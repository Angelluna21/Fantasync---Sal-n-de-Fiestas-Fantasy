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
        $month = $request->input('month');
        $year = $request->input('year');
        $periodo = $request->input('periodo');

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

        if ($month || $year) {
            $query->whereHas('evento', function ($q) use ($month, $year) {
                if ($month) {
                    $q->whereMonth('fecha', $month);
                }
                if ($year) {
                    $q->whereYear('fecha', $year);
                }
            });
        }

        // Filtro rápido de periodo (Semanal, Mensual, Anual) basado en la fecha del evento
        if ($periodo === 'semana') {
            $query->whereHas('evento', function ($q) {
                $q->whereBetween('fecha', [now()->startOfWeek(), now()->endOfWeek()]);
            });
        } elseif ($periodo === 'mes') {
            $query->whereHas('evento', function ($q) {
                $q->whereMonth('fecha', now()->month)->whereYear('fecha', now()->year);
            });
        } elseif ($periodo === 'anio') {
            $query->whereHas('evento', function ($q) {
                $q->whereYear('fecha', now()->year);
            });
        }

        // Clonar el query base para los KPIs (usando los mismos filtros)
        // Usar clone para no afectar la paginación ni el order_by si hiciéramos otras consultas
        $kpiQuery = clone $query;
        // Para remover los selects, with o joins si no son necesarios, pero Eloquent maneja bien el count/sum
        $totalContratado = (clone $kpiQuery)->sum('monto_total');
        $saldoPendiente = (clone $kpiQuery)->sum('saldo_pendiente');
        $contratosActivos = (clone $kpiQuery)->count();

        $contratos = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('contratos.index', compact('contratos', 'search', 'month', 'year', 'periodo', 'totalContratado', 'saldoPendiente', 'contratosActivos'));
    }

    /**
     * Display the specified contract details in a web view.
     */
    public function show($id)
    {
        $contrato = Contrato::with([
            'evento.cliente', 
            'evento.salones.sucursal', 
            'evento.eventoSalones.platillos'
        ])->findOrFail($id);

        return view('contratos.show', compact('contrato'));
    }

    /**
     * Display the specified contract as a PDF preview/download.
     */
    public function pdf($id)
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
