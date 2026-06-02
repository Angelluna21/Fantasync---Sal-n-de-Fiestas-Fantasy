<?php

namespace App\Http\Controllers;

use App\Models\Platillo;
use App\Models\CategoriaPlatillo;
use App\Models\Ingrediente;
use Illuminate\Http\Request;

class PlatilloController extends Controller
{
    public function index()
    {
        $platillos = Platillo::with(['categoriaPlatillo', 'ingredientes'])->orderBy('nombre')->get();
        return view('platillos.index', compact('platillos'));
    }

    public function create()
    {
        $categorias = CategoriaPlatillo::orderBy('nombre')->get();
        $ingredientes = Ingrediente::orderBy('nombre')->get();
        return view('platillos.create', compact('categorias', 'ingredientes'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'categoria_platillo_id' => 'required|integer|exists:categoria_platillos,id',
            'nombre' => 'required|string|max:100',
            'descripcion' => 'nullable|string',
            'precio' => 'nullable|numeric|min:0',
            'porciones_base' => 'nullable|integer|min:1',
            'ingrediente_ids' => 'sometimes|array',
            'ingrediente_ids.*' => 'integer|exists:ingredientes,id',
        ]);

        $platillo = Platillo::create($request->only([
            'categoria_platillo_id', 'nombre', 'descripcion', 'porciones_base'
        ]));

        if (isset($data['ingrediente_ids'])) {
            $platillo->ingredientes()->sync($data['ingrediente_ids']);
        }

        return redirect()->route('platillos.index')->with('success', 'Platillo creado correctamente.');
    }

    public function show(Platillo $platillo)
    {
        $platillo->load(['categoriaPlatillo', 'ingredientes']);
        return view('platillos.show', compact('platillo'));
    }

    public function edit(Platillo $platillo)
    {
        $platillo->load('ingredientes');
        $categorias = CategoriaPlatillo::orderBy('nombre')->get();
        $ingredientes = Ingrediente::orderBy('nombre')->get();
        return view('platillos.edit', compact('platillo', 'categorias', 'ingredientes'));
    }

    public function update(Request $request, Platillo $platillo)
    {
        $data = $request->validate([
            'categoria_platillo_id' => 'required|integer|exists:categoria_platillos,id',
            'nombre' => 'required|string|max:100',
            'descripcion' => 'nullable|string',
            'precio' => 'nullable|numeric|min:0',
            'porciones_base' => 'nullable|integer|min:1',
            'ingrediente_ids' => 'sometimes|array',
            'ingrediente_ids.*' => 'integer|exists:ingredientes,id',
        ]);

        $platillo->update($request->only([
            'categoria_platillo_id', 'nombre', 'descripcion', 'porciones_base'
        ]));

        if (array_key_exists('ingrediente_ids', $data)) {
            $platillo->ingredientes()->sync($data['ingrediente_ids'] ?? []);
        }

        return redirect()->route('platillos.index')->with('success', 'Platillo actualizado correctamente.');
    }

    public function destroy(Platillo $platillo)
    {
        $platillo->delete();
        return redirect()->route('platillos.index')->with('success', 'Platillo eliminado correctamente.');
    }
}
