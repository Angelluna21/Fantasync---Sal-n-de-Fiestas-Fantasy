<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Platillo;
use App\Models\Salon;
use App\Models\Evento;

class ComandaRapidaBuilder extends Component
{
    public $adultos = 0;
    public $ninos = 0;
    public $nombreCliente = '';
    public $fechaEvento = '';
    public $telefono = '';
    public $salon_id = '';
    public $es_externo = false;
    public $platillosSeleccionados = [];
    public $isExpanded = false;

    public function toggleExpanded()
    {
        $this->isExpanded = !$this->isExpanded;
    }

    public function generarComanda()
    {
        $this->validate([
            'nombreCliente' => 'nullable|string|max:255',
            'fechaEvento' => 'required|date',
            'telefono' => 'nullable|string|max:20',
            'salon_id' => 'nullable|integer|exists:salones,id',
            'es_externo' => 'boolean',
            'adultos' => 'required|integer|min:0',
            'ninos' => 'required|integer|min:0',
            'platillosSeleccionados' => 'required|array|min:1',
        ]);

        $totalPersonas = $this->adultos + $this->ninos;

        if ($totalPersonas <= 0) {
            $this->addError('adultos', 'Debe haber al menos 1 persona en total.');
            return;
        }

        // Crear o buscar un cliente rápido para poder guardar el evento
        $cliente = \App\Models\Cliente::firstOrCreate(
            ['nombre_completo' => $this->nombreCliente ?: 'Cliente de Comanda Rápida'],
            ['celular' => $this->telefono]
        );

        // Crear el evento
        $evento = Evento::create([
            'titulo' => $this->nombreCliente ? ('Banquete: ' . $this->nombreCliente) : 'Banquete Independiente',
            'fecha' => $this->fechaEvento,
            'estado' => 'confirmado',
            'tipo_evento' => $this->es_externo ? 'banquete_externo' : 'banquete_interno',
            'hora_inicio' => '12:00:00', // Default
            'horas_duracion' => 5, // Default
            'cliente_id' => $cliente->id,
            'notas' => json_encode([
                'tipo' => 'comanda_rapida',
                'adultos' => $this->adultos,
                'ninos' => $this->ninos,
                'platillos' => $this->platillosSeleccionados
            ])
        ]);

        if ($this->salon_id) {
            $evento->salones()->attach($this->salon_id, [
                'adultos' => $this->adultos,
                'ninos' => $this->ninos,
                'factor_nino' => 0.5
            ]);
        }

        // Guardamos la info en sesión
        session(['comanda_rapida' => [
            'nombre_cliente' => $this->nombreCliente,
            'fecha_evento' => $this->fechaEvento,
            'telefono' => $this->telefono,
            'adultos' => $this->adultos,
            'ninos' => $this->ninos,
            'total' => $totalPersonas,
            'platillos_ids' => $this->platillosSeleccionados,
            'evento_id' => $evento->id,
            'salon_id' => $this->salon_id,
            'es_externo' => $this->es_externo
        ]]);

        // Redirigir a la vista de reporte
        return redirect()->route('reportes.comanda-rapida');
    }

    public function render()
    {
        // Traemos los platillos y los agrupamos por su categoría
        $platillosOptions = Platillo::with('categoriaPlatillo')->orderBy('nombre')->get();
        $salones = Salon::orderBy('nombre')->get();
        
        $platillosAgrupados = $platillosOptions->groupBy(function($platillo) {
            return $platillo->categoriaPlatillo ? $platillo->categoriaPlatillo->nombre : 'Otros';
        });

        // Ordenar las categorías para que Alimentos y Bebidas salgan primero si existen (opcional)
        $platillosAgrupados = $platillosAgrupados->sortKeys();

        return view('livewire.comanda-rapida-builder', [
            'platillosAgrupados' => $platillosAgrupados,
            'salones' => $salones
        ]);
    }
}
