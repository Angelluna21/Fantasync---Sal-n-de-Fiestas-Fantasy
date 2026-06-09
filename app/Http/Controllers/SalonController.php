<?php

namespace App\Http\Controllers;

use App\Models\Salon;
use Illuminate\Http\Request;

class SalonController extends Controller
{
    public function index()
    {
        $salones = Salon::with(['sucursal', 'eventos'])->get();
        return view('salones.index', compact('salones'));
    }

    public function create()
    {
        $sucursales = \App\Models\Sucursal::orderBy('nombre')->get();
        return view('salones.create', compact('sucursales'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'sucursal_id' => 'sometimes|integer|exists:sucursales,id',
            'nombre' => 'required|string|max:80|unique:salones,nombre',
            'alias' => 'nullable|string|max:20|unique:salones,alias',
            'capacidad' => 'nullable|integer|min:0',
            'direccion' => 'nullable|string|max:255',
            'estado' => 'sometimes|string|max:30',
            'descripcion' => 'nullable|string',
            'evento_ids' => 'sometimes|array',
            'evento_ids.*' => 'integer|exists:eventos,id',
        ]);

        $sucursalId = $request->input('sucursal_id') ?: (\App\Models\Sucursal::first()->id ?? 1);

        $salon = Salon::create([
            'sucursal_id' => $sucursalId,
            'nombre' => $request->input('nombre'),
            'alias' => $request->input('alias'),
            'capacidad' => $request->input('capacidad'),
            'direccion' => $request->input('direccion'),
            'estado' => $request->input('estado') ?: 'activo',
            'descripcion' => $request->input('descripcion'),
        ]);

        if (isset($data['evento_ids'])) {
            $salon->eventos()->sync($data['evento_ids']);
        }

        return redirect()->route('salones.show', $salon)->with('success', 'Salón creado correctamente');
    }

    public function show(Salon $salon)
    {
        $salon->load(['sucursal', 'eventos']);
        return view('salones.show', compact('salon'));
    }

    public function edit(Salon $salon)
    {
        $sucursales = \App\Models\Sucursal::orderBy('nombre')->get();
        return view('salones.edit', compact('salon', 'sucursales'));
    }

    public function update(Request $request, Salon $salon)
    {
        $data = $request->validate([
            'sucursal_id' => 'sometimes|integer|exists:sucursales,id',
            'nombre' => 'sometimes|string|max:80|unique:salones,nombre,' . $salon->id,
            'alias' => 'nullable|string|max:20|unique:salones,alias,' . $salon->id,
            'capacidad' => 'nullable|integer|min:0',
            'direccion' => 'nullable|string|max:255',
            'estado' => 'sometimes|string|max:30',
            'descripcion' => 'nullable|string',
            'evento_ids' => 'sometimes|array',
            'evento_ids.*' => 'integer|exists:eventos,id',
        ]);

        $sucursalId = $request->input('sucursal_id') ?: $salon->sucursal_id ?: (\App\Models\Sucursal::first()->id ?? 1);

        $salon->update([
            'sucursal_id' => $sucursalId,
            'nombre' => $request->input('nombre') ?: $salon->nombre,
            'alias' => $request->input('alias'),
            'capacidad' => $request->input('capacidad'),
            'direccion' => $request->input('direccion'),
            'estado' => $request->input('estado') ?: $salon->estado ?: 'activo',
            'descripcion' => $request->input('descripcion'),
        ]);

        if (array_key_exists('evento_ids', $data)) {
            $salon->eventos()->sync($data['evento_ids'] ?? []);
        }

        return redirect()->route('salones.show', $salon)->with('success', 'Salón actualizado correctamente');
    }

    public function destroy(Salon $salon)
    {
        $salon->delete();

        return redirect()->route('salones.index')->with('success', 'Salón eliminado correctamente');
    }
}
