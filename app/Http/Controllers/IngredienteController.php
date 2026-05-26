<?php

namespace App\Http\Controllers;

use App\Models\Ingrediente;
use Illuminate\Http\Request;

class IngredienteController extends Controller
{
    public function index()
    {
        return response()->json(Ingrediente::with('platillos')->get());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:120',
            'unidad' => 'required|in:kg,gr,l,ml,pz',
            'platillo_ids' => 'sometimes|array',
            'platillo_ids.*' => 'integer|exists:platillos,id',
        ]);

        $ingrediente = Ingrediente::create($request->only(['nombre', 'unidad']));

        if (isset($data['platillo_ids'])) {
            $ingrediente->platillos()->sync($data['platillo_ids']);
        }

        return response()->json($ingrediente->load('platillos'), 201);
    }

    public function show(Ingrediente $ingrediente)
    {
        return response()->json($ingrediente->load('platillos'));
    }

    public function update(Request $request, Ingrediente $ingrediente)
    {
        $data = $request->validate([
            'nombre' => 'sometimes|string|max:120',
            'unidad' => 'sometimes|in:kg,gr,l,ml,pz',
            'platillo_ids' => 'sometimes|array',
            'platillo_ids.*' => 'integer|exists:platillos,id',
        ]);

        $ingrediente->update($request->only(['nombre', 'unidad']));

        if (array_key_exists('platillo_ids', $data)) {
            $ingrediente->platillos()->sync($data['platillo_ids'] ?? []);
        }

        return response()->json($ingrediente->load('platillos'));
    }

    public function destroy(Ingrediente $ingrediente)
    {
        $ingrediente->delete();

        return response()->noContent();
    }
}
