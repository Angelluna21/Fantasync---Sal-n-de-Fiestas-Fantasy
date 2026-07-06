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
        return Nomina::with('evento')
            ->where(function($q) {
                $q->where('nombre_empleado', 'like', '%' . $this->search . '%')
                  ->orWhere('puesto', 'like', '%' . $this->search . '%');
            });
    }

    public function getNominasProperty()
    {
        return $this->baseQuery
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);
    }
};
?>

<div>
    <!-- KPIs -->
    <section class="metrics-grid" style="margin-bottom: 2rem;">
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
                <span class="metric-value">${{ number_format((clone $this->baseQuery)->where('estado_pago', 'Pagado')->sum('monto_total'), 2) }}</span>
                <span class="metric-label">Nóminas Pagadas</span>
            </hgroup>
        </article>

        <article class="metric-card pendientes">
            <figure class="metric-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            </figure>
            <hgroup class="metric-content">
                <span class="metric-value">${{ number_format((clone $this->baseQuery)->where('estado_pago', 'Pendiente')->sum('monto_total'), 2) }}</span>
                <span class="metric-label font-danger">Saldo Pendiente</span>
            </hgroup>
        </article>
    </section>

    <div class="mb-4 flex justify-between items-center" style="margin-bottom: 20px;">
        <input wire:model.live="search" type="text" placeholder="Buscar empleado o puesto..." class="form-control" style="width: 300px;">
        <div style="display: flex; gap: 10px;">
            <a href="{{ route('nominas.reporte-pdf', ['search' => $search]) }}" target="_blank" class="btn-event-link" style="display:inline-flex; align-items:center; gap:8px; background: #e5e7eb; color: #111827 !important; font-weight: bold;">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="20"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Imprimir PDF
            </a>
            <a href="{{ route('nominas.create') }}" class="btn-event-link generate" style="display:inline-flex; align-items:center; gap:8px;">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="20"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Dar de Alta Empleado
            </a>
        </div>
    </div>

    <section class="table-wrapper">
        <table class="eventos-table">
            <thead>
                <tr>
                    <th wire:click="sortBy('nombre_empleado')" style="cursor:pointer;">Empleado @if($sortField === 'nombre_empleado') <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span> @endif</th>
                    <th wire:click="sortBy('puesto')" style="cursor:pointer;">Puesto @if($sortField === 'puesto') <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span> @endif</th>
                    <th wire:click="sortBy('salario_base')" style="cursor:pointer;">Salario @if($sortField === 'salario_base') <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span> @endif</th>
                    <th>Evento</th>
                    <th wire:click="sortBy('fecha_trabajo')" style="cursor:pointer;">Fecha @if($sortField === 'fecha_trabajo') <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span> @endif</th>
                    <th wire:click="sortBy('estado_pago')" style="cursor:pointer;">Estado @if($sortField === 'estado_pago') <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span> @endif</th>
                    <th class="table-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($this->nominas as $nomina)
                    <tr>
                        <td>
                            <h3 class="event-info-name">{{ $nomina->nombre_empleado }}</h3>
                        </td>
                        <td><span class="finance-muted">{{ $nomina->puesto }}</span></td>
                        <td class="table-cell">
                            <span style="font-weight: 500;">${{ number_format($nomina->monto_total, 2) }}</span>
                            @if($nomina->horas_extra > 0)
                                <span style="font-size: 0.75rem; color: #059669; display: block;">(+{{ $nomina->horas_extra }} extras)</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge-sucursal">{{ $nomina->evento->titulo ?? 'N/A' }}</span>
                        </td>
                        <td><span class="event-info-sub">{{ \Carbon\Carbon::parse($nomina->fecha_trabajo)->format('d/m/Y') }}</span></td>
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
                                <form action="{{ route('nominas.destroy', $nomina) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Seguro que deseas eliminar este registro?');" style="margin:0;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-event-link" style="color: #ef4444;">Eliminar</button>
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

    <div class="mt-4">
        {{ $this->nominas->links() }}
    </div>
</div>