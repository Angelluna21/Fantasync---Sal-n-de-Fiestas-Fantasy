<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\ServicioGastronomico;
use App\Models\CategoriaPlatillo;
use App\Models\Evento;

class ContratoMenuBuilder extends Component
{
    public $eventoId;
    public $servicio_id = '';
    public $guisados = [];
    public $entrada_id = '';
    public $plato_fuerte_id = '';
    public $postre_id = '';

    public function mount($eventoId)
    {
        $this->eventoId = $eventoId;
        
        $evento = Evento::find($eventoId);
        if ($evento && preg_match('/Servicio Gastronómico:\s*(\d+)/', $evento->notas, $matches)) {
            $this->servicio_id = $matches[1];
        }
    }

    public function guardarMenu()
    {
        $reglas = [
            'servicio_id' => 'required|exists:servicios_gastronomicos,id',
        ];

        if ($this->servicio_id == 2) { // 2 = 2 Tiempos
            $reglas['entrada_id'] = 'required|exists:platillos,id';
            $reglas['plato_fuerte_id'] = 'required|exists:platillos,id';
        } elseif ($this->servicio_id == 3) { // 3 = 3 Tiempos
            $reglas['entrada_id'] = 'required|exists:platillos,id';
            $reglas['plato_fuerte_id'] = 'required|exists:platillos,id';
            $reglas['postre_id'] = 'required|exists:platillos,id';
        }

        $this->validate($reglas);

        // Intentamos buscar el evento en la base de datos
        $evento = Evento::with('salones')->find($this->eventoId);

        // Si es Taquiza (ID 1) o Menú Infantil, los platillos ya se guardaron en el paso 1
        if ($this->servicio_id == 1 || $this->servicio_id == 4) {
            return redirect()->route('reportes.insumos', $this->eventoId)
                ->with('exito', 'Servicio guardado correctamente.');
        }

        $platillosSeleccionados = [];
        if ($this->servicio_id == 2) {
            $platillosSeleccionados = array_filter([$this->entrada_id, $this->plato_fuerte_id]);
        } elseif ($this->servicio_id == 3) {
            $platillosSeleccionados = array_filter([$this->entrada_id, $this->plato_fuerte_id, $this->postre_id]);
        }

        foreach ($evento->salones as $salon) {
            $eventoSalonPivot = $salon->pivot;

            $adultos = $eventoSalonPivot->adultos;
            $ninos = $eventoSalonPivot->ninos;
            $factorNino = $eventoSalonPivot->factor_nino ?: 0.70;

            $totalPorciones = max(($adultos + ($ninos * $factorNino)), 1);

            $syncData = [];

            foreach ($platillosSeleccionados as $index => $platilloId) {
                // TIEMPOS: Cada invitado recibe 1 porción entera
                $porcionesPorPlatillo = $totalPorciones;

                $syncData[$platilloId] = [
                    'porciones_plan' => (int) ceil($porcionesPorPlatillo),
                    'orden'          => $index + 1,
                    'notas'          => 'Registrado desde el configurador dinámico'
                ];
            }

            $eventoSalonPivot->platillos()->sync($syncData);
        }

        return redirect()->route('reportes.insumos', $this->eventoId)
            ->with('exito', 'Comanda guardada correctamente.');
    }

    public function render()
    {
        return view('livewire.contrato-menu-builder', [
            'servicios' => ServicioGastronomico::whereIn('id', [1, 2, 3])->get(),
            'categorias' => CategoriaPlatillo::whereIn('id', [31, 32, 33])->with(['platillos' => function ($query) {
                $query->orderBy('nombre');
            }])->orderBy('orden')->get()
        ]);
    }
}
