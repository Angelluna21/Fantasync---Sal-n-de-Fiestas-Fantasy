<?php

namespace App\Services;

use App\Models\Contrato;
use App\Models\Platillo;
use App\Models\Cliente;
use App\Models\Evento;
use App\Models\EventoSalon;
use Illuminate\Support\Facades\DB;
use Exception;

class ContratoBuilderService
{
    /**
     * Procesa los datos validados y genera el Contrato y sus relaciones
     * 
     * @param array $data Datos validados del request
     * @return \App\Models\Evento Evento creado/actualizado
     * @throws Exception Si el salón está ocupado o falla la transacción
     */
    public function crearOActualizarContrato(array $data)
    {
        $extraKeys = array_keys(config('fantasync.extras', []));
        $platilloIds = array_values(array_map('intval', $data['platillo_ids'] ?? []));
        $extras = [];
        foreach ($extraKeys as $key) {
            $extras[$key] = ! empty($data['extras'][$key]);
        }

        $costosKeys = [
            'c_renta_salon', 'c_otras_bebidas', 'c_pinata', 'c_mesa_dulces',
            'c_show', 'c_usb_video', 'c_album_digital', 'c_album_paquete',
            'c_derecho_pista', 'c_hora_extra', 'c_camara_360', 'c_amenizacion',
            'c_personas_adicionales', 'c_cafe', 'c_mickey_movil', 'c_otros'
        ];
        
        $desgloseCostos = [];
        $totalCostos = 0;
        foreach ($costosKeys as $key) {
            $val = floatval($data[$key] ?? 0);
            if ($val > 0) {
                $desgloseCostos[$key] = $val;
                $totalCostos += $val;
            }
        }
        
        $extras['desglose_costos'] = $desgloseCostos;
        
        // El total es exactamente lo que el usuario ingresó en el desglose
        $total = $totalCostos;

        $pagos = $data['pagos'] ?? [];
        $totalPagado = 0;
        foreach ($pagos as $pago) {
            $totalPagado += floatval($pago['monto'] ?? 0);
        }
        
        $extras['historial_pagos'] = $pagos;
        
        if (isset($data['servicio_gastronomico'])) {
            $extras['servicio_gastronomico_id'] = (int) $data['servicio_gastronomico'];
        }

        $estadoEvento = $data['estado'];
        if ($total > 0 && $totalPagado >= $total) {
            $estadoEvento = 'finalizado';
        }

        $contractData = [
            'cliente' => trim($data['cliente']),
            'correo' => trim($data['correo']),
            'telefono' => trim($data['telefono']),
            'evento_fecha' => $data['evento_fecha'],
            'recepcion_hora' => trim($data['recepcion_hora'] ?? ''),
            'inicio_hora' => trim($data['inicio_hora'] ?? ''),
            'tipo_evento' => trim($data['tipo_evento']),
            'festejado' => trim($data['festejado']),
            'estado' => $estadoEvento,
            'salon_id' => (int) $data['salon_id'],
            'horas_evento' => (float) $data['horas_evento'],
            'num_adultos' => (int) $data['num_adultos'],
            'num_ninos' => (int) $data['num_ninos'],
            'total_personas' => (int) ($data['total_personas'] ?? 0),
            'cliente_domicilio' => trim($data['cliente_domicilio'] ?? ''),
            'cliente_ine' => trim($data['cliente_ine'] ?? ''),
            'manteleria_color' => trim($data['manteleria_color'] ?? ''),
            'cubre_mantel_color' => trim($data['cubre_mantel_color'] ?? ''),
            'monos_color' => trim($data['monos_color'] ?? ''),
            'camino_mesa_color' => trim($data['camino_mesa_color'] ?? ''),
            'platillos' => $platilloIds,
            'extras' => $extras,
            'total' => $total,
            'pagos' => $pagos,
            'totalPagado' => $totalPagado,
        ];

        return DB::transaction(function () use ($data, $platilloIds, $extras, $total, $contractData, $desgloseCostos) {
            // Bloqueo pesimista para evitar Condiciones de Carrera (duplicidad de reservas en el mismo instante)
            DB::table('salones')->where('id', $data['salon_id'])->lockForUpdate()->first();

            // --- VALIDACIÓN DE DISPONIBILIDAD (Evitar duplicidad / doble reserva) ---
            $eventoId = null;
            if (session('contract_draft.contract_id')) {
                $existingContract = Contrato::find(session('contract_draft.contract_id'));
                if ($existingContract) {
                    $eventoId = $existingContract->evento_id;
                }
            }
            
            $isServicioExterno = !empty($data['servicio_externo']);
            if ($isServicioExterno) {
                $extras['servicio_externo'] = true;
            }

            if (!$isServicioExterno) {
                $salonOcupado = EventoSalon::where('salon_id', $data['salon_id'])
                    ->whereHas('evento', function ($q) use ($data, $eventoId) {
                        $q->where('fecha', $data['evento_fecha']);
                        if ($eventoId) {
                            $q->where('id', '!=', $eventoId);
                        }
                    })->exists();

                if ($salonOcupado) {
                    throw new Exception("El salón seleccionado ya está reservado para esta fecha. Si es en otra ubicación, marque 'Servicio Externo'.");
                }
            }
            // -------------------------------------------------------------------------
            // Validar restricción de Cliente Único por fecha
            // Evita que se duplique un evento accidentalmente para la misma persona el mismo día
            $clienteDuplicadoQuery = \App\Models\Evento::whereDate('fecha', $data['evento_fecha'])
                ->whereHas('cliente', function ($q) use ($data) {
                    $q->where('nombre_completo', trim($data['cliente']));
                });
            
            if ($eventoId) {
                $clienteDuplicadoQuery->where('id', '!=', $eventoId);
            }
            
            if ($clienteDuplicadoQuery->exists()) {
                throw new \Exception("El cliente '" . trim($data['cliente']) . "' ya tiene un evento registrado para esta misma fecha. No se pueden duplicar contratos para la misma persona el mismo día.");
            }
            // -------------------------------------------------------------------------

            // 1. Crear o actualizar Cliente
            $cliente = Cliente::updateOrCreate(
                ['correo_electronico' => trim($data['correo'])],
                [
                    'nombre_completo' => trim($data['cliente']),
                    'celular' => trim($data['telefono']),
                    'telefono_casa' => trim($data['tel_casa'] ?? ''),
                    'codigo_postal' => trim($data['cp'] ?? ''),
                    'domicilio' => trim($data['cliente_domicilio'] ?? ''),
                    'ine_numero' => trim($data['cliente_ine'] ?? '')
                ]
            );

            // 3. Crear o actualizar Evento
            $eventoActual = null;
            if ($eventoId) {
                $eventoActual = Evento::find($eventoId);
            }
            $servicioGastronomicoAnterior = null;
            if ($eventoActual) {
                if (preg_match('/Servicio Gastronómico:\s*(\d+)/', $eventoActual->notas, $matches)) {
                    $servicioGastronomicoAnterior = $matches[1];
                }
            }

            $horaInicio = trim($data['inicio_hora'] ?? '');
            $horaRecepcion = trim($data['recepcion_hora'] ?? '');
            
            if ($horaInicio === '') $horaInicio = null;
            if ($horaRecepcion === '') $horaRecepcion = null;

            $notasAdicionales = '';
            
            if (!empty($data['hora_por_definir'])) {
                $horaInicio = '00:00';
                $horaRecepcion = '00:00';
                $notasAdicionales .= ' (Hora por definir)';
                $extras['hora_por_definir'] = true;
            } else {
                $extras['hora_por_definir'] = false;
            }
            if (!empty($data['tiene_misa'])) {
                $notasAdicionales .= ' (Misa solicitada)';
            }
            if (!empty($data['invitacion_estado'])) {
                $invStr = trim($data['invitacion_estado']);
                if (!empty($data['invitacion_detalle'])) {
                    $invStr .= ' - ' . trim($data['invitacion_detalle']);
                }
                $notasAdicionales .= ' Invitación: ' . $invStr . '.';
            }

            if (!empty($data['tiene_misa'])) $extras['tiene_misa'] = true;
            $extras['horas_adicionales'] = (float) ($data['horas_adicionales'] ?? 0);
            $extras['invitacion_estado'] = $data['invitacion_estado'] ?? '';
            $extras['invitacion_detalle'] = $data['invitacion_detalle'] ?? '';
            $extras['quien_vendio_hora_extra'] = $data['quien_vendio_hora_extra'] ?? '';
            $extras['servicio_cafe'] = !empty($data['servicio_cafe']);

            if (!empty($data['tiene_pinata'])) {
                $extras['tiene_pinata'] = true;
                $extras['detalle_pinata'] = $data['detalle_pinata'] ?? '';
            } else {
                $extras['tiene_pinata'] = false;
                $extras['detalle_pinata'] = '';
            }

            if (!empty($data['tiene_show'])) {
                $extras['tiene_show'] = true;
                $extras['detalle_show'] = $data['detalle_show'] ?? '';
            } else {
                $extras['tiene_show'] = false;
                $extras['detalle_show'] = '';
            }

            $extras['arco_globos'] = !empty($data['arco_globos']);
            $extras['derecho_pista_check'] = !empty($data['derecho_pista_check']);

            $eventoData = [
                'cliente_id' => $cliente->id,
                'fecha' => $data['evento_fecha'],
                'hora_recepcion' => $horaRecepcion,
                'hora_inicio' => $horaInicio,
                'horas_duracion' => (float) $data['horas_evento'],
                'tipo_evento' => trim($data['tipo_evento']),
                'nombre_festejado' => trim($data['festejado']),
                'estado' => $contractData['estado'],
                'color_manteleria' => trim($data['manteleria_color'] ?? ''),
                'color_cubre_mantel' => trim($data['cubre_mantel_color'] ?? ''),
                'color_monos' => trim($data['monos_color'] ?? ''),
                'color_camino_mesa' => trim($data['camino_mesa_color'] ?? ''),
                'titulo' => trim($data['cliente']),
                'notas' => 'Servicio Gastronómico: ' . ($data['servicio_gastronomico'] ?? 'N/A') . '. Platillos: ' . implode(', ', $platilloIds) . '. Extras: ' . json_encode($extras) . $notasAdicionales
            ];

            if ($eventoId) {
                $evento = Evento::findOrFail($eventoId);
                $evento->update($eventoData);
            } else {
                $evento = Evento::create($eventoData);
            }

            // 4. Asociar/Sincronizar Evento con el Salón en la tabla pivot
            $evento->salones()->sync([
                (int) $data['salon_id'] => [
                    'adultos' => (int) $data['num_adultos'],
                    'ninos' => (int) $data['num_ninos'],
                    'total_personas' => (int) ($data['total_personas'] ?? 0)
                ]
            ]);

            // Si el servicio gastronómico cambió, limpiar los platillos para evitar platillos fantasma
            $servicioGastronomicoNuevo = $data['servicio_gastronomico'] ?? null;
            if ($eventoActual && $servicioGastronomicoAnterior != $servicioGastronomicoNuevo) {
                $eventoSalonPivot = EventoSalon::where('evento_id', $evento->id)->first();
                if ($eventoSalonPivot) {
                    $eventoSalonPivot->platillos()->detach();
                }
            }


            // 5. Crear o actualizar Contrato
            $contratoId = session('contract_draft.contract_id');
            $contratoData = [
                'evento_id' => $evento->id,
                'monto_total' => $total,
                'anticipo' => $contractData['totalPagado'],
                'saldo_pendiente' => $total - $contractData['totalPagado'],
                'bebidas' => [],
                'servicios_extras' => $extras,
                'consentimiento_imagen' => true,
                'fecha_firma' => date('Y-m-d')
            ];

            if ($contratoId) {
                $contract = Contrato::findOrFail($contratoId);
                $contract->update($contratoData);
            } else {
                $contract = Contrato::create($contratoData);
            }

            // Sincronizar vendedoras
            if (isset($data['vendedoras_ids']) && is_array($data['vendedoras_ids'])) {
                $contract->vendedoras()->sync($data['vendedoras_ids']);
            } else {
                $contract->vendedoras()->detach();
            }

            // 6. Actualizar sesión (se mantiene aquí por ser parte del flujo de estado global)
            // Agregar pagos al draft
            
            // También agregar desgloseCostos al draft para que se mantengan al fallar la validación
            foreach ($desgloseCostos as $k => $v) {
                $contractData[$k] = $v;
            }

            $draft = array_merge($contractData, ['contract_id' => $contract->id]);
            session(['contract_draft' => $draft]);

            return $evento;
        });
    }
}
