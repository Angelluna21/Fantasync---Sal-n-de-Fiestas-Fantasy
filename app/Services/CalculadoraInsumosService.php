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
        $listaInsumos = [];
        $eventoSalones = \App\Models\EventoSalon::where('evento_id', $evento->id)
            ->with('platillos.ingredientes')
            ->get();

        foreach ($eventoSalones as $eventoSalon) {
            foreach ($eventoSalon->platillos as $platillo) {
                
                $porcionesPlan = $platillo->pivot->porciones_plan;
                // La base fija es siempre 100 porciones
                
                foreach ($platillo->ingredientes as $ingrediente) {
                    $cantidadBase = $ingrediente->pivot->cantidad_por_base;
                    $unidad = $ingrediente->unidad;
                    $nombreIngrediente = $ingrediente->nombre;

                    // Regla de 3 para escalar ingredientes con base fija de 100
                    $cantidadFinal = ($cantidadBase / 100) * $porcionesPlan;

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

    /**
     * Calcula la lista de ingredientes para una comanda rápida (banquete independiente)
     * donde no existe un EventoSalon, sino una lista de platillos y porciones totales.
     * 
     * @param array $platillosIds Array con los IDs de los platillos seleccionados
     * @param int $totalPorciones La cantidad total de porciones a calcular por platillo
     */
    public function calcularParaComandaRapida(array $platillosIds, int $totalPorciones): array
    {
        $listaInsumos = [];
        $platillos = \App\Models\Platillo::with('ingredientes')->whereIn('id', $platillosIds)->get();

        foreach ($platillos as $platillo) {
            foreach ($platillo->ingredientes as $ingrediente) {
                $cantidadBase = $ingrediente->pivot->cantidad_por_base;
                $unidad = $ingrediente->unidad;
                $nombreIngrediente = $ingrediente->nombre;

                // Regla de 3 para escalar ingredientes con base fija de 100
                // Asumimos que $totalPorciones se asigna completo a cada platillo seleccionado
                // (Si fuera Taquiza se tendría que dividir entre guisados, pero para banquete rápido 
                // asumimos la porción completa por invitado, o que el usuario ingresa la porción ajustada).
                $cantidadFinal = ($cantidadBase / 100) * $totalPorciones;

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

        // Redondear las cantidades a 3 decimales
        foreach ($listaInsumos as &$insumo) {
            $insumo['cantidad'] = round($insumo['cantidad'], 3);
        }

        return $listaInsumos;
    }

    /**
     * Formatea una cantidad para mostrarla amigablemente (ej. 1 kg 250 g)
     */
    public function formatearCantidad($cantidad, $unidad)
    {
        if (strtolower($unidad) === 'kg' && $cantidad > 0) {
            $kilos = floor($cantidad);
            $gramos = round(($cantidad - $kilos) * 1000);
            
            if ($kilos == 0) {
                return "{$gramos} g";
            } elseif ($gramos == 0) {
                return "{$kilos} kg";
            } else {
                return "{$kilos} kg {$gramos} g";
            }
        }
        
        if ($cantidad == 0) {
            return "0 {$unidad}";
        }
        
        return $cantidad . ' ' . $unidad;
    }

    /**
     * Calcula la cantidad comercial a comprar aplicando redondeo lógico según la unidad.
     */
    public function calcularCompraComercial($cantidadRequeridaSegura, $unidad)
    {
        $unidad = strtolower(trim($unidad));
        
        // Unidades que no se pueden fraccionar en el supermercado
        $unidadesDiscretas = ['pz', 'pieza', 'piezas', 'manojo', 'manojos', 'lata', 'latas', 'paquete', 'paquetes', 'frasco', 'botella'];

        if (in_array($unidad, $unidadesDiscretas)) {
            // Siempre redondear hacia arriba al entero más próximo
            return ceil($cantidadRequeridaSegura);
        } elseif ($unidad === 'kg' || $unidad === 'l') {
            // Para kilos y litros, redondear al 0.5 más cercano hacia arriba para facilitar compra (ej. 1.2 -> 1.5)
            return ceil($cantidadRequeridaSegura * 2) / 2;
        } elseif ($unidad === 'gr' || $unidad === 'g' || $unidad === 'ml') {
            // Para gramos, redondear a los 50g más cercanos hacia arriba
            return ceil($cantidadRequeridaSegura / 50) * 50;
        }

        // Por defecto, redondear a 2 decimales
        return round($cantidadRequeridaSegura, 2);
    }
}