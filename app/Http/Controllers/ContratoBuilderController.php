<?php

namespace App\Http\Controllers;

use App\Models\Contrato;
use App\Models\Platillo;
use App\Models\Salon;
use Illuminate\Http\Request;

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
            'cliente' => 'Carmelo Pérez',
            'correo' => '',
            'telefono' => '',
            'evento_fecha' => date('Y-m-d'),
            'recepcion_hora' => '15:00',
            'inicio_hora' => '16:30',
            'tipo_evento' => 'Comunión',
            'festejado' => '',
            'estado' => 'cotizacion',
            'salon_id' => null,
            'platillo_ids' => [],
            'extras' => [],
            'horas_evento' => 5,
            'num_adultos' => 50,
            'num_ninos' => 20,
            'cliente_domicilio' => '',
            'cliente_ine' => '',
            'manteleria_color' => 'Blanco',
        ]);

        // Es mejor depender de los seeders de la base de datos para los datos de desarrollo.
        // Los bloques try-catch anteriores ocultaban posibles errores de conexión o de consulta.
        $salones = Salon::query()->with('sucursal')->orderBy('nombre')->get();

        $platillos = Platillo::query()->with('categoriaPlatillo')->orderBy('nombre')->get();

        return view('contrato-builder', compact('salones', 'platillos', 'draft'));
    }

    public function store(Request $request)
    {
        $extraKeys = array_keys(config('fantasync.extras', []));

        $data = $request->validate([
            'cliente' => 'required|string|max:150',
            'correo' => 'required|email|max:150',
            'telefono' => 'required|string|max:40',
            'evento_fecha' => 'required|date',
            'recepcion_hora' => 'required|string|max:20',
            'inicio_hora' => 'required|string|max:20',
            'tipo_evento' => 'required|string|max:80',
            'festejado' => 'required|string|max:120',
            'estado' => 'required|in:cotizacion,confirmado,finalizado,cancelado',
            'salon_id' => 'required|integer|exists:salones,id',
            'horas_evento' => 'required|integer|min:1',
            'num_adultos' => 'required|integer|min:0',
            'num_ninos' => 'required|integer|min:0',
            'cliente_domicilio' => 'nullable|string|max:255',
            'cliente_ine' => 'nullable|string|max:50',
            'manteleria_color' => 'nullable|string|max:50',
            'platillo_ids' => 'sometimes|array',
            'platillo_ids.*' => 'integer|exists:platillos,id',
            'extras' => 'sometimes|array',
            'extras.*' => 'nullable|boolean',
        ]);

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
            'recepcion_hora' => trim($data['recepcion_hora']),
            'inicio_hora' => trim($data['inicio_hora']),
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

        // 1. Crear o actualizar Cliente
        $cliente = \App\Models\Cliente::updateOrCreate(
            ['correo_electronico' => trim($data['correo'])],
            [
                'nombre_completo' => trim($data['cliente']),
                'celular' => trim($data['telefono']),
                'domicilio' => trim($data['cliente_domicilio'] ?? ''),
                'ine_numero' => trim($data['cliente_ine'] ?? '')
            ]
        );

        // 2. Obtener ID del evento existente si estamos editando
        $eventoId = null;
        if (session('contract_draft.contract_id')) {
            $existingContract = Contrato::find(session('contract_draft.contract_id'));
            if ($existingContract) {
                $eventoId = $existingContract->evento_id;
            }
        }

        // 3. Crear o actualizar Evento
        $evento = \App\Models\Evento::updateOrCreate(
            ['id' => $eventoId],
            [
                'cliente_id' => $cliente->id,
                'fecha' => $data['evento_fecha'],
                'hora_recepcion' => trim($data['recepcion_hora']),
                'hora_inicio' => trim($data['inicio_hora']),
                'horas_duracion' => (int) $data['horas_evento'],
                'tipo_evento' => trim($data['tipo_evento']),
                'nombre_festejado' => trim($data['festejado']),
                'estado' => $data['estado'],
                'color_manteleria' => trim($data['manteleria_color'] ?? ''),
                'titulo' => trim($data['tipo_evento']) . ' de ' . trim($data['festejado']),
                'notas' => 'Platillos: ' . implode(', ', $platilloIds) . '. Extras: ' . json_encode($extras)
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
        $eventoSalon = \App\Models\EventoSalon::where('evento_id', $evento->id)
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

        $draft = array_merge($contractData, ['contract_id' => $contract->id]);

        session(['contract_draft' => $draft]);

        return redirect()->route('eventos.menu', ['evento' => $evento->id])
                         ->with('status', 'Contrato guardado. Por favor, configura el menú.');
    }
}
