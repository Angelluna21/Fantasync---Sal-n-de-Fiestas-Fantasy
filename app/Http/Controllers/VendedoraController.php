<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVendedoraRequest;
use App\Http\Requests\UpdateVendedoraRequest;
use App\Models\Vendedora;

class VendedoraController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $vendedoras = Vendedora::all();
        return view('vendedoras.index', compact('vendedoras'));
    }

    /**
     * Reporte estadístico de ventas.
     */
    public function estadisticas(\Illuminate\Http\Request $request)
    {
        $periodo = $request->get('periodo', 'todos'); // 'semana', 'mes', 'anio', 'todos'
        $vendedoraId = $request->get('vendedora_id', 'todas');
        
        $todasLasVendedoras = Vendedora::with('contratos.evento')->get();

        // Obtener vendedoras a procesar
        $vendedoras = $todasLasVendedoras;
        if ($vendedoraId !== 'todas') {
            $vendedoras = $vendedoras->where('id', $vendedoraId);
        }

        $stats = [];
        foreach ($vendedoras as $vendedora) {
            // Todos los contratos para calcular desglose de cantidades
            $todosLosContratos = $vendedora->contratos;
            
            $cntSemana = 0;
            $cntMes = 0;
            $cntAnio = 0;
            $cntHistorico = $todosLosContratos->count();
            
            $now = now();
            $startOfWeek = $now->copy()->startOfWeek();
            $endOfWeek = $now->copy()->endOfWeek();
            $startOfMonth = $now->copy()->startOfMonth();
            $endOfMonth = $now->copy()->endOfMonth();
            $startOfYear = $now->copy()->startOfYear();
            $endOfYear = $now->copy()->endOfYear();

            foreach($todosLosContratos as $c) {
                if (!$c->evento || !$c->evento->fecha) continue;
                $fechaEvento = \Carbon\Carbon::parse($c->evento->fecha);
                if ($fechaEvento->between($startOfWeek, $endOfWeek)) $cntSemana++;
                if ($fechaEvento->between($startOfMonth, $endOfMonth)) $cntMes++;
                if ($fechaEvento->between($startOfYear, $endOfYear)) $cntAnio++;
            }

            // Filtrar para los cálculos financieros (basado en la fecha del EVENTO, no de la firma)
            $contratosQuery = $vendedora->contratos()->whereHas('evento', function ($query) use ($periodo, $startOfWeek, $endOfWeek, $startOfMonth, $endOfMonth, $startOfYear, $endOfYear) {
                if ($periodo === 'semana') {
                    $query->whereBetween('fecha', [$startOfWeek, $endOfWeek]);
                } elseif ($periodo === 'mes') {
                    $query->whereBetween('fecha', [$startOfMonth, $endOfMonth]);
                } elseif ($periodo === 'anio') {
                    $query->whereBetween('fecha', [$startOfYear, $endOfYear]);
                }
            })->with('evento');
            
            $contratos = $contratosQuery->get();
            $comisionTotal = 0;
            $montoTotalVendido = 0; // Total Bruto
            $montoDescontado = 0;   // Hr Extra Restada
            $bonoExtras = 0;        // Bono 10%
            
            foreach ($contratos as $contrato) {
                $evento = $contrato->evento;
                $montoTotalContrato = (float) $contrato->monto_total;
                $montoTotalVendido += $montoTotalContrato;
                
                $numVendedoras = $contrato->vendedoras()->count();
                if ($numVendedoras === 0) $numVendedoras = 1;
                
                $extras = $contrato->servicios_extras ?? [];
                $desglose = $extras['desglose_costos'] ?? [];
                $costoHoraExtra = isset($desglose['c_hora_extra']) ? (float)$desglose['c_hora_extra'] : 0;
                $quienVendioHrExtra = $extras['quien_vendio_hora_extra'] ?? '';
                
                $esSabado = false;
                if ($evento && $evento->fecha) {
                    $esSabado = \Carbon\Carbon::parse($evento->fecha)->isSaturday();
                }
                
                $montoComisionable = $montoTotalContrato;
                
                // Restar la hora extra del monto base a comisionar si:
                // - NO es sábado
                // - O SI la vendió el capitán (sin importar el día)
                if (!$esSabado || $quienVendioHrExtra === 'capitan') {
                    if ($costoHoraExtra > 0) {
                        $montoDescontado += $costoHoraExtra;
                    }
                    $montoComisionable = max(0, $montoTotalContrato - $costoHoraExtra);
                }
                
                $porcentajeComision = $esSabado ? 0.01 : 0.05;
                $comisionBaseIndividual = ($montoComisionable * $porcentajeComision) / $numVendedoras;
                $comisionTotal += $comisionBaseIndividual;
                
                if ($quienVendioHrExtra === 'vendedora_' . $vendedora->id) {
                    $bono = $costoHoraExtra * 0.10;
                    $bonoExtras += $bono;
                    $comisionTotal += $bono;
                }
            }
            
            $stats[] = [
                'vendedora' => $vendedora,
                'cantidad_contratos' => $contratos->count(), // Contratos en el periodo seleccionado
                'cnt_semana' => $cntSemana,
                'cnt_mes' => $cntMes,
                'cnt_anio' => $cntAnio,
                'cnt_historico' => $cntHistorico,
                'monto_total' => $montoTotalVendido,
                'monto_descontado' => $montoDescontado,
                'bono_extras' => $bonoExtras,
                'comisiones' => $comisionTotal,
            ];
        }

        // Ordenar por monto_total de mayor a menor
        usort($stats, function ($a, $b) {
            return $b['monto_total'] <=> $a['monto_total'];
        });

        return view('vendedoras.estadisticas', compact('stats', 'periodo', 'todasLasVendedoras', 'vendedoraId'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('vendedoras.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreVendedoraRequest $request)
    {
        Vendedora::create($request->validated());
        return redirect()->route('vendedoras.index')->with('success', 'Vendedora creada exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Vendedora $vendedora)
    {
        return view('vendedoras.show', compact('vendedora'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vendedora $vendedora)
    {
        return view('vendedoras.edit', compact('vendedora'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateVendedoraRequest $request, Vendedora $vendedora)
    {
        $vendedora->update($request->validated());
        return redirect()->route('vendedoras.index')->with('success', 'Vendedora actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vendedora $vendedora)
    {
        $vendedora->delete();
        return redirect()->route('vendedoras.index')->with('success', 'Vendedora eliminada exitosamente.');
    }
}
