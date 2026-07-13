<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Categoria;

class CategoriaManager extends Component
{
    public $nombre;
    public $orden;
    public $categoria_id; 
    public $search = '';
    public $formVisible = false; 

    protected $rules = [
        'nombre' => 'required|string|max:60',
        'orden' => 'required|numeric',
    ];

   public function render()
    {
        // Cambiamos a orderBy('orden', 'asc') para que respete tu numeración
        $categorias = Categoria::where('nombre', 'like', '%' . $this->search . '%')
            ->orderBy('orden', 'asc')
            ->get();

        return view('livewire.categoria-manager', compact('categorias'));
    }

    public function guardarCategoria()
    {
        $this->validate();

        Categoria::updateOrCreate(
            ['id' => $this->categoria_id],
            [
                'nombre' => $this->nombre,
                'orden' => $this->orden,
            ]
        );

        session()->flash('message', $this->categoria_id ? 'Categoría actualizada.' : 'Categoría creada.');
        
        $this->reset(['nombre', 'orden', 'categoria_id']);
        
        // Se cierra automáticamente el formulario al terminar de guardar
        $this->formVisible = false; 
    }

    public function edit($id)
    {
        $categoria = Categoria::findOrFail($id);
        $this->categoria_id = $id;
        $this->nombre = $categoria->nombre;
        $this->orden = $categoria->orden;
        
        // Se abre automáticamente el formulario para poder editar los datos
        $this->formVisible = true; 
    }

    public function delete($id)
    {
        Categoria::find($id)->delete();
        session()->flash('message', 'Categoría eliminada.');
        
        // Por seguridad, si el usuario estaba editando esa categoría y la elimina, cerramos el formulario
        if ($this->categoria_id == $id) {
            $this->reset(['nombre', 'orden', 'categoria_id']);
            $this->formVisible = false;
        }
    }
}