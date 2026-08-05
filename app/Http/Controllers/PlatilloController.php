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

        $platillosAgrupados = collect();

        $catMenuInfantilId = CategoriaPlatillo::whereRaw('LOWER(TRIM(nombre)) IN (?, ?)', ['menú infantil', 'menu infantil'])->value('id');
        $catBuffetInfantilId = CategoriaPlatillo::whereRaw('LOWER(TRIM(nombre)) = ?', ['buffet infantil'])->value('id');

        foreach ($platillos as $platillo) {
            $catId = $platillo->categoria_platillo_id ?? 0; // 0 para sin categoría
            if (!$platillosAgrupados->has($catId)) {
                $platillosAgrupados->put($catId, collect());
            }
            $platillosAgrupados->get($catId)->push($platillo);

            // Clonar para que aparezca en la sección de Menú Infantil y Buffet Infantil si tienen el servicio asignado
            $serviciosNombres = $platillo->serviciosGastronomicos->pluck('nombre')->map(fn($n) => strtolower(trim($n)))->toArray();

            if ($catMenuInfantilId && $catId != $catMenuInfantilId && (in_array('menú infantil', $serviciosNombres) || in_array('menu infantil', $serviciosNombres))) {
                if (!$platillosAgrupados->has($catMenuInfantilId)) $platillosAgrupados->put($catMenuInfantilId, collect());
                // Evitar duplicados exactos si por alguna razón entra doble
                if (!$platillosAgrupados->get($catMenuInfantilId)->contains('id', $platillo->id)) {
                    $platillosAgrupados->get($catMenuInfantilId)->push($platillo);
                }
            }

            if ($catBuffetInfantilId && $catId != $catBuffetInfantilId && in_array('buffet infantil', $serviciosNombres)) {
                if (!$platillosAgrupados->has($catBuffetInfantilId)) $platillosAgrupados->put($catBuffetInfantilId, collect());
                if (!$platillosAgrupados->get($catBuffetInfantilId)->contains('id', $platillo->id)) {
                    $platillosAgrupados->get($catBuffetInfantilId)->push($platillo);
                }
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

    public function storeAjax(Request $request)
    {
        try {
            $request->validate([
                'nombre' => 'required|string',
                'unidad' => 'required|string',
                'categoria' => 'required|string',
            ]);

            $nombreLimpio = trim($request->nombre);

            $insumo = Ingrediente::whereRaw('LOWER(TRIM(nombre)) = ?', [strtolower($nombreLimpio)])->first();

            if (!$insumo) {
                $insumo = Ingrediente::create([
                    'nombre' => $nombreLimpio,
                    'unidad' => $request->unidad,
                    'categoria' => $request->categoria,
                ]);
            }

            return response()->json([
                'success' => true,
                'id' => $insumo->id,
                'nombre' => $insumo->nombre,
                'unidad' => $insumo->unidad
            ]);
        } catch (\Exception $e) {
            Log::error('Error al guardar insumo vía AJAX: ' . $e->getMessage());

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
            'nombre' => 'required|string|max:100|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s\/]+$/|unique:platillos,nombre',
            'descripcion' => 'nullable|string',
            'ingredientes.id.*' => 'nullable|integer|exists:ingredientes,id',
            'ingredientes.cantidad.*' => 'nullable|numeric|min:0.01',
            'ingredientes.es_fijo.*' => 'nullable|boolean',
        ], [
            'nombre.regex' => 'El nombre del platillo solo debe contener letras, espacios y la diagonal (/).',
            'nombre.unique' => 'Ya existe un platillo registrado con este nombre.'
        ]);

        // Guardamos sin requerir precio (si no viene, se guarda como 0 o null)
        $platillo = Platillo::create([
            'categoria_platillo_id' => $data['categoria_platillo_id'],
            'nombre' => $data['nombre'],
            'descripcion' => $data['descripcion'] ?? null,
            
        ]);

        $platillo->serviciosGastronomicos()->sync($data['servicio_gastronomico_id']);

        if (isset($data['ingredientes']['id'])) {
            $syncData = [];
            foreach ($data['ingredientes']['id'] as $index => $ingredienteId) {
                if (!empty($ingredienteId)) {
                    $cantidad = $data['ingredientes']['cantidad'][$index] ?? 1;
                    $esFijo = isset($data['ingredientes']['es_fijo'][$index]) && $data['ingredientes']['es_fijo'][$index] ? 1 : 0;
                    $syncData[$ingredienteId] = [
                        'cantidad_por_base' => $cantidad,
                        'es_fijo' => $esFijo
                    ];
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
            'nombre' => 'required|string|max:100|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s\/]+$/|unique:platillos,nombre,' . $platillo->id,
            'descripcion' => 'nullable|string',
            'ingredientes.id.*' => 'nullable|integer|exists:ingredientes,id',
            'ingredientes.cantidad.*' => 'nullable|numeric|min:0.01',
            'ingredientes.es_fijo.*' => 'nullable|boolean',
        ], [
            'nombre.regex' => 'El nombre del platillo solo debe contener letras, espacios y la diagonal (/).',
            'nombre.unique' => 'Ya existe un platillo registrado con este nombre.'
        ]);

        $platillo->update([
            'categoria_platillo_id' => $data['categoria_platillo_id'],
            'nombre' => $data['nombre'],
            'descripcion' => $data['descripcion'] ?? null,
        ]);

        $platillo->serviciosGastronomicos()->sync($data['servicio_gastronomico_id']);

        if (array_key_exists('ingredientes', $data) && isset($data['ingredientes']['id'])) {
            $syncData = [];
            foreach ($data['ingredientes']['id'] as $index => $ingredienteId) {
                if (!empty($ingredienteId)) {
                    $cantidad = $data['ingredientes']['cantidad'][$index] ?? 1;
                    $esFijo = isset($data['ingredientes']['es_fijo'][$index]) && $data['ingredientes']['es_fijo'][$index] ? 1 : 0;
                    $syncData[$ingredienteId] = [
                        'cantidad_por_base' => $cantidad,
                        'es_fijo' => $esFijo
                    ];
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