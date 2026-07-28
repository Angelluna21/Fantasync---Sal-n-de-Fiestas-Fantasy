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
        $evento = Evento::with(['salones', 'eventoSalones.platillos.categoriaPlatillo', 'eventoSalones.salon'])->findOrFail($id);
        
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

    /**
     * Procesa y consolida la lista global de compras (Central de Abastos) para un rango de fechas o semana.
     */
    public function comprasSemanal(\Illuminate\Http\Request $request)
    {
        // Por defecto: desde el inicio de esta semana hasta dos semanas en adelante para abarcar todos los eventos próximos
        $fechaInicio = $request->input('fecha_inicio', \Carbon\Carbon::now()->startOfWeek()->format('Y-m-d'));
        $fechaFin = $request->input('fecha_fin', \Carbon\Carbon::now()->endOfWeek()->addWeeks(2)->format('Y-m-d'));

        // Cargamos los eventos dentro del rango que tengan salones asignados
        $eventos = Evento::with(['salones', 'eventoSalones.platillos.categoriaPlatillo', 'eventoSalones.salon'])
            ->whereBetween('fecha', [$fechaInicio, $fechaFin])
            ->orderBy('fecha', 'asc')
            ->get();

        $listaGlobal = [];
        $margenSeguridad = 1.10; // 10% extra por mermas y seguridad

        foreach ($eventos as $evento) {
            $insumosEvento = $this->calculadora->calcularParaEvento($evento);

            foreach ($insumosEvento as $nombre => $datos) {
                if (!isset($listaGlobal[$nombre])) {
                    $ingredienteModel = \App\Models\Ingrediente::where('nombre', $nombre)->first();
                    $categoria = $ingredienteModel && $ingredienteModel->categoria ? $ingredienteModel->categoria : 'Otros';

                    $listaGlobal[$nombre] = [
                        'nombre' => $nombre,
                        'unidad' => $datos['unidad'],
                        'categoria' => $categoria,
                        'exacto_total' => 0,
                        'eventos_desglose' => []
                    ];
                }

                $listaGlobal[$nombre]['exacto_total'] += $datos['cantidad'];
                
                // Guardamos la cantidad que va para este evento específico (aplicando ya merma)
                $listaGlobal[$nombre]['eventos_desglose'][] = [
                    'evento_titulo' => $evento->titulo,
                    'evento_fecha' => $evento->fecha->format('d/m'),
                    'format' => $this->calculadora->formatearCantidad($datos['cantidad'] * $margenSeguridad, $datos['unidad'])
                ];
            }
        }

        $reporteConsolidado = [];
        foreach ($listaGlobal as $nombre => $item) {
            $exacto = $item['exacto_total'];
            $seguro = $exacto * $margenSeguridad;
            $comprarComercial = $seguro > 0 ? $this->calculadora->calcularCompraComercial($seguro, $item['unidad']) : 0;

            $reporteConsolidado[] = [
                'nombre' => $nombre,
                'unidad' => $item['unidad'],
                'categoria' => $item['categoria'],
                'exacto_format' => $this->calculadora->formatearCantidad($exacto, $item['unidad']),
                'seguro_format' => $this->calculadora->formatearCantidad($seguro, $item['unidad']),
                'comprar_format' => $this->calculadora->formatearCantidad($comprarComercial, $item['unidad']),
                'eventos_desglose' => $item['eventos_desglose']
            ];
        }

        // Agrupamos y ordenamos por categoría de supermercado/central de abastos
        $groupedInsumos = collect($reporteConsolidado)->groupBy('categoria');
        $categoriaOrder = ['Frutas y Verduras', 'Carnes', 'Cremería', 'Abarrotes', 'General', 'Otros'];
        $sortedGroups = $groupedInsumos->sortBy(function($val, $key) use ($categoriaOrder) {
            $pos = array_search($key, $categoriaOrder);
            return $pos === false ? 99 : $pos;
        });

        return view('reportes.compras-semana', compact('eventos', 'sortedGroups', 'fechaInicio', 'fechaFin'));
    }
}