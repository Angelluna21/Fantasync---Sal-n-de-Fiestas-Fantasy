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
    // The `show` method is kept for the "live preview" from the session draft.
    // It's complex due to its fallback logic for showing a demo.
    public function show()
    {
        $draft = session('contract_draft', []);
        $contractId = $draft['contract_id'] ?? null;
        
        $contract = $contractId ? Contrato::find($contractId) : null;

        if (! $contract) {
            // If no persisted contract is found, build a temporary one from the session draft.
            $contract = new Contrato(is_array($draft) ? $draft : []);
        }

        // Fallback values for properties that might not be in the draft yet.
        $contract->cliente ??= 'Cliente demo';
        $contract->correo ??= 'cliente@fantasync.local';
        $contract->telefono ??= '55 0000 0000';
        $contract->evento_fecha ??= '2026-06-12';
        $contract->recepcion_hora ??= '15:00 hrs';
        $contract->inicio_hora ??= '16:30 hrs';
        $contract->tipo_evento ??= 'Comunión';
        $contract->festejado ??= 'Nombre del festejado';
        $contract->estado ??= 'cotizacion';
        $contract->horas_evento ??= 5;
        $contract->num_adultos ??= 50;
        $contract->num_ninos ??= 20;
        $contract->cliente_domicilio ??= 'Domicilio conocido';
        $contract->cliente_ine ??= 'ABC123456DEF';
        $contract->manteleria_color ??= 'Blanco';

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
            Str::slug($contrato->cliente)
        );

        return $pdf->stream($filename);
    }

    /**
     * Gathers and prepares all data related to a contract for view presentation.
     */
    private function gatherContractData(Contrato $contrato): array
    {
        // Eager load relationships for efficiency if they haven't been loaded yet.
        $contrato->loadMissing('salon.sucursal');

        // The 'platillos' attribute on Contrato is a JSON array of IDs.
        // We ensure it's loaded as a relationship for consistent access.
        if (! $contrato->relationLoaded('platillos')) {
            $platilloIds = $contrato->platillos ?? [];
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
                'cantidad' => 1, // This might need more complex logic based on your needs
                'precio' => (float) $platillo->precio,
                'subtotal' => (float) $platillo->precio,
            ];
        })->values()->all();

        $allExtras = config('fantasync.extras', []);
        $selectedExtras = [];
        $contractExtras = is_array($contrato->extras) ? $contrato->extras : [];
        foreach ($allExtras as $key => $extra) {
            if (! empty($contractExtras[$key] ?? false)) {
                $selectedExtras[] = $extra;
            }
        }

        $subtotalMenu = $platillosCollection->sum('precio');
        $subtotalExtras = array_sum(array_column($selectedExtras, 'precio'));

        // Use the saved total if available (from a persisted contract), otherwise calculate it.
        $total = $contrato->total ?? ($subtotalMenu + $subtotalExtras);

        // This payment logic is hardcoded and should ideally be more dynamic.
        $payments = [
            ['label' => 'Anticipo de reserva', 'value' => 2500],
            ['label' => 'Abono mensual', 'value' => max(4000, round($total * 0.2))],
            ['label' => '50% mínimo antes de 30 días', 'value' => 'Requerido'],
            ['label' => 'Liquidación final antes de 15 días', 'value' => 'Requerido'],
        ];

        $eventoFecha = Carbon::parse($contrato->evento_fecha)
            ->locale('es')
            ->translatedFormat('d \d\e F \d\e Y');

        return [
            'contrato' => $contrato, // Pass the full object for flexibility
            'cliente' => $contrato->cliente,
            'correo' => $contrato->correo,
            'telefono' => $contrato->telefono,
            'clienteDomicilio' => $contrato->cliente_domicilio,
            'clienteIne' => $contrato->cliente_ine,
            'eventoFecha' => $eventoFecha,
            'recepcionHora' => $contrato->recepcion_hora,
            'inicioHora' => $contrato->inicio_hora,
            'tipoEvento' => $contrato->tipo_evento,
            'festejado' => $contrato->festejado,
            'horasEvento' => $contrato->horas_evento,
            'numAdultos' => $contrato->num_adultos,
            'numNinos' => $contrato->num_ninos,
            'manteleriaColor' => $contrato->manteleria_color,
            'salonNombre' => $contrato->salon?->nombre ?? 'Seleccione un salón',
            'salonSucursal' => $contrato->salon?->sucursal?->nombre ?? 'Sucursal no asignada',
            'menuItems' => $menuItems,
            'extras' => $selectedExtras,
            'payments' => $payments,
            'subtotalMenu' => $subtotalMenu,
            'subtotalExtras' => $subtotalExtras,
            'total' => $total,
            'estadoContrato' => $contrato->estado,
        ];
    }
}
