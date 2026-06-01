<?php

namespace App\Http\Controllers;

use App\Models\Sucursal;
use Illuminate\Http\Request;

class SucursalController extends Controller
{
    public function index()
    {
        $sucursales = Sucursal::with('salones')->get();
        return view('sucursales.index', compact('sucursales'));
    }

    public function create()
    {
        return view('sucursales.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:100',
            'direccion' => 'nullable|string',
        ]);

        $sucursal = Sucursal::create($data);

        return redirect()->route('sucursales.show', $sucursal)->with('success', 'Sucursal creada correctamente');
    }

    public function show(Sucursal $sucursal)
    {
        $sucursal->load('salones');
        return view('sucursales.show', compact('sucursal'));
    }

    public function edit(Sucursal $sucursal)
    {
        return view('sucursales.edit', compact('sucursal'));
    }

    public function update(Request $request, Sucursal $sucursal)
    {
        $data = $request->validate([
            'nombre' => 'sometimes|string|max:100',
            'direccion' => 'nullable|string',
        ]);

        $sucursal->update($data);

        return redirect()->route('sucursales.show', $sucursal)->with('success', 'Sucursal actualizada correctamente');
    }

    public function destroy(Sucursal $sucursal)
    {
        $sucursal->delete();

        return redirect()->route('sucursales.index')->with('success', 'Sucursal eliminada correctamente');
    }
}
