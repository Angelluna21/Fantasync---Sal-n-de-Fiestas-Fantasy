<?php

namespace App\Http\Controllers;

use App\Models\Salon;
use Illuminate\Http\Request;

class SalonController extends Controller
{
    public function index()
    {
        return response()->json(Salon::with(['sucursal', 'eventos'])->get());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'sucursal_id' => 'required|integer|exists:sucursales,id',
            'nombre' => 'required|string|max:80',
            'alias' => 'nullable|string|max:20',
            'evento_ids' => 'sometimes|array',
            'evento_ids.*' => 'integer|exists:eventos,id',
        ]);

        $salon = Salon::create($request->only(['sucursal_id', 'nombre', 'alias']));

        if (isset($data['evento_ids'])) {
            $salon->eventos()->sync($data['evento_ids']);
        }

        return response()->json($salon->load(['sucursal', 'eventos']), 201);
    }

    public function show(Salon $salon)
    {
        return response()->json($salon->load(['sucursal', 'eventos']));
    }

    public function update(Request $request, Salon $salon)
    {
        $data = $request->validate([
            'sucursal_id' => 'sometimes|integer|exists:sucursales,id',
            'nombre' => 'sometimes|string|max:80',
            'alias' => 'nullable|string|max:20',
            'evento_ids' => 'sometimes|array',
            'evento_ids.*' => 'integer|exists:eventos,id',
        ]);

        $salon->update($request->only(['sucursal_id', 'nombre', 'alias']));

        if (array_key_exists('evento_ids', $data)) {
            $salon->eventos()->sync($data['evento_ids'] ?? []);
        }

        return response()->json($salon->load(['sucursal', 'eventos']));
    }

    public function destroy(Salon $salon)
    {
        $salon->delete();

        return response()->noContent();
    }
}
