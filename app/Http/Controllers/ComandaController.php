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
        // 1. Cargamos el contrato con su evento y cliente
        $contrato->load('evento.cliente');

        // 2. Extraemos las comandas del contrato usando el mutator creado
        $salonesConComanda = $contrato->comandas;

        // 3. (Opcional) Podemos agrupar todos los platillos globalmente si queremos
        //    una lista unificada para la cocina.
        $platillosAgrupados = collect();
        
        foreach ($salonesConComanda as $eventoSalon) {
            foreach ($eventoSalon->platillos as $platillo) {
                $id = $platillo->id;
                
                if (!$platillosAgrupados->has($id)) {
                    $platillosAgrupados->put($id, [
                        'nombre' => $platillo->nombre,
                        'categoria' => $platillo->categoriaPlatillo ? $platillo->categoriaPlatillo->nombre : 'Sin Categoría',
                        'porciones_totales' => 0,
                        'salones' => [],
                    ]);
                }

                $item = $platillosAgrupados->get($id);
                $item['porciones_totales'] += $platillo->pivot->porciones_plan;
                
                // Guardamos para qué salón es y cuántas porciones
                $item['salones'][] = [
                    'nombre' => $eventoSalon->salon->nombre ?? 'Sin nombre',
                    'porciones' => $platillo->pivot->porciones_plan,
                    'notas' => $platillo->pivot->notas
                ];

                $platillosAgrupados->put($id, $item);
            }
        }

        // Agrupamos la colección final por categoría para que la cocina tenga orden
        $comandaGlobal = $platillosAgrupados->groupBy('categoria');

        return view('reportes.comanda', compact('contrato', 'comandaGlobal', 'salonesConComanda'));
    }
}
