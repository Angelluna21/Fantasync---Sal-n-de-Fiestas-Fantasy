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
            'estado_pago' => 'nullable|string',
            'monto_total' => 'nullable|numeric',
            'pagos_extra' => 'nullable|array',
            'pagos_extra.*.concepto' => 'required_with:pagos_extra|string|max:255',
            'pagos_extra.*.monto' => 'required_with:pagos_extra|numeric|min:0',
            'dias_trabajados' => 'required|array|min:1',
            'dias_trabajados.*.evento_id' => 'required|exists:eventos,id',
            'dias_trabajados.*.fecha_trabajo' => 'required|date',
            'dias_trabajados.*.puesto' => 'required|string|max:255',
            'dias_trabajados.*.salario_base' => 'required|numeric',
            'dias_trabajados.*.horas_extra' => 'nullable|integer',
            'dias_trabajados.*.subtotal' => 'required|numeric',
            'metodo_pago' => 'nullable|string',
            'observaciones' => 'nullable|string',
        ]);

        $validated['estado_pago'] = $validated['estado_pago'] ?? 'Pendiente';

        $nomina = Nomina::create([
            'nombre_empleado' => $validated['nombre_empleado'],
            'estado_pago' => $validated['estado_pago'],
            'pagos_extra' => $validated['pagos_extra'] ?? null,
            'monto_total' => $validated['monto_total'] ?? 0,
            'metodo_pago' => $validated['metodo_pago'] ?? null,
            'observaciones' => $validated['observaciones'] ?? null,
        ]);

        foreach ($validated['dias_trabajados'] as $dia) {
            $nomina->detalles()->create([
                'evento_id' => $dia['evento_id'],
                'fecha_trabajo' => $dia['fecha_trabajo'],
                'puesto' => $dia['puesto'],
                'salario_base' => $dia['salario_base'],
                'horas_extra' => $dia['horas_extra'] ?? 0,
                'subtotal' => $dia['subtotal'],
            ]);
        }

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
            'estado_pago' => 'nullable|string',
            'monto_total' => 'nullable|numeric',
            'pagos_extra' => 'nullable|array',
            'pagos_extra.*.concepto' => 'required_with:pagos_extra|string|max:255',
            'pagos_extra.*.monto' => 'required_with:pagos_extra|numeric|min:0',
            'dias_trabajados' => 'required|array|min:1',
            'dias_trabajados.*.evento_id' => 'required|exists:eventos,id',
            'dias_trabajados.*.fecha_trabajo' => 'required|date',
            'dias_trabajados.*.puesto' => 'required|string|max:255',
            'dias_trabajados.*.salario_base' => 'required|numeric',
            'dias_trabajados.*.horas_extra' => 'nullable|integer',
            'dias_trabajados.*.subtotal' => 'required|numeric',
            'metodo_pago' => 'nullable|string',
            'observaciones' => 'nullable|string',
        ]);

        $nomina->update([
            'nombre_empleado' => $validated['nombre_empleado'],
            'estado_pago' => $validated['estado_pago'] ?? 'Pendiente',
            'pagos_extra' => $validated['pagos_extra'] ?? null,
            'monto_total' => $validated['monto_total'] ?? 0,
            'metodo_pago' => $validated['metodo_pago'] ?? null,
            'observaciones' => $validated['observaciones'] ?? null,
        ]);

        $nomina->detalles()->delete();
        foreach ($validated['dias_trabajados'] as $dia) {
            $nomina->detalles()->create([
                'evento_id' => $dia['evento_id'],
                'fecha_trabajo' => $dia['fecha_trabajo'],
                'puesto' => $dia['puesto'],
                'salario_base' => $dia['salario_base'],
                'horas_extra' => $dia['horas_extra'] ?? 0,
                'subtotal' => $dia['subtotal'],
            ]);
        }

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

        $query = Nomina::with('detalles.evento');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nombre_empleado', 'like', '%' . $search . '%')
                  ->orWhereHas('detalles', function($dq) use ($search) {
                      $dq->where('puesto', 'like', '%' . $search . '%');
                  });
            });
        }

        $nominas = $query->orderBy('created_at', 'desc')->get();
        $totalEmpleados = $nominas->count();
        $totalAPagar = $nominas->sum('monto_total');
        
        $puestosOperacion = ['Pista (meseros)', 'Dj', 'Puerta', 'Capitan de meseros', 'Barra', 'Nana'];
        $puestosCocina = ['Cocinera', 'Auxiliar de cocina'];
        $puestosOficina = ['Encargada', 'Oficina'];

        $operacionTotal = 0;
        $cocinaTotal = 0;
        $oficinaTotal = 0;

        foreach ($nominas as $nomina) {
            foreach ($nomina->detalles as $detalle) {
                if (in_array($detalle->puesto, $puestosOperacion)) {
                    $operacionTotal += $detalle->subtotal;
                } elseif (in_array($detalle->puesto, $puestosCocina)) {
                    $cocinaTotal += $detalle->subtotal;
                } elseif (in_array($detalle->puesto, $puestosOficina)) {
                    $oficinaTotal += $detalle->subtotal;
                }
            }
        }

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('nominas.reporte-pdf', compact(
            'nominas', 'totalEmpleados', 'totalAPagar', 'operacionTotal', 'cocinaTotal', 'oficinaTotal', 'search'
        ))->setPaper('a4', 'landscape');
        
        return $pdf->stream('reporte_nominas_' . date('Y-m-d') . '.pdf');
    }
}
