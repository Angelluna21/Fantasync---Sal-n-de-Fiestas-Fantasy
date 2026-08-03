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
                    $esFijo = $ingrediente->pivot->es_fijo ?? false;
                    $unidad = $ingrediente->unidad;
                    $nombreIngrediente = $ingrediente->nombre;

                    if ($esFijo) {
                        // Si es fijo, la cantidad es exactamente la base, sin importar los invitados
                        $cantidadFinal = $cantidadBase;
                    } else {
                        // Regla de 3 para escalar ingredientes proporcionales (base 100)
                        $cantidadFinal = ($cantidadBase / 100) * $porcionesPlan;
                    }

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

        // REGLAS DE DESCORCHE Y CERVEZA
        $tieneDescorche = strpos($evento->notas, 'Descorche: Sí') !== false;
        $tieneCerveza = strpos($evento->notas, 'Descorche Cerveza: Sí') !== false;

        if ($tieneDescorche || $tieneCerveza) {
            $totalAdultos = $eventoSalones->sum('adultos');
            $escalaSien = max(ceil($totalAdultos / 100), 1); // Escala por cada 100 adultos (mínimo 1)

            if ($tieneDescorche) {
                // 4 bolsas de hielo por cada 100 adultos
                $bolsasHielo = $escalaSien * 4;
                if (!isset($listaInsumos['Hielo en Cubos'])) {
                    $listaInsumos['Hielo en Cubos'] = ['cantidad' => 0, 'unidad' => 'bolsa'];
                }
                $listaInsumos['Hielo en Cubos']['cantidad'] += $bolsasHielo;
            }

            if ($tieneCerveza) {
                // $100 de molido por cada 100 adultos -> representamos como 1 Bulto/Carga
                $bultosMolido = $escalaSien * 1;
                if (!isset($listaInsumos['Hielo Molido ($100)'] )) {
                    $listaInsumos['Hielo Molido ($100)'] = ['cantidad' => 0, 'unidad' => 'bulto'];
                }
                $listaInsumos['Hielo Molido ($100)']['cantidad'] += $bultosMolido;

                // 3 kilos de limones por cada 100 adultos
                $kilosLimon = $escalaSien * 3;
                if (!isset($listaInsumos['Limón'])) {
                    $listaInsumos['Limón'] = ['cantidad' => 0, 'unidad' => 'kg'];
                }
                $listaInsumos['Limón']['cantidad'] += $kilosLimon;
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
                $esFijo = $ingrediente->pivot->es_fijo ?? false;
                $unidad = $ingrediente->unidad;
                $nombreIngrediente = $ingrediente->nombre;

                if ($esFijo) {
                    // Si es fijo, no multiplicamos por las porciones totales
                    $cantidadFinal = $cantidadBase;
                } else {
                    // Regla de 3 para escalar ingredientes proporcionales (base 100)
                    $cantidadFinal = ($cantidadBase / 100) * $totalPorciones;
                }

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
            $decimal = $cantidad - $kilos;
            
            $fraccionStr = '';
            if (abs($decimal - 0.25) < 0.001) $fraccionStr = '1/4';
            elseif (abs($decimal - 0.5) < 0.001) $fraccionStr = '1/2';
            elseif (abs($decimal - 0.75) < 0.001) $fraccionStr = '3/4';
            
            if ($fraccionStr !== '') {
                if ($kilos == 0) return "{$fraccionStr} kg";
                return "{$kilos} {$fraccionStr} kg";
            }
            
            $gramos = round($decimal * 1000);
            
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
        $unidadesDiscretas = ['pz', 'pieza', 'piezas', 'manojo', 'manojos', 'lata', 'latas', 'paquete', 'paquetes', 'frasco', 'botella', 'cabeza', 'cabezas'];

        if (in_array($unidad, $unidadesDiscretas)) {
            // Siempre redondear hacia arriba al entero más próximo
            return ceil($cantidadRequeridaSegura);
        } elseif ($unidad === 'kg' || $unidad === 'l') {
            // Para kilos y litros, redondear en cuartos (0.25, 0.50, 0.75, 1.00)
            return ceil($cantidadRequeridaSegura * 4) / 4;
        } elseif ($unidad === 'gr' || $unidad === 'g' || $unidad === 'ml') {
            // Para gramos, redondear a múltiplos de 250g hacia arriba
            return ceil($cantidadRequeridaSegura / 250) * 250;
        }

        // Por defecto, redondear a 2 decimales
        return round($cantidadRequeridaSegura, 2);
    }
}