<?php

namespace App\Http\Controllers;

use App\Models\CategoriaPlatillo;
use Illuminate\Http\Request;

class CategoriaPlatilloController extends Controller
{
    public function index()
    {
        $categorias = CategoriaPlatillo::withCount('platillos')
            ->orderBy('orden')
            ->orderBy('nombre')
            ->get()
            ->groupBy(function ($categoria) {
                return $categoria->grupo ?? 'Sin Grupo';
            });

        return view('categoria-platillos.index', compact('categorias'));
    }

    public function create()
    {
        $grupos = CategoriaPlatillo::whereNotNull('grupo')
            ->distinct()
            ->orderBy('grupo')
            ->pluck('grupo');

        return view('categoria-platillos.create', compact('grupos'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:60',
            'grupo' => 'nullable|string|max:60',
            'orden' => 'required|integer|min:1',
        ], [
            'nombre.required' => 'El nombre de la categoría es obligatorio.',
            'orden.required' => 'El orden es obligatorio.',
            'orden.integer' => 'El orden debe ser un número.',
        ]);

        CategoriaPlatillo::create($data);

        return redirect()->route('categorias.index')
            ->with('success', 'Categoría creada correctamente.');
    }

    // OJO: el nombre del parámetro debe ser $categoria (coincide con el
    // {categoria} que genera Route::resource('categorias', ...)).
    public function show(CategoriaPlatillo $categoria)
    {
        $categoria->load('platillos');

        return view('categoria-platillos.show', compact('categoria'));
    }

    public function edit(CategoriaPlatillo $categoria)
    {
        $grupos = CategoriaPlatillo::whereNotNull('grupo')
            ->distinct()
            ->orderBy('grupo')
            ->pluck('grupo');

        return view('categoria-platillos.edit', compact('categoria', 'grupos'));
    }

    public function update(Request $request, CategoriaPlatillo $categoria)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:60',
            'grupo' => 'nullable|string|max:60',
            'orden' => 'required|integer|min:1',
        ], [
            'nombre.required' => 'El nombre de la categoría es obligatorio.',
            'orden.required' => 'El orden es obligatorio.',
            'orden.integer' => 'El orden debe ser un número.',
        ]);

        $categoria->update($data);

        return redirect()->route('categorias.index')
            ->with('success', 'Categoría actualizada correctamente.');
    }

    public function destroy(CategoriaPlatillo $categoria)
    {
        if ($categoria->platillos()->exists()) {
            return redirect()->route('categorias.index')
                ->with('error', 'No se puede eliminar "' . $categoria->nombre . '" porque tiene platillos asociados.');
        }

        $categoria->delete();

        return redirect()->route('categorias.index')
            ->with('success', 'Categoría eliminada correctamente.');
    }
}