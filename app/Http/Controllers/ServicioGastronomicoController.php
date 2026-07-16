<?php

namespace App\Http\Controllers;

use App\Models\ServicioGastronomico;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class ServicioGastronomicoController extends Controller
{
    public function index()
    {
        $servicios = ServicioGastronomico::orderBy('nombre')->get();

        return view('servicios-gastronomicos.index', compact('servicios'));
    }

    public function create()
    {
        return view('servicios-gastronomicos.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:255|unique:servicios_gastronomicos,nombre',
        ], [
            'nombre.required' => 'El nombre del servicio es obligatorio.',
            'nombre.unique' => 'Ya existe un servicio con ese nombre.',
        ]);

        ServicioGastronomico::create($data);

        return redirect()->route('servicios-gastronomicos.index')
            ->with('success', 'Servicio creado correctamente.');
    }

    public function show(ServicioGastronomico $servicios_gastronomico)
    {
        return view('servicios-gastronomicos.show', ['servicio' => $servicios_gastronomico]);
    }

    public function edit(ServicioGastronomico $servicios_gastronomico)
    {
        return view('servicios-gastronomicos.edit', ['servicio' => $servicios_gastronomico]);
    }

    public function update(Request $request, ServicioGastronomico $servicios_gastronomico)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:255|unique:servicios_gastronomicos,nombre,' . $servicios_gastronomico->id,
        ], [
            'nombre.required' => 'El nombre del servicio es obligatorio.',
            'nombre.unique' => 'Ya existe un servicio con ese nombre.',
        ]);

        $servicios_gastronomico->update($data);

        return redirect()->route('servicios-gastronomicos.index')
            ->with('success', 'Servicio actualizado correctamente.');
    }

    public function destroy(ServicioGastronomico $servicios_gastronomico)
    {
        try {
            $servicios_gastronomico->delete();
        } catch (QueryException $e) {
            // Si el servicio ya tiene platillos relacionados (tabla pivote),
            // la base de datos rechaza el borrado por la llave foránea.
            return redirect()->route('servicios-gastronomicos.index')
                ->with('error', 'No se puede eliminar "' . $servicios_gastronomico->nombre . '" porque tiene platillos asociados. Quítalo primero de esos platillos.');
        }

        return redirect()->route('servicios-gastronomicos.index')
            ->with('success', 'Servicio eliminado correctamente.');
    }
}
