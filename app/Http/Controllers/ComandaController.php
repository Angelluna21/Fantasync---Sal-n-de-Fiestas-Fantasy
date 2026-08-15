<?php

namespace App\Http\Controllers;

use App\Models\Contrato;
use App\Models\Evento;
use Illuminate\Http\Request;

class ComandaController extends Controller
{
    /**
     * Muestra la comanda completa (orden de cocina) de un contrato específico.
     */
    public function showByContrato(Contrato $contrato)
    {
        $contrato->load('evento.cliente', 'evento.salones.sucursal');
        $salonesConComanda = $contrato->comandas;
        $salonesConComanda->load(['platillos.categoriaPlatillo', 'platillos.ingredientes']);
        
        $platillosAgrupados = collect();
        
        $calculadora = app(\App\Services\CalculadoraInsumosService::class);
        
        foreach ($salonesConComanda as $eventoSalon) {
            foreach ($eventoSalon->platillos as $platillo) {
                $id = $platillo->id;
                $porcionesPlan = $platillo->pivot->porciones_plan;
                
                if (!$platillosAgrupados->has($id)) {
                    $platillosAgrupados->put($id, [
                        'nombre' => $platillo->nombre,
                        'categoria' => $platillo->categoriaPlatillo ? $platillo->categoriaPlatillo->nombre : 'Sin Categoría',
                        'porciones_totales' => 0,
                        'salones' => [],
                        'ingredientes' => []
                    ]);
                }

                $item = $platillosAgrupados->get($id);
                $item['porciones_totales'] += $porcionesPlan;
                
                // Guardamos para qué salón es y cuántas porciones
                $item['salones'][] = [
                    'nombre' => $eventoSalon->salon->nombre ?? 'Sin nombre',
                    'porciones' => $porcionesPlan,
                    'notas' => $platillo->pivot->notas
                ];

                // Calcular ingredientes para estas porciones
                foreach ($platillo->ingredientes as $ingrediente) {
                    $nombreIng = $ingrediente->nombre;
                    $cantidadBase = $ingrediente->pivot->cantidad_por_base;
                    $esFijo = $ingrediente->pivot->es_fijo ?? false;
                    $unidad = $ingrediente->unidad;
                    
                    if ($esFijo) {
                        $cantidadFinal = $cantidadBase;
                    } else {
                        $cantidadFinal = ($cantidadBase / 100) * $porcionesPlan;
                    }

                    if (!isset($item['ingredientes'][$nombreIng])) {
                        $item['ingredientes'][$nombreIng] = [
                            'cantidad' => 0,
                            'unidad' => $unidad,
                        ];
                    }
                    $item['ingredientes'][$nombreIng]['cantidad'] += $cantidadFinal;
                }

                $platillosAgrupados->put($id, $item);
            }
        }

        // Formatear cantidades amigablemente
        $platillosAgrupados->transform(function ($item) use ($calculadora) {
            foreach ($item['ingredientes'] as $nombreIng => &$datos) {
                // Redondear a 3 decimales internamente
                $datos['cantidad'] = round($datos['cantidad'], 3);
                $datos['format'] = $calculadora->formatearCantidad($datos['cantidad'], $datos['unidad']);
            }
            return $item;
        });

        // Inyectar Servicio de Café si está activo en los extras
        if (!empty($contrato->evento->extras['servicio_cafe'])) {
            $platillosAgrupados->put('cafe_especial', [
                'nombre' => 'Mesa de Café (Servicio de Café)',
                'categoria' => 'Bebidas',
                'porciones_totales' => 'Por demanda',
                'salones' => [['nombre' => 'General', 'porciones' => '-', 'notas' => 'Activado en Detalles del Evento']],
                'ingredientes' => [
                    'Café (Grano/Soluble)' => ['cantidad' => 1, 'unidad' => 'lote', 'format' => '1 lote'],
                    'Azúcar' => ['cantidad' => 1, 'unidad' => 'lote', 'format' => '1 lote'],
                    'Canela' => ['cantidad' => 1, 'unidad' => 'lote', 'format' => '1 lote'],
                ]
            ]);
        }

        // Agrupamos la colección final por categoría para que la cocina tenga orden
        $comandaGlobal = $platillosAgrupados->groupBy('categoria');

        // Orden profesional de cocina (Tiempos, Taquiza/Buffet, Infantil, Bebidas, Dulces/Postres)
        $ordenDeseado = [
            'Entradas',
            'Cremas y Sopas',
            'Platos Fuertes',
            'Espejos',
            'Guarniciones (Formales)',
            'Guisados',
            'Parrillada (Carnes)',
            'Guarniciones',
            'Salsas',
            'Aderezos',
            'Menú Infantil',
            'Buffet Infantil',
            'Bebidas',
            'Dulces',
            'Postres',
        ];

        $comandaGlobal = $comandaGlobal->sortBy(function ($platillos, $categoria) use ($ordenDeseado) {
            $pos = array_search($categoria, $ordenDeseado);
            return $pos === false ? 999 : $pos;
        });

        return view('reportes.comanda', compact('contrato', 'comandaGlobal', 'salonesConComanda'));
    }
}
