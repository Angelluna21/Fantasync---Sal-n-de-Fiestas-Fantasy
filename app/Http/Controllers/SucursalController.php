<?php

namespace App\Http\Controllers;

use App\Models\Sucursal;
use Illuminate\Http\Request;

class SucursalController extends Controller
{
    public function index()
    {
        return response()->json(Sucursal::with('salones')->get());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:100',
            'direccion' => 'nullable|string',
        ]);

        $sucursal = Sucursal::create($data);

        return response()->json($sucursal, 201);
    }

    public function show(Sucursal $sucursal)
    {
        return response()->json($sucursal->load('salones'));
    }

    public function update(Request $request, Sucursal $sucursal)
    {
        $data = $request->validate([
            'nombre' => 'sometimes|string|max:100',
            'direccion' => 'nullable|string',
        ]);

        $sucursal->update($data);

        return response()->json($sucursal);
    }

    public function destroy(Sucursal $sucursal)
    {
        $sucursal->delete();

        return response()->noContent();
    }
}
