<?php

namespace App\Services;

use App\Models\Evento;

class CalculadoraInsumosService
{
    /**
     * Calcula y consolida la lista de ingredientes para un evento,
     * procesando los menús de todos los salones asignados.
     */
    public function calcularParaEvento(Evento $evento): array
    {
        // El evento ya viene con las relaciones cargadas desde el controlador.
        $listaInsumos = [];
        // $evento = Evento::with(['salones.platillos.ingredientes'])->findOrFail($eventoId); // Ya no es necesario

        foreach ($evento->salones as $salon) {
            foreach ($salon->platillos as $platillo) {
                
                $porcionesPlan = $platillo->pivot->porciones_plan;
                $porcionesBase = $platillo->porciones_base ?: 1; 
                
                foreach ($platillo->ingredientes as $ingrediente) {
                    $cantidadBase = $ingrediente->pivot->cantidad_por_base;
                    $unidad = $ingrediente->unidad;
                    $nombreIngrediente = $ingrediente->nombre;

                    // Regla de 3 para escalar ingredientes
                    $cantidadFinal = ($cantidadBase / $porcionesBase) * $porcionesPlan;

                    // Consolidación: Sumar ingredientes repetidos
                    if (!isset($listaInsumos[$nombreIngrediente])) {
                        $listaInsumos[$nombreIngrediente] = [
                            'cantidad' => 0,
                            'unidad'   => $unidad
                        ];
                    }
                    
                    $listaInsumos[$nombreIngrediente]['cantidad'] += $cantidadFinal;
                }
            }
        }

        // Redondear las cantidades a 3 decimales
        foreach ($listaInsumos as &$insumo) {
            $insumo['cantidad'] = round($insumo['cantidad'], 3);
        }

        return $listaInsumos;
    }
}