<?php

namespace App\Http\Controllers;

use App\Models\ServicioGastronomico;
use Illuminate\Http\Request;

class ServicioGastronomicoController extends Controller
{
    public function index()
    {
        return response()->json(ServicioGastronomico::all());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:80',
        ]);

        $servicio = ServicioGastronomico::create($data);

        return response()->json($servicio, 201);
    }

    public function show(ServicioGastronomico $servicioGastronomico)
    {
        return response()->json($servicioGastronomico);
    }

    public function update(Request $request, ServicioGastronomico $servicioGastronomico)
    {
        $data = $request->validate([
            'nombre' => 'sometimes|string|max:80',
        ]);

        $servicioGastronomico->update($data);

        return response()->json($servicioGastronomico);
    }

    public function destroy(ServicioGastronomico $servicioGastronomico)
    {
        $servicioGastronomico->delete();

        return response()->noContent();
    }
}
