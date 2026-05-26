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

        $contract = Contrato::updateOrCreate(
            ['id' => session('contract_draft.contract_id')],
            $contractData
        );

        $draft = array_merge($contractData, ['contract_id' => $contract->id]);

        session(['contract_draft' => $draft]);

        return redirect()->route('contrato.demo')->with('status', 'Contrato guardado y listo para previsualizar.');
    }
}
