<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use App\Services\CalculadoraInsumosService;

class ReporteController extends Controller
{
    protected $calculadora;

    public function __construct(CalculadoraInsumosService $calculadora)
    {
        $this->calculadora = $calculadora;
    }

    /**
     * Formatea una cantidad para mostrarla amigablemente (ej. 1 kg 250 g)
     */
    private function formatearCantidad($cantidad, $unidad)
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
     * Procesa y muestra el reporte de insumos para un evento específico.
     */
    public function insumosEvento($id)
    {
        // 1. Cargamos el evento con TODAS las relaciones necesarias para el cálculo y la vista.
        // Esto evita que el servicio tenga que hacer su propia consulta (N+1).
        $evento = Evento::with('salones')->findOrFail($id);
        
        // 2. Calculamos los insumos a través del servicio, pasándole el objeto ya cargado.
        $insumosCalculados = $this->calculadora->calcularParaEvento($evento);

        $reporteInsumos = [];
        foreach ($insumosCalculados as $nombre => $datos) {
            // Consultamos el stock real desde el modelo Ingrediente
            $ingredienteModel = \App\Models\Ingrediente::where('nombre', $nombre)->first();
            $stockActual = $ingredienteModel ? $ingredienteModel->stock : 0; 
            
            $cantidadRequerida = $datos['cantidad'];

            // 3. Lógica del semáforo de stock y cálculo de compras
            if ($stockActual >= $cantidadRequerida) {
                $estado = 'verde';
                $comprarRaw = 0;
            } elseif ($stockActual > 0 && $stockActual < $cantidadRequerida) {
                $estado = 'amarillo';
                $comprarRaw = $cantidadRequerida - $stockActual;
            } else {
                $estado = 'rojo';
                $comprarRaw = $cantidadRequerida;
            }

            // 4. Construimos el array para la vista
            $reporteInsumos[] = [
                'nombre'           => $nombre,
                'unidad'           => $datos['unidad'],
                'requerido'        => $cantidadRequerida,
                'requerido_format' => $this->formatearCantidad($cantidadRequerida, $datos['unidad']),
                'stock'            => $stockActual,
                'stock_format'     => $this->formatearCantidad($stockActual, $datos['unidad']),
                'comprar_raw'      => $comprarRaw,
                'comprar_format'   => $this->formatearCantidad($comprarRaw, $datos['unidad']),
                'estado'           => $estado
            ];
        }

        // 5. Retornamos la vista con el evento y el reporte procesado
        return view('reportes.insumos', compact('evento', 'reporteInsumos'));
    }
}