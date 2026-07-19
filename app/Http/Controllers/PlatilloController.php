<?php

namespace App\Http\Controllers;

use App\Models\Platillo;
use App\Models\CategoriaPlatillo;
use App\Models\Ingrediente;
use App\Models\ServicioGastronomico;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PlatilloController extends Controller
{
    public function index()
    {
        $platillos = Platillo::with(['categoriaPlatillo', 'ingredientes', 'serviciosGastronomicos'])
            ->orderBy('nombre')->get();

        // Como un platillo ahora puede estar en varios servicios,
        // lo agrupamos manualmente para que aparezca en cada sección a la que pertenece
        $platillosAgrupados = collect();

        foreach ($platillos as $platillo) {
            foreach ($platillo->serviciosGastronomicos as $servicio) {
                if (!$platillosAgrupados->has($servicio->id)) {
                    $platillosAgrupados->put($servicio->id, collect());
                }
                $platillosAgrupados->get($servicio->id)->push($platillo);
            }
        }

        return view('platillos.index', compact('platillos', 'platillosAgrupados'));
    }

    public function create()
    {
        $servicios = ServicioGastronomico::all();
        $categorias = CategoriaPlatillo::orderBy('orden')->orderBy('nombre')->get();
        $insumos = Ingrediente::orderBy('nombre')->get();

        return view('platillos.create', compact('servicios', 'categorias', 'insumos'));
    }

    // FUNCIÓN BLINDADA PARA EVITAR ERRORES DE BASE DE DATOS Y DUPLICADOS
    public function storeAjax(Request $request)
    {
        try {
            $request->validate([
                'nombre' => 'required|string',
                'unidad' => 'required|string',
            ]);

            $nombreLimpio = trim($request->nombre);

            // Buscamos si ya existe un insumo con ese nombre (sin importar mayúsculas/minúsculas)
            $insumo = Ingrediente::whereRaw('LOWER(TRIM(nombre)) = ?', [strtolower($nombreLimpio)])->first();

            if (!$insumo) {
                // No existe, lo creamos
                $insumo = Ingrediente::create([
                    'nombre' => $nombreLimpio,
                    'unidad' => $request->unidad,
                    'categoria' => 'General',
                ]);
            }

            return response()->json([
                'success' => true,
                'id' => $insumo->id,
                'nombre' => $insumo->nombre,
                'unidad' => $insumo->unidad
            ]);
        } catch (\Exception $e) {
            // Logueamos el error real para verlo en laravel.log
            Log::error('Error al guardar insumo vía AJAX: ' . $e->getMessage());

            // Devolvemos el error detallado para que sepas exactamente qué pasa
            return response()->json([
                'success' => false,
                'message' => 'Error al guardar el insumo: ' . $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'servicio_gastronomico_id' => 'required|array|min:1',
            'servicio_gastronomico_id.*' => 'integer|exists:servicios_gastronomicos,id',
            'categoria_platillo_id' => 'required|integer|exists:categoria_platillos,id',
            'nombre' => 'required|string|max:100',
            'descripcion' => 'nullable|string',
            'precio' => 'nullable|numeric|min:0',
            'ingredientes.id.*' => 'nullable|integer|exists:ingredientes,id',
            'ingredientes.cantidad.*' => 'nullable|numeric|min:0.01',
        ]);

        $platillo = Platillo::create($request->only([
            'categoria_platillo_id',
            'nombre',
            'descripcion',
            'precio'
        ]));

        // Enlaza el platillo a uno o varios tipos de servicio (sin duplicar el platillo)
        $platillo->serviciosGastronomicos()->sync($data['servicio_gastronomico_id']);

        if (isset($data['ingredientes']['id'])) {
            $syncData = [];
            foreach ($data['ingredientes']['id'] as $index => $ingredienteId) {
                if (!empty($ingredienteId)) {
                    $cantidad = $data['ingredientes']['cantidad'][$index] ?? 1;
                    $syncData[$ingredienteId] = ['cantidad_por_base' => $cantidad];
                }
            }
            $platillo->ingredientes()->sync($syncData);
        }

        return redirect()->route('platillos.index')->with('success', 'Platillo creado correctamente.');
    }

    public function show(Platillo $platillo)
    {
        $platillo->load(['categoriaPlatillo', 'ingredientes', 'serviciosGastronomicos']);
        return view('platillos.show', compact('platillo'));
    }

    public function edit(Platillo $platillo)
    {
        $platillo->load(['ingredientes', 'serviciosGastronomicos']);
        $categorias = CategoriaPlatillo::orderBy('nombre')->get();
        $ingredientes = Ingrediente::orderBy('nombre')->get();
        $servicios = ServicioGastronomico::orderBy('nombre')->get();

        return view('platillos.edit', compact('platillo', 'categorias', 'ingredientes', 'servicios'));
    }

    public function update(Request $request, Platillo $platillo)
    {
        $data = $request->validate([
            'servicio_gastronomico_id' => 'required|array|min:1',
            'servicio_gastronomico_id.*' => 'integer|exists:servicios_gastronomicos,id',
            'categoria_platillo_id' => 'required|integer|exists:categoria_platillos,id',
            'nombre' => 'required|string|max:100',
            'descripcion' => 'nullable|string',
            'precio' => 'nullable|numeric|min:0',
            'ingredientes.id.*' => 'nullable|integer|exists:ingredientes,id',
            'ingredientes.cantidad.*' => 'nullable|numeric|min:0.01',
        ]);

        $platillo->update($request->only([
            'categoria_platillo_id',
            'nombre',
            'descripcion',
            'precio'
        ]));

        $platillo->serviciosGastronomicos()->sync($data['servicio_gastronomico_id']);

        if (array_key_exists('ingredientes', $data) && isset($data['ingredientes']['id'])) {
            $syncData = [];
            foreach ($data['ingredientes']['id'] as $index => $ingredienteId) {
                if (!empty($ingredienteId)) {
                    $cantidad = $data['ingredientes']['cantidad'][$index] ?? 1;
                    $syncData[$ingredienteId] = ['cantidad_por_base' => $cantidad];
                }
            }
            $platillo->ingredientes()->sync($syncData);
        } else {
            $platillo->ingredientes()->sync([]);
        }

        return redirect()->route('platillos.index')->with('success', 'Platillo actualizado correctamente.');
    }

    public function destroy(Platillo $platillo)
    {
        $platillo->delete();
        return redirect()->route('platillos.index')->with('success', 'Platillo eliminado correctamente.');
    }
}
