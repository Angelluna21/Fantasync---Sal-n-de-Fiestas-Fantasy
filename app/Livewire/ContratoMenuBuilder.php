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

    public function mount($eventoId)
    {
        $this->eventoId = $eventoId;
    }

    public function guardarMenu()
    {
        $reglas = [
            'servicio_id' => 'required|exists:servicio_gastronomicos,id',
        ];

        if ($this->servicio_id == 1) { // 1 = Taquiza
            $reglas['guisados'] = 'required|array|min:5|max:7';
        } elseif ($this->servicio_id == 2) { // 2 = Por Tiempos
            $reglas['entrada_id'] = 'required|exists:platillos,id';
            $reglas['plato_fuerte_id'] = 'required|exists:platillos,id';
        }

        $this->validate($reglas);

        // Intentamos buscar el evento en la base de datos
        $evento = Evento::with('salones')->find($this->eventoId);

        $platillosSeleccionados = [];
        if ($this->servicio_id == 1) {
            $platillosSeleccionados = $this->guisados;
        } else {
            $platillosSeleccionados = array_filter([$this->entrada_id, $this->plato_fuerte_id]);
        }

        foreach ($evento->salones as $salon) {
            $eventoSalonPivot = $salon->pivot;

            $adultos = $eventoSalonPivot->adultos;
            $ninos = $eventoSalonPivot->ninos;
            $factorNino = $eventoSalonPivot->factor_nino ?: 0.70;

            $totalPorciones = max(($adultos + ($ninos * $factorNino)), 1);

            $syncData = [];

            foreach ($platillosSeleccionados as $index => $platilloId) {
                // Lógica de Banquetería Estándar
                if ($this->servicio_id == 1) {
                    // TAQUIZA / BUFFET: La gente come aprox 2 veces una porción normal dividida en la variedad.
                    // Fórmula: (Total Invitados * 2) / Cantidad de Guisados
                    $cantidadGuisados = count($platillosSeleccionados);
                    $porcionesPorPlatillo = ($totalPorciones * 2) / max($cantidadGuisados, 1);
                } else {
                    // TIEMPOS: Cada invitado recibe 1 porción entera de la entrada y 1 del plato fuerte
                    $porcionesPorPlatillo = $totalPorciones;
                }

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
            'servicios' => ServicioGastronomico::all(),
            'categorias' => CategoriaPlatillo::with(['platillos' => function ($query) {
                $query->orderBy('nombre');
            }])->orderBy('orden')->get()
        ]);
    }
}
