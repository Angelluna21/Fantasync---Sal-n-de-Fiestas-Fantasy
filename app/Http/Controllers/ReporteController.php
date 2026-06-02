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
     * Procesa y muestra el reporte de insumos para un evento específico.
     */
    public function insumosEvento($id)
    {
        // 1. Cargamos el evento con TODAS las relaciones necesarias para el cálculo y la vista.
        // Esto evita que el servicio tenga que hacer su propia consulta (N+1).
        $evento = Evento::with(['salones.platillos.ingredientes'])->findOrFail($id);
        
        // 2. Calculamos los insumos a través del servicio, pasándole el objeto ya cargado.
        $insumosCalculados = $this->calculadora->calcularParaEvento($evento);

        $reporteInsumos = [];
        foreach ($insumosCalculados as $nombre => $datos) {
            // Nota: Aquí se mantiene la simulación, en el futuro conectarás con tu modelo Inventario
            $stockActualSimulado = rand(0, 100); 
            $cantidadRequerida = $datos['cantidad'];

            // 3. Lógica del semáforo de stock
            if ($stockActualSimulado >= $cantidadRequerida) {
                $estado = 'verde';
            } elseif ($stockActualSimulado > 0 && $stockActualSimulado < $cantidadRequerida) {
                $estado = 'amarillo';
            } else {
                $estado = 'rojo';
            }

            // 4. Construimos el array para la vista
            $reporteInsumos[] = [
                'nombre'    => $nombre,
                'unidad'    => $datos['unidad'],
                'requerido' => $cantidadRequerida,
                'stock'     => $stockActualSimulado,
                'estado'    => $estado
            ];
        }

        // 5. Retornamos la vista con el evento y el reporte procesado
        return view('reportes.insumos', compact('evento', 'reporteInsumos'));
    }
}