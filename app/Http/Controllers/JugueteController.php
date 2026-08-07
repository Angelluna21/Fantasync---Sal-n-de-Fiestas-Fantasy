<?php

namespace App\Http\Controllers;

use App\Models\Juguete;
use Illuminate\Http\Request;

class JugueteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Juguete::query();
        if ($request->has('search') && $request->search != '') {
            $query->where('nombre', 'like', '%' . $request->search . '%')
                  ->orWhere('descripcion', 'like', '%' . $request->search . '%');
        }
        $juguetes = $query->orderBy('nombre')->get();
        
        $startOfWeek = now()->startOfWeek()->format('Y-m-d 00:00:00');
        $endOfWeek = now()->endOfWeek()->format('Y-m-d 23:59:59');

        $contratos = \App\Models\Contrato::with('evento.cliente')
            ->whereHas('evento', function($q) use ($startOfWeek, $endOfWeek) {
                $q->whereBetween('fecha', [$startOfWeek, $endOfWeek])
                  ->whereIn('estado', ['confirmado', 'finalizado']);
            })
            ->get()
            ->sortBy('evento.fecha');

        return view('juguetes.index', compact('juguetes', 'contratos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('juguetes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'stock_actual' => 'required|integer|min:0',
            'stock_minimo' => 'required|integer|min:0',
        ]);

        Juguete::create($validated);

        return redirect()->route('juguetes.index')->with('success', 'Juguete agregado exitosamente.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Juguete $juguete)
    {
        return view('juguetes.edit', compact('juguete'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Juguete $juguete)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'stock_actual' => 'required|integer|min:0',
            'stock_minimo' => 'required|integer|min:0',
        ]);

        $juguete->update($validated);

        return redirect()->route('juguetes.index')->with('success', 'Juguete actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Juguete $juguete)
    {
        $juguete->delete();
        return redirect()->route('juguetes.index')->with('success', 'Juguete eliminado.');
    }

    /**
     * Restar stock del juguete (uso para Show o Bienvenida).
     */
    public function restarStock(Request $request, Juguete $juguete)
    {
        $validated = $request->validate([
            'cantidad' => 'required|integer|min:1',
            'contrato_id' => 'nullable|exists:contratos,id'
        ]);

        if ($juguete->stock_actual < $validated['cantidad']) {
            return back()->withErrors(['cantidad' => 'No hay suficiente stock para restar esa cantidad.']);
        }

        $juguete->stock_actual -= $validated['cantidad'];
        $juguete->save();

        \App\Models\JugueteMovimiento::create([
            'juguete_id' => $juguete->id,
            'contrato_id' => $validated['contrato_id'] ?? null,
            'cantidad' => $validated['cantidad'],
            'tipo' => 'salida'
        ]);

        return back()->with('success', "Se restaron {$validated['cantidad']} unidades de {$juguete->nombre}.");
    }

    /**
     * Ver el historial de movimientos de un juguete.
     */
    public function historial(Juguete $juguete)
    {
        $movimientos = $juguete->movimientos()->with('contrato.evento.cliente')->orderByDesc('created_at')->get();
        return view('juguetes.historial', compact('juguete', 'movimientos'));
    }

    /**
     * Limpiar (borrar) el historial de movimientos de un juguete.
     */
    public function limpiarHistorial(Request $request, Juguete $juguete)
    {
        if (!\Illuminate\Support\Facades\Hash::check($request->password, auth()->user()->password)) {
            return back()->withErrors(['password' => 'La contraseña ingresada es incorrecta. No se limpió el historial.']);
        }

        $juguete->movimientos()->delete();
        return back()->with('success', 'El historial de este juguete ha sido limpiado.');
    }
}
