<?php

namespace App\Http\Controllers;

use App\Models\Contrato;
use App\Models\Platillo;
use App\Models\Salon;
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

        $salones = Salon::query()->with('sucursal')->orderBy('nombre')->get();
        $platillos = Platillo::query()->with(['categoriaPlatillo', 'serviciosGastronomicos'])->orderBy('nombre')->get();
        $serviciosGastronomicos = \App\Models\ServicioGastronomico::orderBy('id')->get();

        return view('contrato-builder', compact('salones', 'platillos', 'serviciosGastronomicos', 'draft'));
    }

    public function store(StoreContratoRequest $request, ContratoBuilderService $service)
    {
        try {
            $evento = $service->crearOActualizarContrato($request->validated());

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
