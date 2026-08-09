<?php

namespace App\Http\Controllers;

use App\Models\Contrato;
use App\Models\Platillo;
use App\Models\Salon;
use App\Models\Vendedora;
use App\Http\Requests\StoreContratoRequest;
use App\Services\ContratoBuilderService;
use Exception;

class ContratoBuilderController extends Controller
{
    public function create()
    {
        // El borrador se carga desde la sesión para permitir la edición.
        // Para iniciar un nuevo contrato, la sesión 'contract_draft' tendría que ser borrada,
        // por ejemplo, a través de una acción o ruta dedicada como /contratos/crear?new=1.
        if (request()->query('new')) {
            session()->forget('contract_draft');
        }
        
        $draft = session('contract_draft', [
            'cliente' => '',
            'correo' => '',
            'telefono' => '',
            'evento_fecha' => '',
            'recepcion_hora' => '',
            'inicio_hora' => '',
            'tipo_evento' => '',
            'festejado' => '',
            'estado' => 'cotizacion',
            'salon_id' => null,
            'platillo_ids' => [],
            'extras' => [],
            'c_album_paquete' => 0,
            'c_derecho_pista' => 0,
            'c_hora_extra' => 0,
            'quien_vendio_hora_extra' => '',
            'c_camara_360' => 0,
            'horas_evento' => 6,
            'horas_adicionales' => 0,
            'num_adultos' => 0,
            'num_ninos' => 0,
            'total_personas' => 0,
            'cliente_domicilio' => '',
            'cliente_ine' => '',
            'manteleria_color' => '',
            'cubre_mantel_color' => '',
            'monos_color' => '',
            'camino_mesa_color' => '',
            'vendedoras_ids' => [],
        ]);

        $salones = Salon::query()->with('sucursal')->orderBy('nombre')->get();
        $platillos = Platillo::query()->with(['categoriaPlatillo', 'serviciosGastronomicos'])->orderBy('nombre')->get();
        $serviciosGastronomicos = \App\Models\ServicioGastronomico::whereNotIn('nombre', ['Menú Infantil', 'Bebidas', 'Buffet Infantil'])
            ->orderBy('id')->get();
        $vendedoras = Vendedora::where('estado', 'activo')->orderBy('nombre')->get();

        return view('contrato-builder', compact('salones', 'platillos', 'serviciosGastronomicos', 'vendedoras', 'draft'));
    }

