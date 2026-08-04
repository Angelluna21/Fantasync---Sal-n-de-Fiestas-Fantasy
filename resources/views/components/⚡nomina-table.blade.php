<?php

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Nomina;

new class extends Component {
    use WithPagination;

    public $search = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        $this->sortField = $field;
    }

    public function getBaseQueryProperty()
    {
        return Nomina::with('detalles.evento')
            ->where(function($q) {
                $q->where('nombre_empleado', 'like', '%' . $this->search . '%')
                  ->orWhereHas('detalles', function($dq) {
                      $dq->where('puesto', 'like', '%' . $this->search . '%');
                  });
            });
    }

    public function getNominasProperty()
    {
        return $this->baseQuery
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);
    }

    public function getOperacionTotalProperty()
    {
        return \App\Models\NominaDetalle::whereHas('nomina', function($q) {
            $q->where('nombre_empleado', 'like', '%' . $this->search . '%')
              ->orWhereHas('detalles', function($dq) {
                  $dq->where('puesto', 'like', '%' . $this->search . '%');
              });
        })->whereIn('puesto', ['Pista (meseros)', 'Dj', 'Puerta', 'Capitan de meseros', 'Barra', 'Nana'])->sum('subtotal');
    }

    public function getCocinaTotalProperty()
    {
        return \App\Models\NominaDetalle::whereHas('nomina', function($q) {
            $q->where('nombre_empleado', 'like', '%' . $this->search . '%')
              ->orWhereHas('detalles', function($dq) {
                  $dq->where('puesto', 'like', '%' . $this->search . '%');
              });
        })->whereIn('puesto', ['Cocinera', 'Auxiliar de cocina'])->sum('subtotal');
    }

    public function getOficinaTotalProperty()
    {
        return \App\Models\NominaDetalle::whereHas('nomina', function($q) {
            $q->where('nombre_empleado', 'like', '%' . $this->search . '%')
              ->orWhereHas('detalles', function($dq) {
                  $dq->where('puesto', 'like', '%' . $this->search . '%');
              });
        })->whereIn('puesto', ['Encargada', 'Oficina'])->sum('subtotal');
    }
};
?>

<section>
    <!-- KPIs -->
    <section class="metrics-grid metrics-grid-mb">
        <article class="metric-card total">
            <figure class="metric-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </figure>
            <hgroup class="metric-content">
                <span class="metric-value">{{ $this->baseQuery->count() }}</span>
                <span class="metric-label">Empleados</span>
            </hgroup>
        </article>

        <article class="metric-card monto">
            <figure class="metric-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </figure>
            <hgroup class="metric-content">
                <span class="metric-value">${{ number_format($this->baseQuery->sum('monto_total'), 2) }}</span>
                <span class="metric-label">Total a Pagar</span>
            </hgroup>
        </article>

        <article class="metric-card confirmados">
            <figure class="metric-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </figure>
            <hgroup class="metric-content">
                <span class="metric-value">${{ number_format($this->operacionTotal, 2) }}</span>
                <span class="metric-label">Operación</span>
            </hgroup>
        </article>

        <article class="metric-card pendientes">
            <figure class="metric-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </figure>
            <hgroup class="metric-content">
                <span class="metric-value">${{ number_format($this->cocinaTotal, 2) }}</span>
                <span class="metric-label">Cocina</span>
            </hgroup>
        </article>
        
        <article class="metric-card total">
            <figure class="metric-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
            </figure>
            <hgroup class="metric-content">
                <span class="metric-value">${{ number_format($this->oficinaTotal, 2) }}</span>
                <span class="metric-label">Oficina</span>
            </hgroup>
        </article>
    </section>

    <header class="header-nomina-search">
        <input wire:model.live="search" type="text" placeholder="Buscar empleado o puesto..." class="form-control input-nomina-search">
        <search class="search-nomina-actions">
            <a href="{{ route('nominas.reporte-pdf', ['search' => $search]) }}" target="_blank" class="btn-event-link btn-print-pdf">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="20"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Imprimir PDF
            </a>
            <a href="{{ route('nominas.create') }}" class="btn-event-link generate btn-nomina-action">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="20"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Dar de Alta Empleado
            </a>
        </search>
    </header>

    <section class="table-wrapper">
        <table class="eventos-table">
            <thead>
                <tr>
                    <th wire:click="sortBy('nombre_empleado')" class="th-sortable">Empleado @if($sortField === 'nombre_empleado') <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span> @endif</th>
                    <th>Puestos</th>
                    <th wire:click="sortBy('monto_total')" class="th-sortable">Total Recibo @if($sortField === 'monto_total') <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span> @endif</th>
                    <th>Eventos</th>
                    <th>Fechas</th>
                    <th wire:click="sortBy('estado_pago')" class="th-sortable">Estado @if($sortField === 'estado_pago') <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span> @endif</th>
                    <th class="table-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($this->nominas as $nomina)
                    <tr>
                        <td>
                            <h3 class="event-info-name">{{ $nomina->nombre_empleado }}</h3>
                        </td>
                        <td>
                            <span class="finance-muted">
                                {{ $nomina->detalles->pluck('puesto')->unique()->implode(', ') ?: 'N/A' }}
                            </span>
                        </td>
                        <td class="table-cell">
                            <span class="font-medium-price">${{ number_format($nomina->monto_total, 2) }}</span>
                            @php $totalExtras = $nomina->detalles->sum('horas_extra'); @endphp
                            @if($totalExtras > 0)
                                <span class="text-extras-green">(+{{ $totalExtras }} extras)</span>
                            @endif
                        </td>
                        <td>
                            @foreach($nomina->detalles->pluck('evento.titulo')->filter()->unique() as $evTitulo)
                                <span class="badge-sucursal" style="display:inline-block; margin-bottom:2px;">{{ $evTitulo }}</span>
                            @endforeach
                            @if($nomina->detalles->isEmpty()) <span class="badge-sucursal">N/A</span> @endif
                        </td>
                        <td>
                            <span class="event-info-sub">
                                {{ $nomina->detalles->pluck('fecha_trabajo')->map(fn($d) => \Carbon\Carbon::parse($d)->format('d/m'))->unique()->implode(', ') ?: 'N/A' }}
                            </span>
                        </td>
                        <td>
                            <span class="event-badge {{ strtolower($nomina->estado_pago) === 'pagado' ? 'confirmado' : (strtolower($nomina->estado_pago) === 'cancelado' ? 'cancelado' : 'cotizacion') }}">
                                {{ $nomina->estado_pago }}
                            </span>
                        </td>
                        <td class="table-center">
                            <menu class="actions-group">
                                <a href="{{ route('nominas.edit', $nomina) }}" class="btn-event-link">
                                    Editar
                                </a>
                                <form action="{{ route('nominas.destroy', $nomina) }}" method="POST" class="inline-block form-inline-m0" onsubmit="return confirm('¿Seguro que deseas eliminar este registro?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-event-link btn-text-danger">Eliminar</button>
                                </form>
                            </menu>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="table-empty">No se encontraron registros.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </section>

    <footer class="mt-4">
        {{ $this->nominas->links() }}
    </footer>
</section>