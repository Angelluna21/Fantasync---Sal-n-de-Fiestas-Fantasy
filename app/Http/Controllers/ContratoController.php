<?php

namespace App\Http\Controllers;

use App\Models\Contrato;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ContratoController extends Controller
{
    /**
     * Display a listing of the contracts.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $query = Contrato::with(['evento.cliente', 'evento.salones.sucursal']);

        if ($search) {
            $query->whereHas('evento', function ($q) use ($search) {
                $q->where('titulo', 'like', "%{$search}%")
                  ->orWhereHas('cliente', function ($q2) use ($search) {
                      $q2->where('nombre_completo', 'like', "%{$search}%")
                         ->orWhere('celular', 'like', "%{$search}%");
                  });
            });
        }

        $contratos = $query->orderBy('created_at', 'desc')->paginate(10);
        $totalContratado = Contrato::sum('monto_total');
        $saldoPendiente = Contrato::sum('saldo_pendiente');
        $contratosActivos = Contrato::count();

        return view('contratos.index', compact('contratos', 'search', 'totalContratado', 'saldoPendiente', 'contratosActivos'));
    }

    /**
     * Display the specified contract as a PDF preview/download.
     */
    public function show($id)
    {
        $contrato = Contrato::with([
            'evento.cliente', 
            'evento.salones.sucursal', 
            'evento.eventoSalones.platillos'
        ])->findOrFail($id);

        $pdf = Pdf::loadView('contratos.pdf', compact('contrato'));
        
        return $pdf->stream('Contrato_FantaSync_' . $contrato->id . '.pdf');
    }

    /**
     * Remove the specified contract from storage.
     */
    public function destroy($id)
    {
        $contrato = Contrato::findOrFail($id);
        
        // Al eliminar el contrato, si el evento no ha ocurrido, podríamos cambiar el estado del evento a 'cancelado'
        if ($contrato->evento && $contrato->evento->estado != 'finalizado') {
            $contrato->evento->update(['estado' => 'cancelado']);
        }

        $contrato->delete();

        return redirect()->route('contratos.index')->with('success', 'Contrato anulado correctamente y evento cancelado.');
    }
}