    public function edit(Contrato $contrato)
    {
        $evento = $contrato->evento;
        $eventoSalon = $evento->eventoSalones()->first();
        
        $salon_id = $eventoSalon ? $eventoSalon->salon_id : null;
        $num_adultos = $eventoSalon ? $eventoSalon->adultos : 50;
        $num_ninos = $eventoSalon ? $eventoSalon->ninos : 20;
        $total_personas = $eventoSalon ? $eventoSalon->total_personas : 0;

        $servicioGastronomico = null;
        $notas = $evento->notas;
        if (preg_match('/Servicio Gastronómico:\s*(\d+)/', $notas, $matches)) {
            $servicioGastronomico = $matches[1];
        }

        $platillos = [];
        if ($eventoSalon) {
            $platillos = $eventoSalon->platillos()->pluck('platillos.id')->toArray();
        }
        
        $hora_por_definir = str_contains($notas, '(Hora por definir)');

        $invitacion_estado = '';
        $invitacion_detalle = '';
        if (preg_match('/Invitación:\s*(.*?)\./', $notas, $matches)) {
            $invFull = $matches[1];
            if (str_contains($invFull, ' - ')) {
                $parts = explode(' - ', $invFull, 2);
                $invitacion_estado = $parts[0];
                $invitacion_detalle = $parts[1];
            } else {
                $invitacion_estado = $invFull;
            }
        }

        $extras = $contrato->servicios_extras ?? [];
        if ($hora_por_definir) {
            $extras['hora_por_definir'] = true;
        }

        $draft = [
            'contract_id' => $contrato->id,
            'cliente' => $evento->cliente->nombre_completo ?? '',
            'correo' => $evento->cliente->correo_electronico ?? '',
            'telefono' => $evento->cliente->celular ?? '',
            'tel_casa' => $evento->cliente->telefono_casa ?? '',
            'evento_fecha' => $evento->fecha->format('Y-m-d'),
            'recepcion_hora' => $hora_por_definir ? '' : \Carbon\Carbon::parse($evento->hora_recepcion)->format('H:i'),
            'inicio_hora' => $hora_por_definir ? '' : \Carbon\Carbon::parse($evento->hora_inicio)->format('H:i'),
            'tipo_evento' => $evento->tipo_evento ?? '',
            'festejado' => $evento->nombre_festejado ?? '',
            'estado' => $evento->estado ?? 'cotizacion',
            'salon_id' => $salon_id,
            'platillo_ids' => $platillos,
            'servicio_gastronomico' => $servicioGastronomico,
            'c_album_paquete' => $extras['c_album_paquete'] ?? 0,
            'c_derecho_pista' => $extras['c_derecho_pista'] ?? 0,
            'c_hora_extra' => $extras['c_hora_extra'] ?? 0,
            'quien_vendio_hora_extra' => $extras['quien_vendio_hora_extra'] ?? '',
            'c_camara_360' => $extras['c_camara_360'] ?? 0,
            'extras' => $extras,
            'horas_evento' => $evento->horas_duracion ?? 6,
            'horas_adicionales' => $extras['horas_adicionales'] ?? 0,
            'num_adultos' => $num_adultos,
            'num_ninos' => $num_ninos,
            'total_personas' => $total_personas,
            'cliente_domicilio' => $evento->cliente->domicilio ?? '',
            'cp' => $evento->cliente->codigo_postal ?? '',
            'cliente_ine' => $evento->cliente->ine_numero ?? '',
            'manteleria_color' => $evento->color_manteleria ?? '',
            'cubre_mantel_color' => $evento->color_cubre_mantel ?? '',
            'monos_color' => $evento->color_monos ?? '',
            'camino_mesa_color' => $evento->color_camino_mesa ?? '',
            'invitacion_estado' => $invitacion_estado,
            'invitacion_detalle' => $invitacion_detalle,
            'vendedoras_ids' => $contrato->vendedoras()->pluck('vendedoras.id')->toArray(),
        ];

        // Migración en memoria: Si existe historial_pagos usarlo, sino generar uno si hay anticipo > 0
        if (!empty($contrato->servicios_extras['historial_pagos'])) {
            $draft['pagos'] = $contrato->servicios_extras['historial_pagos'];
        } else if (($contrato->anticipo ?? 0) > 0) {
            $draft['pagos'] = [
                [
                    'monto' => $contrato->anticipo,
                    'recibo' => $contrato->servicios_extras['recibo_transferencia'] ?? '',
                    'fecha' => $contrato->created_at ? $contrato->created_at->format('Y-m-d') : date('Y-m-d')
                ]
            ];
        } else {
            $draft['pagos'] = [];
        }

        // Añadir los costos de vuelta al draft si existen
        if (!empty($contrato->servicios_extras['desglose_costos'])) {
            foreach ($contrato->servicios_extras['desglose_costos'] as $k => $v) {
                $draft[$k] = $v;
            }
        }

        session(['contract_draft' => $draft]);

        return redirect()->route('contratos.crear');
    }

    public function store(StoreContratoRequest $request, ContratoBuilderService $service)
    {
        try {
            $evento = $service->crearOActualizarContrato($request->validated());

            if ($request->input('action') === 'save_only') {
                session()->forget('contract_draft');
                return redirect()->route('contratos.index')
                                 ->with('success', 'Contrato guardado correctamente.');
            }

            session()->forget('contract_draft');
            return redirect()->route('eventos.menu', ['evento' => $evento->id])
                             ->with('status', 'Contrato guardado. Por favor, configura el menú.');
        } catch (Exception $e) {
            return back()->withErrors([
                'evento_fecha' => $e->getMessage(),
                'salon_id' => $e->getMessage()
            ])->withInput();
        }
    }
}
