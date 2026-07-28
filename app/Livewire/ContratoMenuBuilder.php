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
    public $crema_sopa_id = '';
    public $guarnicion_formal_id = '';
    
    // Novedades para taquiza, bebidas e infantil
    public $taquiza_guisados = [];
    public $taquiza_parrillada = [];
    public $taquiza_guarniciones = [];
    public $infantil = [];
    public $bebidas = [];

    public function mount($eventoId)
    {
        $this->eventoId = $eventoId;
        
        $evento = Evento::with('eventoSalones.platillos.categoriaPlatillo')->find($eventoId);
        if ($evento && preg_match('/Servicio Gastronómico:\s*(\d+)/', $evento->notas, $matches)) {
            $this->servicio_id = $matches[1];
        }

        if ($evento && $evento->eventoSalones->isNotEmpty()) {
            $eventoSalonPivot = $evento->eventoSalones->first();
            $eventoSalonPivot->load('platillos.categoriaPlatillo');
            $platillos = $eventoSalonPivot->platillos;
            
            foreach($platillos as $p) {
                $cat = strtolower(trim($p->categoriaPlatillo->nombre ?? ''));
                if (in_array($cat, ['guisados', 'taquiza'])) {
                    $this->taquiza_guisados[] = (string) $p->id;
                } elseif (in_array($cat, ['parrillada (carnes)', 'parrillada', 'parrilladas', 'carnes'])) {
                    $this->taquiza_parrillada[] = (string) $p->id;
                } elseif (in_array($cat, ['entradas', 'entrada'])) {
                    $this->entrada_id = (string) $p->id;
                } elseif (in_array($cat, ['plato fuerte', 'platos fuertes'])) {
                    $this->plato_fuerte_id = (string) $p->id;
                } elseif (in_array($cat, ['cremas y sopas', 'cremas / sopas', 'crema', 'sopa'])) {
                    $this->crema_sopa_id = (string) $p->id;
                } elseif (in_array($cat, ['guarniciones (taquiza)', 'guarniciones'])) {
                    $this->taquiza_guarniciones[] = (string) $p->id;
                } elseif (in_array($cat, ['guarniciones (formales)'])) {
                    $this->guarnicion_formal_id = (string) $p->id;
                } elseif (in_array($cat, ['menú infantil', 'menu infantil', 'buffet infantil'])) {
                    $this->infantil[] = (string) $p->id;
                } elseif (in_array($cat, ['bebidas', 'bebida', 'aguas', 'refrescos'])) {
                    $this->bebidas[] = (string) $p->id;
                }
            }
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
            $reglas['guarnicion_formal_id'] = 'nullable|exists:platillos,id';
        } elseif ($this->servicio_id == 3) { // 3 Tiempos
            $reglas['crema_sopa_id'] = 'required|exists:platillos,id';
            $reglas['entrada_id'] = 'required|exists:platillos,id';
            $reglas['plato_fuerte_id'] = 'required|exists:platillos,id';
            $reglas['guarnicion_formal_id'] = 'nullable|exists:platillos,id';
        }

        $this->validate($reglas);

        // Intentamos buscar el evento en la base de datos
        $evento = Evento::with('salones')->find($this->eventoId);

        $platillosSeleccionados = [];
        if ($this->servicio_id == 1) {
            $platillosSeleccionados = array_merge($this->taquiza_guisados, $this->taquiza_parrillada, $this->taquiza_guarniciones);
        } elseif ($this->servicio_id == 2) {
            $platillosSeleccionados = array_filter([$this->entrada_id, $this->plato_fuerte_id, $this->guarnicion_formal_id]);
        } elseif ($this->servicio_id == 3) {
            $platillosSeleccionados = array_filter([$this->crema_sopa_id, $this->entrada_id, $this->plato_fuerte_id, $this->guarnicion_formal_id]);
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
                    // Exclusivo para niños
                    $porciones = max($ninos, 1);
                } elseif (in_array($catNombre, ['bebidas', 'bebida', 'aguas', 'refrescos'])) {
                    // Bebidas son generales (adultos + ninos*factor)
                    $porciones = (int) ceil($totalPorciones);
                } else {
                    // Taquiza, 2 Tiempos, 3 Tiempos son exclusivos de adultos
                    $porciones = max($adultos, 1);
                }

                $syncData[$platilloId] = [
                    'porciones_plan' => $porciones,
                    'orden'          => $index + 1,
                    'notas'          => null
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
                $query->with('serviciosGastronomicos')->orderBy('nombre');
            }])->orderBy('orden')->get()
        ]);
    }
}
