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
            'cliente_domicilio' => trim($data['cliente_domicilio'] ?? ''),
            'cliente_ine' => trim($data['cliente_ine'] ?? ''),
            'manteleria_color' => trim($data['manteleria_color'] ?? ''),
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

            $salonOcupado = EventoSalon::where('salon_id', $data['salon_id'])
                ->whereHas('evento', function ($q) use ($data, $eventoId) {
                    $q->where('fecha', $data['evento_fecha']);
                    if ($eventoId) {
                        $q->where('id', '!=', $eventoId);
                    }
                })->exists();

            if ($salonOcupado) {
                throw new Exception("El salón seleccionado ya está reservado para esta fecha.");
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
            $horaInicio = trim($data['inicio_hora'] ?? '00:00');
            $horaRecepcion = trim($data['recepcion_hora'] ?? '00:00');
            $notasAdicionales = '';
            
            if (!empty($data['hora_por_definir'])) {
                $horaInicio = '00:00';
                $horaRecepcion = '00:00';
                $notasAdicionales .= ' (Hora por definir)';
            }
            if (!empty($data['tiene_misa'])) {
                $notasAdicionales .= ' (Misa solicitada)';
            }
            if (!empty($data['invitacion'])) {
                $notasAdicionales .= ' Invitación: ' . trim($data['invitacion']) . '.';
            }

            if (!empty($data['tiene_misa'])) $extras['tiene_misa'] = true;
            if (!empty($data['invitacion'])) $extras['invitacion'] = $data['invitacion'];

            $extras['horas_adicionales'] = (int) ($data['horas_adicionales'] ?? 0);

            $evento = Evento::updateOrCreate(
                ['id' => $eventoId],
                [
                    'cliente_id' => $cliente->id,
                    'fecha' => $data['evento_fecha'],
                    'hora_recepcion' => $horaRecepcion,
                    'hora_inicio' => $horaInicio,
                    'horas_duracion' => (int) $data['horas_evento'],
                    'tipo_evento' => trim($data['tipo_evento']),
                    'nombre_festejado' => trim($data['festejado']),
                    'estado' => $contractData['estado'],
                    'color_manteleria' => trim($data['manteleria_color'] ?? ''),
                    'titulo' => trim($data['tipo_evento']) . ' de ' . trim($data['festejado']),
                    'notas' => 'Servicio Gastronómico: ' . ($data['servicio_gastronomico'] ?? 'N/A') . '. Platillos: ' . implode(', ', $platilloIds) . '. Extras: ' . json_encode($extras) . $notasAdicionales
                ]
            );

            // 4. Asociar/Sincronizar Evento con el Salón en la tabla pivot
            $evento->salones()->sync([
                (int) $data['salon_id'] => [
                    'adultos' => (int) $data['num_adultos'],
                    'ninos' => (int) $data['num_ninos']
                ]
            ]);

            // 4.5 Asociar Platillos al EventoSalon (Comanda)
            $eventoSalon = EventoSalon::where('evento_id', $evento->id)
                ->where('salon_id', (int) $data['salon_id'])
                ->first();

            if ($eventoSalon && !empty($platilloIds)) {
                $numAdultos = (int) $data['num_adultos'];
                $numNinos = (int) $data['num_ninos'];
                $porcionesTotal = $numAdultos + $numNinos;
                
                $platillosModelos = \App\Models\Platillo::with('categoriaPlatillo')->whereIn('id', $platilloIds)->get();
                
                $syncPlatillos = [];
                foreach ($platillosModelos as $platilloModelo) {
                    $catNombre = strtolower(trim($platilloModelo->categoriaPlatillo->nombre ?? ''));
                    if (in_array($catNombre, ['menú infantil', 'menu infantil', 'buffet infantil'])) {
                        $porciones = max($numNinos, 1);
                    } else {
                        $porciones = $porcionesTotal;
                    }

                    $syncPlatillos[$platilloModelo->id] = [
                        'porciones_plan' => $porciones,
                        'orden' => 0
                    ];
                }

                // Si el servicio es 2 o 3 Tiempos, Paso 1 solo envía Bebidas e Infantil.
                // Debemos preservar los platillos que el usuario ya configuró en el Paso 2 (Entradas, Fuertes, etc.)
                if (in_array((int) $data['servicio_gastronomico'], [2, 3])) {
                    $existingPlatillos = $eventoSalon->platillos()->with('categoriaPlatillo')->get();
                    foreach ($existingPlatillos as $p) {
                        $cat = strtolower(trim($p->categoriaPlatillo->nombre ?? ''));
                        if (!in_array($cat, ['menú infantil', 'menu infantil', 'buffet infantil', 'bebidas'])) {
                            // Si no está ya en el sync array, lo agregamos para no perderlo
                            if (!array_key_exists($p->id, $syncPlatillos)) {
                                $syncPlatillos[$p->id] = [
                                    'porciones_plan' => $p->pivot->porciones_plan,
                                    'orden' => $p->pivot->orden,
                                    'notas' => $p->pivot->notas
                                ];
                            }
                        }
                    }
                }

                $eventoSalon->platillos()->sync($syncPlatillos);
            }

            // 5. Crear o actualizar Contrato
            
            $contract = Contrato::updateOrCreate(
                ['id' => session('contract_draft.contract_id')],
                [
                    'evento_id' => $evento->id,
                    'monto_total' => $total,
                    'anticipo' => $contractData['totalPagado'],
                    'saldo_pendiente' => $total - $contractData['totalPagado'],
                    'bebidas' => [],
                    'servicios_extras' => $extras,
                    'consentimiento_imagen' => true,
                    'fecha_firma' => date('Y-m-d')
                ]
            );

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
