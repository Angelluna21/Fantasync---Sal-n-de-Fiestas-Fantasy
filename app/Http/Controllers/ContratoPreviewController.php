<?php

namespace App\Http\Controllers;

use App\Models\Contrato;
use App\Models\Platillo;
use App\Models\Salon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class ContratoPreviewController extends Controller
{
    // The `show` method handles both "live preview" from the session draft and persistent previews from the database.
    public function show()
    {
        $contractId = request()->query('id');
        
        if (! $contractId) {
            $draft = session('contract_draft', []);
            $contractId = $draft['contract_id'] ?? null;
        }

        $contract = $contractId ? Contrato::find($contractId) : null;

        if (! $contract) {
            // If no persisted contract is found, build a temporary one from the session draft.
            $draft = session('contract_draft', []);
            $contract = new Contrato(is_array($draft) ? $draft : []);
        }

        return view('contrato-demo', $this->gatherContractData($contract));
    }

    /**
     * Generates and streams a PDF for a given contract.
     */
    public function download(Contrato $contract)
    {
        $data = $this->gatherContractData($contract);

        // This requires a 'contrato-pdf.blade.php' view, styled to match your PDF document.
        $pdf = Pdf::loadView('contrato-pdf', $data);

        $filename = sprintf(
            'contrato-%s-%s.pdf',
            $contract->id,
            Str::slug($data['cliente'] ?? 'cliente')
        );

        return $pdf->stream($filename);
    }

    /**
     * Gathers and prepares all data related to a contract for view presentation.
     */
    private function gatherContractData(Contrato $contract): array
    {
        // Eager load relationships for efficiency.
        $contract->loadMissing(['evento.cliente', 'evento.salones.sucursal']);

        $event = $contract->evento;
        $client = $event?->cliente;
        $venue = $event?->salones?->first();

        // 1. Map client fields
        $clientName = $client?->nombre_completo ?? ($contract->cliente ?? 'Cliente demo');
        $clientEmail = $client?->correo_electronico ?? ($contract->correo ?? 'cliente@fantasync.local');
        $clientPhone = $client?->celular ?? ($contract->telefono ?? '55 0000 0000');
        $clientAddress = $client?->domicilio ?? ($contract->cliente_domicilio ?? 'Domicilio conocido');
        $clientIne = $client?->ine_numero ?? ($contract->cliente_ine ?? 'ABC123456DEF');

        // 2. Map event logistical fields
        $eventDateRaw = $event?->fecha ?? ($contract->evento_fecha ?? '2026-06-12');
        $receptionTime = $event?->hora_recepcion ?? ($contract->recepcion_hora ?? '15:00 hrs');
        $startTime = $event?->hora_inicio ?? ($contract->inicio_hora ?? '16:30 hrs');
        $eventType = $event?->tipo_evento ?? ($contract->tipo_evento ?? 'Comunión');
        $honoree = $event?->nombre_festejado ?? ($contract->festejado ?? 'Nombre del festejado');
        $durationHours = $event?->horas_duracion ?? ($contract->horas_evento ?? 5);
        $linenColor = $event?->color_manteleria ?? ($contract->manteleria_color ?? 'Blanco');

        $adultCount = $venue?->pivot?->adultos ?? ($contract->num_adultos ?? 50);
        $kidCount = $venue?->pivot?->ninos ?? ($contract->num_ninos ?? 20);

        $venueName = $venue?->nombre ?? ($contract->salon?->nombre ?? 'Seleccione un salón');
        $branchName = $venue?->sucursal?->nombre ?? ($contract->salon?->sucursal?->nombre ?? 'Sucursal no asignada');

        // 3. Map dishes (for persisted contracts, parsed from JSON notes in the database)
        if (! $contract->relationLoaded('platillos')) {
            $dishIds = $contract->platillos ?? [];
            if (empty($dishIds) && $event && $event->notas) {
                if (preg_match('/Platillos:\s*([0-9,\s]+)/', $event->notas, $matches)) {
                    $dishIds = array_filter(array_map('trim', explode(',', $matches[1])));
                }
            }
            if (! is_array($dishIds)) {
                $dishIds = json_decode($dishIds, true) ?: [];
            }

            $dishesCollection = collect();
            if (! empty($dishIds)) {
                $dishIds = array_map('intval', $dishIds);
                $dishesCollection = Platillo::with('categoriaPlatillo')->whereIn('id', $dishIds)->get();
            }
            $contract->setRelation('platillos', $dishesCollection);
        }
        $dishesCollection = $contract->getRelation('platillos');

        $menuItems = $dishesCollection->map(function (Platillo $dish) {
            return [
                'nombre' => $dish->nombre,
                'detalle' => $dish->categoriaPlatillo?->nombre ?? 'Menú principal',
                'cantidad' => 1,
                'precio' => (float) $dish->precio,
                'subtotal' => (float) $dish->precio,
            ];
        })->values()->all();

        // 4. Map extras
        $allExtras = config('fantasync.extras', []);
        $selectedExtras = [];
        $contractExtras = $contract->servicios_extras ?? ($contract->extras ?? []);
        if (! is_array($contractExtras)) {
            $contractExtras = json_decode($contractExtras, true) ?: [];
        }
        foreach ($allExtras as $key => $extra) {
            if (! empty($contractExtras[$key] ?? false)) {
                $selectedExtras[] = $extra;
            }
        }

        $menuSubtotal = $dishesCollection->sum('precio');
        $extrasSubtotal = array_sum(array_column($selectedExtras, 'precio'));

        // Use the saved total if available, otherwise calculate it.
        $totalAmount = $contract->monto_total ?? ($contract->total ?? ($menuSubtotal + $extrasSubtotal));

        // Suggested Payment Plan
        $paymentPlan = [
            ['label' => 'Anticipo de reserva', 'value' => $contract->anticipo ?? 2500],
            ['label' => 'Abono mensual', 'value' => max(4000, round($totalAmount * 0.2))],
            ['label' => '50% mínimo antes de 30 días', 'value' => 'Requerido'],
            ['label' => 'Liquidación final antes de 15 días', 'value' => 'Requerido'],
        ];

        $formattedEventDate = Carbon::parse($eventDateRaw)
            ->locale('es')
            ->translatedFormat('d \d\e F \d\e Y');

        return [
            'contrato' => $contract,
            'cliente' => $clientName,
            'correo' => $clientEmail,
            'telefono' => $clientPhone,
            'clienteDomicilio' => $clientAddress,
            'clienteIne' => $clientIne,
            'eventoFecha' => $formattedEventDate,
            'recepcionHora' => $receptionTime,
            'inicioHora' => $startTime,
            'tipoEvento' => $eventType,
            'festejado' => $honoree,
            'horasEvento' => $durationHours,
            'numAdultos' => $adultCount,
            'numNinos' => $kidCount,
            'manteleriaColor' => $linenColor,
            'salonNombre' => $venueName,
            'salonSucursal' => $branchName,
            'menuItems' => $menuItems,
            'extras' => $selectedExtras,
            'payments' => $paymentPlan,
            'subtotalMenu' => $menuSubtotal,
            'subtotalExtras' => $extrasSubtotal,
            'total' => $totalAmount,
            'estadoContrato' => $contract->estado ?? ($event?->estado ?? 'cotizacion'),
        ];
    }
}
