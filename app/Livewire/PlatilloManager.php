<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Platillo;
use App\Models\CategoriaPlatillo;
use App\Models\ServicioGastronomico;
use App\Models\Ingrediente;

class PlatilloManager extends Component
{
    public $nombre, $descripcion, $precio = 0, $porciones_base = 100, $categoria_platillo_id, $servicio_gastronomico_id;
    public $ingredientesAgregados = [], $ingredienteSeleccionadoId, $cantidadTemporal, $unidadSeleccionada = '';

    protected $rules = [
        'nombre' => 'required|max:150',
        'categoria_platillo_id' => 'required',
        'servicio_gastronomico_id' => 'required',
        'porciones_base' => 'required|integer|min:1',
    ];

    public function updatedIngredienteSeleccionadoId($id) {
        $ing = Ingrediente::find($id);
        $this->unidadSeleccionada = $ing ? $ing->unidad : '';
    }

    public function agregarIngrediente() {
        $this->validate(['ingredienteSeleccionadoId' => 'required', 'cantidadTemporal' => 'required|numeric']);
        $ing = Ingrediente::find($this->ingredienteSeleccionadoId);
        
        $this->ingredientesAgregados[$ing->id] = [
            'id' => $ing->id,
            'nombre' => $ing->nombre,
            'unidad' => $ing->unidad,
            'cantidad' => $this->cantidadTemporal
        ];
        
        $this->reset(['ingredienteSeleccionadoId', 'cantidadTemporal', 'unidadSeleccionada']);
    }

    public function removerIngrediente($id) {
        unset($this->ingredientesAgregados[$id]);
    }

    public function guardarPlatillo() {
        $this->validate();
        $platillo = Platillo::create([
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion,
            'precio' => $this->precio,
            'porciones_base' => $this->porciones_base,
            'categoria_platillo_id' => $this->categoria_platillo_id,
            'servicio_gastronomico_id' => $this->servicio_gastronomico_id
        ]);

        $syncData = [];
        foreach ($this->ingredientesAgregados as $id => $info) {
            $syncData[$id] = ['cantidad_por_base' => $info['cantidad']];
        }
        $platillo->ingredientes()->sync($syncData);

        session()->flash('message', '¡Platillo y receta guardados correctamente!');
        $this->reset();
    }

    public function render() {
        return view('livewire.platillo-manager', [
            'categorias' => CategoriaPlatillo::all(),
            'servicios' => ServicioGastronomico::all(),
            'todosLosIngredientes' => Ingrediente::orderBy('nombre')->get()
        ]);
    }
}