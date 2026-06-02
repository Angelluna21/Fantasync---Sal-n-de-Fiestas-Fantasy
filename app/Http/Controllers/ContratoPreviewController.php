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
    public function download(Contrato $contrato)
    {
        $data = $this->gatherContractData($contrato);

        // This requires a 'contrato-pdf.blade.php' view, styled to match your PDF document.
        // You can use HTML and CSS (with some limitations) to structure the document.
        $pdf = Pdf::loadView('contrato-pdf', $data);

        $filename = sprintf(
            'contrato-%s-%s.pdf',
            $contrato->id,
            Str::slug($data['cliente'] ?? 'cliente')
        );

        return $pdf->stream($filename);
    }

    /**
     * Gathers and prepares all data related to a contract for view presentation.
     */
    private function gatherContractData(Contrato $contrato): array
    {
        // Eager load relationships for efficiency.
        $contrato->loadMissing(['evento.cliente', 'evento.salones.sucursal']);

        $evento = $contrato->evento;
        $clienteObj = $evento?->cliente;
        $salonObj = $evento?->salones?->first();

        // 1. Map client fields (fallback to flat contract draft values if no database relation exists)
        $clienteName = $clienteObj?->nombre_completo ?? ($contrato->cliente ?? 'Cliente demo');
        $clienteCorreo = $clienteObj?->correo_electronico ?? ($contrato->correo ?? 'cliente@fantasync.local');
        $clienteTelefono = $clienteObj?->celular ?? ($contrato->telefono ?? '55 0000 0000');
        $clienteDom = $clienteObj?->domicilio ?? ($contrato->cliente_domicilio ?? 'Domicilio conocido');
        $clienteIneNum = $clienteObj?->ine_numero ?? ($contrato->cliente_ine ?? 'ABC123456DEF');

        // 2. Map event logistical fields
        $evtFechaRaw = $evento?->fecha ?? ($contrato->evento_fecha ?? '2026-06-12');
        $recHora = $evento?->hora_recepcion ?? ($contrato->recepcion_hora ?? '15:00 hrs');
        $iniHora = $evento?->hora_inicio ?? ($contrato->inicio_hora ?? '16:30 hrs');
        $tipo = $evento?->tipo_evento ?? ($contrato->tipo_evento ?? 'Comunión');
        $fest = $evento?->nombre_festejado ?? ($contrato->festejado ?? 'Nombre del festejado');
        $horas = $evento?->horas_duracion ?? ($contrato->horas_evento ?? 5);
        $mant = $evento?->color_manteleria ?? ($contrato->manteleria_color ?? 'Blanco');

        $adults = $salonObj?->pivot?->adultos ?? ($contrato->num_adultos ?? 50);
        $kids = $salonObj?->pivot?->ninos ?? ($contrato->num_ninos ?? 20);

        $salonName = $salonObj?->nombre ?? ($contrato->salon?->nombre ?? 'Seleccione un salón');
        $sucursalName = $salonObj?->sucursal?->nombre ?? ($contrato->salon?->sucursal?->nombre ?? 'Sucursal no asignada');

        // 3. Map platillos (for persisted contracts, parsed from JSON notes in the database)
        if (! $contrato->relationLoaded('platillos')) {
            $platilloIds = $contrato->platillos ?? [];
            if (empty($platilloIds) && $evento && $evento->notas) {
                if (preg_match('/Platillos:\s*([0-9,\s]+)/', $evento->notas, $matches)) {
                    $platilloIds = array_filter(array_map('trim', explode(',', $matches[1])));
                }
            }
            if (! is_array($platilloIds)) {
                $platilloIds = json_decode($platilloIds, true) ?: [];
            }

            $platillosCollection = collect();
            if (! empty($platilloIds)) {
                $platilloIds = array_map('intval', $platilloIds);
                $platillosCollection = Platillo::with('categoriaPlatillo')->whereIn('id', $platilloIds)->get();
            }
            $contrato->setRelation('platillos', $platillosCollection);
        }
        $platillosCollection = $contrato->getRelation('platillos');

        $menuItems = $platillosCollection->map(function (Platillo $platillo) {
            return [
                'nombre' => $platillo->nombre,
                'detalle' => $platillo->categoriaPlatillo?->nombre ?? 'Menú principal',
                'cantidad' => 1,
                'precio' => (float) $platillo->precio,
                'subtotal' => (float) $platillo->precio,
            ];
        })->values()->all();

        // 4. Map extras (support both database JSON column and session draft array)
        $allExtras = config('fantasync.extras', []);
        $selectedExtras = [];
        $contractExtras = $contrato->servicios_extras ?? ($contrato->extras ?? []);
        if (! is_array($contractExtras)) {
            $contractExtras = json_decode($contractExtras, true) ?: [];
        }
        foreach ($allExtras as $key => $extra) {
            if (! empty($contractExtras[$key] ?? false)) {
                $selectedExtras[] = $extra;
            }
        }

        $subtotalMenu = $platillosCollection->sum('precio');
        $subtotalExtras = array_sum(array_column($selectedExtras, 'precio'));

        // Use the saved total if available, otherwise calculate it.
        $total = $contrato->monto_total ?? ($contrato->total ?? ($subtotalMenu + $subtotalExtras));

        // Suggested Payment Plan
        $payments = [
            ['label' => 'Anticipo de reserva', 'value' => $contrato->anticipo ?? 2500],
            ['label' => 'Abono mensual', 'value' => max(4000, round($total * 0.2))],
            ['label' => '50% mínimo antes de 30 días', 'value' => 'Requerido'],
            ['label' => 'Liquidación final antes de 15 días', 'value' => 'Requerido'],
        ];

        $eventoFecha = Carbon::parse($evtFechaRaw)
            ->locale('es')
            ->translatedFormat('d \d\e F \d\e Y');

        return [
            'contrato' => $contrato,
            'cliente' => $clienteName,
            'correo' => $clienteCorreo,
            'telefono' => $clienteTelefono,
            'clienteDomicilio' => $clienteDom,
            'clienteIne' => $clienteIneNum,
            'eventoFecha' => $eventoFecha,
            'recepcionHora' => $recHora,
            'inicioHora' => $iniHora,
            'tipoEvento' => $tipo,
            'festejado' => $fest,
            'horasEvento' => $horas,
            'numAdultos' => $adults,
            'numNinos' => $kids,
            'manteleriaColor' => $mant,
            'salonNombre' => $salonName,
            'salonSucursal' => $sucursalName,
            'menuItems' => $menuItems,
            'extras' => $selectedExtras,
            'payments' => $payments,
            'subtotalMenu' => $subtotalMenu,
            'subtotalExtras' => $subtotalExtras,
            'total' => $total,
            'estadoContrato' => $contrato->estado ?? ($evento?->estado ?? 'cotizacion'),
        ];
    }
    }
}
