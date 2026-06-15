<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Platillo;

class ComandaRapidaBuilder extends Component
{
    public $adultos = 0;
    public $ninos = 0;
    public $platillosSeleccionados = [];
    public $isExpanded = false;

    public function toggleExpanded()
    {
        $this->isExpanded = !$this->isExpanded;
    }

    public function generarComanda()
    {
        $this->validate([
            'adultos' => 'required|integer|min:0',
            'ninos' => 'required|integer|min:0',
            'platillosSeleccionados' => 'required|array|min:1',
        ]);

        $totalPersonas = $this->adultos + $this->ninos;

        if ($totalPersonas <= 0) {
            $this->addError('adultos', 'Debe haber al menos 1 persona en total.');
            return;
        }

        // Guardamos la info en sesión
        session(['comanda_rapida' => [
            'adultos' => $this->adultos,
            'ninos' => $this->ninos,
            'total' => $totalPersonas,
            'platillos_ids' => $this->platillosSeleccionados,
        ]]);

        // Redirigir a la vista de reporte
        return redirect()->route('reportes.comanda-rapida');
    }

    public function render()
    {
        // Traemos los platillos y los agrupamos por su categoría
        $platillosOptions = Platillo::with('categoriaPlatillo')->orderBy('nombre')->get();
        
        $platillosAgrupados = $platillosOptions->groupBy(function($platillo) {
            return $platillo->categoriaPlatillo ? $platillo->categoriaPlatillo->nombre : 'Otros';
        });

        // Ordenar las categorías para que Alimentos y Bebidas salgan primero si existen (opcional)
        $platillosAgrupados = $platillosAgrupados->sortKeys();

        return view('livewire.comanda-rapida-builder', [
            'platillosAgrupados' => $platillosAgrupados
        ]);
    }
}
