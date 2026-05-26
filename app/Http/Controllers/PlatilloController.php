<?php

namespace App\Http\Controllers;

use App\Models\Platillo;
use Illuminate\Http\Request;

class PlatilloController extends Controller
{
    public function index()
    {
        return response()->json(Platillo::with(['categoriaPlatillo', 'ingredientes'])->get());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'categoria_platillo_id' => 'required|integer|exists:categoria_platillos,id',
            'nombre' => 'required|string|max:100',
            'precio' => 'required|numeric|min:0',
            'ingrediente_ids' => 'sometimes|array',
            'ingrediente_ids.*' => 'integer|exists:ingredientes,id',
        ]);

        $platillo = Platillo::create($request->only(['categoria_platillo_id', 'nombre', 'precio']));

        if (isset($data['ingrediente_ids'])) {
            $platillo->ingredientes()->sync($data['ingrediente_ids']);
        }

        return response()->json($platillo->load(['categoriaPlatillo', 'ingredientes']), 201);
    }

    public function show(Platillo $platillo)
    {
        return response()->json($platillo->load(['categoriaPlatillo', 'ingredientes']));
    }

    public function update(Request $request, Platillo $platillo)
    {
        $data = $request->validate([
            'categoria_platillo_id' => 'sometimes|integer|exists:categoria_platillos,id',
            'nombre' => 'sometimes|string|max:100',
            'precio' => 'sometimes|numeric|min:0',
            'ingrediente_ids' => 'sometimes|array',
            'ingrediente_ids.*' => 'integer|exists:ingredientes,id',
        ]);

        $platillo->update($request->only(['categoria_platillo_id', 'nombre', 'precio']));

        if (array_key_exists('ingrediente_ids', $data)) {
            $platillo->ingredientes()->sync($data['ingrediente_ids'] ?? []);
        }

        return response()->json($platillo->load(['categoriaPlatillo', 'ingredientes']));
    }

    public function destroy(Platillo $platillo)
    {
        $platillo->delete();

        return response()->noContent();
    }
}
