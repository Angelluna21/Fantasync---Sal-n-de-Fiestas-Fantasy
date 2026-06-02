<?php

namespace App\Http\Controllers;

use App\Models\Ingrediente;
use App\Models\Platillo;
use Illuminate\Http\Request;

class IngredienteController extends Controller
{
    public function index()
    {
        $ingredientes = Ingrediente::with('platillos')->orderBy('nombre')->get();
        return view('ingredientes.index', compact('ingredientes'));
    }

    public function create()
    {
        $platillos = Platillo::orderBy('nombre')->get();
        return view('ingredientes.create', compact('platillos'));
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

        return redirect()->route('ingredientes.index')->with('success', 'Ingrediente creado con éxito.');
    }

    public function show(Ingrediente $ingrediente)
    {
        $ingrediente->load('platillos.categoriaPlatillo');
        return view('ingredientes.show', compact('ingrediente'));
    }

    public function edit(Ingrediente $ingrediente)
    {
        $ingrediente->load('platillos');
        $platillos = Platillo::orderBy('nombre')->get();
        return view('ingredientes.edit', compact('ingrediente', 'platillos'));
    }

    public function update(Request $request, Ingrediente $ingrediente)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:120',
            'unidad' => 'required|in:kg,gr,l,ml,pz',
            'platillo_ids' => 'sometimes|array',
            'platillo_ids.*' => 'integer|exists:platillos,id',
        ]);

        $ingrediente->update($request->only(['nombre', 'unidad']));

        if (array_key_exists('platillo_ids', $data)) {
            $ingrediente->platillos()->sync($data['platillo_ids'] ?? []);
        }

        return redirect()->route('ingredientes.index')->with('success', 'Ingrediente actualizado con éxito.');
    }

    public function destroy(Ingrediente $ingrediente)
    {
        $ingrediente->delete();
        return redirect()->route('ingredientes.index')->with('success', 'Ingrediente eliminado con éxito.');
    }
}
