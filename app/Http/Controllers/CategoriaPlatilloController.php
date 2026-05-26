<?php

namespace App\Http\Controllers;

use App\Models\CategoriaPlatillo;
use Illuminate\Http\Request;

class CategoriaPlatilloController extends Controller
{
    public function index()
    {
        return response()->json(CategoriaPlatillo::with('platillos')->get());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:60',
            'orden' => 'required|integer|min:1',
        ]);

        $categoria = CategoriaPlatillo::create($data);

        return response()->json($categoria, 201);
    }

    public function show(CategoriaPlatillo $categoriaPlatillo)
    {
        return response()->json($categoriaPlatillo->load('platillos'));
    }

    public function update(Request $request, CategoriaPlatillo $categoriaPlatillo)
    {
        $data = $request->validate([
            'nombre' => 'sometimes|string|max:60',
            'orden' => 'sometimes|integer|min:1',
        ]);

        $categoriaPlatillo->update($data);

        return response()->json($categoriaPlatillo);
    }

    public function destroy(CategoriaPlatillo $categoriaPlatillo)
    {
        $categoriaPlatillo->delete();

        return response()->noContent();
    }
}
