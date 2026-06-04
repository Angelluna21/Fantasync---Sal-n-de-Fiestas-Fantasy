<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use Illuminate\Http\Request;

class EventoController extends Controller
{
    public function index()
    {
        $eventos = Evento::with(['cliente', 'salones.sucursal', 'contrato'])
            ->orderBy('fecha', 'desc')
            ->get();

        $totalEventos = $eventos->count();
        $confirmados = 0;
        $cotizaciones = 0;
        $montoTotal = 0;
        $saldoPendiente = 0;

        foreach ($eventos as $ev) {
            if ($ev->estado === 'confirmado') {
                $confirmados++;
            } elseif ($ev->estado === 'cotizacion') {
                $cotizaciones++;
            }

            if ($ev->contrato) {
                $montoTotal += (float) $ev->contrato->monto_total;
                $saldoPendiente += (float) $ev->contrato->saldo_pendiente;
            }
        }

        return view('eventos.index', compact(
            'eventos',
            'totalEventos',
            'confirmados',
            'cotizaciones',
            'montoTotal',
            'saldoPendiente'
        ));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'fecha' => 'required|date',
            'estado' => 'required|in:cotizacion,confirmado,finalizado,cancelado',
            'titulo' => 'required|string|max:150',
            'notas' => 'nullable|string',
            'salon_ids' => 'sometimes|array',
            'salon_ids.*' => 'integer|exists:salones,id',
        ]);

        $evento = Evento::create($request->only(['fecha', 'estado', 'titulo', 'notas']));

        if (isset($data['salon_ids'])) {
            $evento->salones()->sync($data['salon_ids']);
        }

        return response()->json($evento->load('salones'), 201);
    }

    public function show(Evento $evento)
    {
        return response()->json($evento->load('salones'));
    }

    public function update(Request $request, Evento $evento)
    {
        $data = $request->validate([
            'fecha' => 'sometimes|date',
            'estado' => 'sometimes|in:cotizacion,confirmado,finalizado,cancelado',
            'titulo' => 'sometimes|string|max:150',
            'notas' => 'nullable|string',
            'salon_ids' => 'sometimes|array',
            'salon_ids.*' => 'integer|exists:salones,id',
        ]);

        $evento->update($request->only(['fecha', 'estado', 'titulo', 'notas']));

        if (array_key_exists('salon_ids', $data)) {
            $evento->salones()->sync($data['salon_ids'] ?? []);
        }

        return response()->json($evento->load('salones'));
    }

    public function destroy(Evento $evento)
    {
        $evento->delete();

        return response()->noContent();
    }

    public function menuConfig(Evento $evento)
    {
        return view('contratos.menu-config', compact('evento'));
    }
}
