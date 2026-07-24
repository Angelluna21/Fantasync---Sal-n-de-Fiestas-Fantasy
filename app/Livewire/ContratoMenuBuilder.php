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
    
    // Novedades para taquiza, bebidas e infantil
    public $taquiza_guisados = [];
    public $taquiza_guarniciones = [];
    public $infantil = [];
    public $bebidas = [];

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

        if ($this->servicio_id == 2) { // 2 Tiempos
            $reglas['entrada_id'] = 'required|exists:platillos,id';
            $reglas['plato_fuerte_id'] = 'required|exists:platillos,id';
        } elseif ($this->servicio_id == 3) { // 3 Tiempos
            $reglas['entrada_id'] = 'required|exists:platillos,id';
            $reglas['plato_fuerte_id'] = 'required|exists:platillos,id';
            $reglas['postre_id'] = 'required|exists:platillos,id';
        }

        $this->validate($reglas);

        // Intentamos buscar el evento en la base de datos
        $evento = Evento::with('salones')->find($this->eventoId);

        $platillosSeleccionados = [];
        if ($this->servicio_id == 1) {
            $platillosSeleccionados = array_merge($this->taquiza_guisados, $this->taquiza_guarniciones);
        } elseif ($this->servicio_id == 2) {
            $platillosSeleccionados = array_filter([$this->entrada_id, $this->plato_fuerte_id]);
        } elseif ($this->servicio_id == 3) {
            $platillosSeleccionados = array_filter([$this->entrada_id, $this->plato_fuerte_id, $this->postre_id]);
        }

        // Add infantil and bebidas (up to 2 bebidas logic could be validated in $reglas, or limited in UI)
        $platillosSeleccionados = array_merge($platillosSeleccionados, $this->infantil, $this->bebidas);

        foreach ($evento->salones as $salon) {
            $eventoSalonPivot = $salon->pivot;

            $adultos = $eventoSalonPivot->adultos;
            $ninos = $eventoSalonPivot->ninos;
            $factorNino = $eventoSalonPivot->factor_nino ?: 0.70;

            $totalPorciones = max(($adultos + ($ninos * $factorNino)), 1);

            $syncData = [];

            $platillosModelos = \App\Models\Platillo::with('categoriaPlatillo')->whereIn('id', $platillosSeleccionados)->get()->keyBy('id');

            foreach ($platillosSeleccionados as $index => $platilloId) {
                if(!isset($platillosModelos[$platilloId])) continue;
                $platillo = $platillosModelos[$platilloId];
                
                $catNombre = strtolower(trim($platillo->categoriaPlatillo->nombre ?? ''));
                if (in_array($catNombre, ['menú infantil', 'menu infantil', 'buffet infantil'])) {
                    $porciones = max($ninos, 1);
                } else {
                    $porciones = (int) ceil($totalPorciones);
                }

                $syncData[$platilloId] = [
                    'porciones_plan' => $porciones,
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
            'categorias' => CategoriaPlatillo::with(['platillos' => function ($query) {
                $query->orderBy('nombre');
            }])->orderBy('orden')->get()
        ]);
    }
}
