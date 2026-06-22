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
     * Calcula la cantidad comercial a comprar aplicando redondeo lógico según la unidad.
     */
    private function calcularCompraComercial($cantidadRequeridaSegura, $unidad)
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
            $comprarComercial = $comprarPuro > 0 ? $this->calcularCompraComercial($comprarPuro, $datos['unidad']) : 0;

            // 4. Construimos el array para la vista
            $reporteInsumos[] = [
                'nombre'           => $nombre,
                'unidad'           => $datos['unidad'],
                'categoria'        => $categoria,
                'requerido_exacto' => $cantidadExacta,
                'exacto_format'    => $this->formatearCantidad($cantidadExacta, $datos['unidad']),
                'requerido_seguro' => $cantidadSegura,
                'seguro_format'    => $this->formatearCantidad($cantidadSegura, $datos['unidad']),
                'comprar_raw'      => $comprarComercial,
                'comprar_format'   => $this->formatearCantidad($comprarComercial, $datos['unidad'])
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
            $comprarComercial = $comprarPuro > 0 ? $this->calcularCompraComercial($comprarPuro, $datos['unidad']) : 0;

            // Construimos el array para la vista
            $reporteInsumos[] = [
                'nombre'           => $nombre,
                'unidad'           => $datos['unidad'],
                'categoria'        => $categoria,
                'requerido_exacto' => $cantidadExacta,
                'exacto_format'    => $this->formatearCantidad($cantidadExacta, $datos['unidad']),
                'requerido_seguro' => $cantidadSegura,
                'seguro_format'    => $this->formatearCantidad($cantidadSegura, $datos['unidad']),
                'comprar_raw'      => $comprarComercial,
                'comprar_format'   => $this->formatearCantidad($comprarComercial, $datos['unidad'])
            ];
        }

        $platillos = \App\Models\Platillo::whereIn('id', $platillosIds)->get();

        return view('reportes.comanda-rapida', compact('reporteInsumos', 'comandaSession', 'platillos'));
    }
}