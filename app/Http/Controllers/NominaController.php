<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Nomina;
use App\Models\Evento;

class NominaController extends Controller
{
    public function index()
    {
        return view('nominas.index');
    }

    public function create()
    {
        $eventos = Evento::with('contrato')->get();
        return view('nominas.create', compact('eventos'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre_empleado' => 'required|string|max:255',
            'puesto' => 'required|string|max:255',
            'salario_base' => 'required|numeric',
            'horas_extra' => 'nullable|integer',
            'fecha_trabajo' => 'required|date',
            'evento_id' => 'required|exists:eventos,id',
            'estado_pago' => 'nullable|string',
            'monto_total' => 'nullable|numeric',
            'metodo_pago' => 'nullable|string',
            'observaciones' => 'nullable|string',
        ]);

        $validated['horas_extra'] = $validated['horas_extra'] ?? 0;
        $validated['estado_pago'] = $validated['estado_pago'] ?? 'Pendiente';

        Nomina::create($validated);

        return redirect()->route('nominas.index')->with('success', 'Registro creado exitosamente.');
    }

    public function edit(Nomina $nomina)
    {
        $eventos = Evento::with('contrato')->get();
        return view('nominas.edit', compact('nomina', 'eventos'));
    }

    public function update(Request $request, Nomina $nomina)
    {
        $validated = $request->validate([
            'nombre_empleado' => 'required|string|max:255',
            'puesto' => 'required|string|max:255',
            'salario_base' => 'required|numeric',
            'horas_extra' => 'nullable|integer',
            'fecha_trabajo' => 'required|date',
            'evento_id' => 'required|exists:eventos,id',
            'estado_pago' => 'nullable|string',
            'monto_total' => 'nullable|numeric',
            'metodo_pago' => 'nullable|string',
            'observaciones' => 'nullable|string',
        ]);

        $validated['horas_extra'] = $validated['horas_extra'] ?? 0;

        $nomina->update($validated);

        return redirect()->route('nominas.index')->with('success', 'Registro actualizado exitosamente.');
    }

    public function destroy(Nomina $nomina)
    {
        $nomina->delete();
        return redirect()->route('nominas.index')->with('success', 'Registro eliminado exitosamente.');
    }

    public function reportePdf(Request $request)
    {
        $search = $request->input('search');

        $query = Nomina::with('evento');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nombre_empleado', 'like', '%' . $search . '%')
                  ->orWhere('puesto', 'like', '%' . $search . '%');
            });
        }

        $nominas = $query->orderBy('created_at', 'desc')->get();
        $totalEmpleados = $nominas->count();
        $totalAPagar = $nominas->sum('monto_total');
        
        $puestosOperacion = ['Pista (meseros)', 'Dj', 'Puerta', 'Capitan de meseros', 'Barra', 'Nana'];
        $puestosCocina = ['Cocinera', 'Auxiliar de cocina'];
        $puestosOficina = ['Encargada', 'Oficina'];

        $operacionTotal = $nominas->whereIn('puesto', $puestosOperacion)->sum('monto_total');
        $cocinaTotal = $nominas->whereIn('puesto', $puestosCocina)->sum('monto_total');
        $oficinaTotal = $nominas->whereIn('puesto', $puestosOficina)->sum('monto_total');

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('nominas.reporte-pdf', compact(
            'nominas', 'totalEmpleados', 'totalAPagar', 'operacionTotal', 'cocinaTotal', 'oficinaTotal', 'search'
        ));
        
        return $pdf->stream('reporte_nominas_' . date('Y-m-d') . '.pdf');
    }
}
