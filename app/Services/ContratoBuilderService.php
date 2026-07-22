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

        // Calcular el total
        $subtotalPlatillos = Platillo::query()->whereIn('id', $platilloIds)->sum('precio');
        $extrasDefinition = config('fantasync.extras');
        $subtotalExtras = 0;
        foreach ($extras as $key => $isSelected) {
            if ($isSelected) {
                $subtotalExtras += $extrasDefinition[$key]['precio'] ?? 0;
            }
        }
        $total = $subtotalPlatillos + $subtotalExtras;

        $contractData = [
            'cliente' => trim($data['cliente']),
            'correo' => trim($data['correo']),
            'telefono' => trim($data['telefono']),
            'evento_fecha' => $data['evento_fecha'],
            'recepcion_hora' => trim($data['recepcion_hora'] ?? ''),
            'inicio_hora' => trim($data['inicio_hora'] ?? ''),
            'tipo_evento' => trim($data['tipo_evento']),
            'festejado' => trim($data['festejado']),
            'estado' => $data['estado'],
            'salon_id' => (int) $data['salon_id'],
            'horas_evento' => (int) $data['horas_evento'],
            'num_adultos' => (int) $data['num_adultos'],
            'num_ninos' => (int) $data['num_ninos'],
            'cliente_domicilio' => trim($data['cliente_domicilio'] ?? ''),
            'cliente_ine' => trim($data['cliente_ine'] ?? ''),
            'manteleria_color' => trim($data['manteleria_color'] ?? ''),
            'platillos' => $platilloIds,
            'extras' => $extras,
            'total' => $total,
        ];

        return DB::transaction(function () use ($data, $platilloIds, $extras, $total, $contractData) {
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
                    'estado' => $data['estado'],
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
                $porciones = ((int) $data['num_adultos']) + ((int) $data['num_ninos']);
                $syncPlatillos = [];
                foreach ($platilloIds as $pId) {
                    $syncPlatillos[$pId] = [
                        'porciones_plan' => $porciones,
                        'orden' => 0
                    ];
                }
                $eventoSalon->platillos()->sync($syncPlatillos);
            }

            // 5. Crear o actualizar Contrato
            $contract = Contrato::updateOrCreate(
                ['id' => session('contract_draft.contract_id')],
                [
                    'evento_id' => $evento->id,
                    'monto_total' => $total,
                    'anticipo' => 2500, // Anticipo mínimo base estipulado
                    'saldo_pendiente' => max(0, $total - 2500),
                    'bebidas' => [],
                    'servicios_extras' => $extras,
                    'consentimiento_imagen' => true,
                    'fecha_firma' => date('Y-m-d')
                ]
            );

            // 6. Actualizar sesión (se mantiene aquí por ser parte del flujo de estado global)
            $draft = array_merge($contractData, ['contract_id' => $contract->id]);
            session(['contract_draft' => $draft]);

            return $evento;
        });
    }
}
