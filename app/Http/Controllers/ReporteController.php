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
        $evento = Evento::with(['salones', 'eventoSalones.platillos', 'eventoSalones.salon'])->findOrFail($id);
        
        // 2. Calculamos los insumos a través del servicio, pasándole el objeto ya cargado.
        $insumosCalculados = $this->calculadora->calcularParaEvento($evento);

        $reporteInsumos = [];
        $margenSeguridad = 1.10; // 10% extra por mermas y seguridad

        foreach ($insumosCalculados as $nombre => $datos) {
            // Consultamos el stock real desde el modelo Ingrediente
            $ingredienteModel = \App\Models\Ingrediente::where('nombre', $nombre)->first();
            $stockActual = $ingredienteModel ? $ingredienteModel->stock : 0; 
            $categoria = $ingredienteModel && $ingredienteModel->categoria ? $ingredienteModel->categoria : 'Otros';
            
            $cantidadExacta = $datos['cantidad'];
            
            // Aplicar margen del 10%
            $cantidadSegura = $cantidadExacta * $margenSeguridad;

            // Se compran insumos nuevos por evento, ignoramos stock en refrigerador
            $comprarPuro = $cantidadSegura;

            // Aplicar redondeo comercial sobre lo que se debe comprar
            $comprarComercial = $comprarPuro > 0 ? $this->calculadora->calcularCompraComercial($comprarPuro, $datos['unidad']) : 0;

            // 4. Construimos el array para la vista
            $reporteInsumos[] = [
                'nombre'           => $nombre,
                'unidad'           => $datos['unidad'],
                'categoria'        => $categoria,
                'requerido_exacto' => $cantidadExacta,
                'exacto_format'    => $this->calculadora->formatearCantidad($cantidadExacta, $datos['unidad']),
                'requerido_seguro' => $cantidadSegura,
                'seguro_format'    => $this->calculadora->formatearCantidad($cantidadSegura, $datos['unidad']),
                'comprar_raw'      => $comprarComercial,
                'comprar_format'   => $this->calculadora->formatearCantidad($comprarComercial, $datos['unidad'])
            ];
        }

        // 5. Retornamos la vista con el evento y el reporte procesado
        return view('reportes.insumos', compact('evento', 'reporteInsumos'));
    }

    /**
     * Procesa y muestra el reporte de insumos para una comanda rápida (sin evento).
     */
    public function comandaRapida()
    {
        $comandaSession = session('comanda_rapida');

        if (!$comandaSession) {
            return redirect()->route('platillos.index')->with('error', 'No hay ninguna comanda rápida en proceso.');
        }

        $totalPersonas = $comandaSession['total'];
        $platillosIds = $comandaSession['platillos_ids'];

        // Calculamos los insumos a través del servicio
        $insumosCalculados = $this->calculadora->calcularParaComandaRapida($platillosIds, $totalPersonas);

        $reporteInsumos = [];
        $margenSeguridad = 1.10; // 10% extra por mermas y seguridad

        foreach ($insumosCalculados as $nombre => $datos) {
            // Consultamos la categoría desde el modelo Ingrediente
            $ingredienteModel = \App\Models\Ingrediente::where('nombre', $nombre)->first();
            $categoria = $ingredienteModel && $ingredienteModel->categoria ? $ingredienteModel->categoria : 'Otros';

            $cantidadExacta = $datos['cantidad'];
            
            // Aplicar margen del 10%
            $cantidadSegura = $cantidadExacta * $margenSeguridad;

            // Se compran insumos nuevos, ignoramos stock
            $comprarPuro = $cantidadSegura;

            // Aplicar redondeo comercial
            $comprarComercial = $comprarPuro > 0 ? $this->calculadora->calcularCompraComercial($comprarPuro, $datos['unidad']) : 0;

            // Construimos el array para la vista
            $reporteInsumos[] = [
                'nombre'           => $nombre,
                'unidad'           => $datos['unidad'],
                'categoria'        => $categoria,
                'requerido_exacto' => $cantidadExacta,
                'exacto_format'    => $this->calculadora->formatearCantidad($cantidadExacta, $datos['unidad']),
                'requerido_seguro' => $cantidadSegura,
                'seguro_format'    => $this->calculadora->formatearCantidad($cantidadSegura, $datos['unidad']),
                'comprar_raw'      => $comprarComercial,
                'comprar_format'   => $this->calculadora->formatearCantidad($comprarComercial, $datos['unidad'])
            ];
        }

        $platillos = \App\Models\Platillo::whereIn('id', $platillosIds)->get();

        return view('reportes.comanda-rapida', compact('reporteInsumos', 'comandaSession', 'platillos'));
    }
}