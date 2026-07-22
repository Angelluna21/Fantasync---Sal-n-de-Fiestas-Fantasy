<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Módulo de Contratos · Fantasy</title>
    @vite(['resources/css/app.css', 'resources/css/dashboard.css', 'resources/css/eventos.css'])
</head>
<body>
    <figure class="dashboard-background" aria-hidden="true"></figure>
    
    <main class="dashboard-layout">
        <!-- Navegación superior y Encabezado Unificado -->
        <nav class="top-nav" style="align-items: flex-start; margin-bottom: 2rem; padding-bottom: 0;">
            <!-- Lado Izquierdo: Logo y Botón Volver -->
            <section style="display: flex; flex-direction: column; gap: 0.5rem; flex: 1;">
                <a href="{{ route('dashboard') }}" aria-label="Volver al panel" class="logo-link" style="width: fit-content;">
                    <img src="{{ asset('img/logo.png') }}" alt="Logo FantaSync" class="nav-logo" style="height: 100px;">
                </a>
                <a href="{{ route('dashboard') }}" class="btn-back-nav" style="width: fit-content; margin-bottom: 0; padding: 0.4rem 1rem; font-size: 0.85rem; background: rgba(255, 255, 255, 0.15); border: 1px solid rgba(255, 255, 255, 0.3); color: white;">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 16px; height: 16px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Volver al Panel
                </a>
            </section>

            <!-- Centro: Encabezado -->
            <header class="dashboard-header" style="margin: 3rem 0 0 0; flex: 2; display: flex; flex-direction: column; justify-content: center; max-width: none;">
                <hgroup>
                    <p class="eyebrow" style="margin-bottom: 0;">Gestión Legal</p>
                    <h1 class="dashboard-title" style="font-size: 2.5rem; margin-top: 0.2rem;">Módulo de Contratos</h1>
                    <p class="dashboard-description" style="margin: 0.5rem auto 0; font-size: 1.05rem;">Revisa, previsualiza en PDF y administra los contratos de todos tus eventos.</p>
                </hgroup>
            </header>

            <!-- Lado Derecho: Menú Usuario -->
            <section style="flex: 1; display: flex; justify-content: flex-end; padding-top: 15px;">
                <x-user-menu />
            </section>
        </nav>

        <!-- Sección de Indicadores (KPIs) -->
        <section class="metrics-grid" aria-label="Tarjetas de Indicadores de Contratos">
            <article class="metric-card total">
                <figure class="metric-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </figure>
                <hgroup class="metric-content">
                    <span class="metric-value">{{ $contratosActivos }}</span>
                    <span class="metric-label">Contratos Vigentes</span>
                </hgroup>
            </article>

            <article class="metric-card confirmados">
                <figure class="metric-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </figure>
                <hgroup class="metric-content">
                    <span class="metric-value">${{ number_format($totalContratado, 2) }}</span>
                    <span class="metric-label">Ingresos Contratados</span>
                </hgroup>
            </article>

            <article class="metric-card pendientes">
                <figure class="metric-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </figure>
                <hgroup class="metric-content">
                    <span class="metric-value">${{ number_format($saldoPendiente, 2) }}</span>
                    <span class="metric-label font-danger">Saldos por Cobrar</span>
                </hgroup>
            </article>
        </section>

        <section class="eventos-section">
            @if(session('success'))
                <aside class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert" style="margin-bottom: 2rem;">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </aside>
            @endif

            <header style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; gap: 1rem; flex-wrap: wrap;">
                <form action="{{ route('contratos.index') }}" method="GET" class="search-form" style="display: flex; gap: 1rem; flex: 1; min-width: 300px;">
                    <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Buscar por cliente o evento..." class="form-control" style="flex: 1; padding: 0.75rem 1rem; border-radius: 8px; border: 1px solid rgba(255,255,255,0.2); background: rgba(0,0,0,0.2); color: white;">
                    
                    <select name="month" class="form-control" style="width: auto; padding: 0.75rem 1rem; border-radius: 8px; border: 1px solid rgba(255,255,255,0.2); background: rgba(0,0,0,0.2); color: white;">
                        <option value="">Todos los meses</option>
                        @for($m=1; $m<=12; $m++)
                            <option value="{{ $m }}" {{ ($month ?? '') == $m ? 'selected' : '' }}>{{ \Carbon\Carbon::create()->month($m)->locale('es')->monthName }}</option>
                        @endfor
                    </select>

                    <select name="year" class="form-control" style="width: auto; padding: 0.75rem 1rem; border-radius: 8px; border: 1px solid rgba(255,255,255,0.2); background: rgba(0,0,0,0.2); color: white;">
                        <option value="">Todos los años</option>
                        @for($y=date('Y')-2; $y<=date('Y')+2; $y++)
                            <option value="{{ $y }}" {{ ($year ?? '') == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>

                    <button type="submit" class="btn-submit" style="width: auto; padding: 0.75rem 1.5rem;">Filtrar</button>
                </form>
                <a href="{{ route('contratos.crear', ['new' => 1]) }}" class="btn-submit" style="width: auto; padding: 0.75rem 1.5rem; text-decoration: none; display: inline-flex; align-items: center; justify-content: center; background: var(--accent-yellow); color: var(--primary-purple); font-weight: 800; border-radius: 8px; box-shadow: 0 4px 15px rgba(255, 213, 79, 0.4); transition: all 0.3s ease;">
                    + Nuevo Contrato
                </a>
            </header>

            <section class="table-wrapper">
                <table class="eventos-table">
                    <thead>
                        <tr>
                            <th>Contrato ID</th>
                            <th>Evento</th>
                            <th>Cliente</th>
                            <th>Monto Total</th>
                            <th>Estatus</th>
                            <th class="table-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($contratos as $contrato)
                            <tr>
                                <td>
                                    <span class="finance-muted">#CNT-{{ str_pad($contrato->id, 5, '0', STR_PAD_LEFT) }}</span>
                                    <p class="event-info-sub">{{ $contrato->created_at->format('d/m/Y') }}</p>
                                </td>
                                <td>
                                    <h3 class="event-info-name">{{ $contrato->evento->titulo }}</h3>
                                    <p class="event-info-sub">{{ $contrato->evento->fecha->format('d/m/Y') }}</p>
                                </td>
                                <td>
                                    @if($contrato->evento->cliente)
                                        <h3 class="event-info-name event-info-name-client">{{ $contrato->evento->cliente->nombre_completo }}</h3>
                                        <p class="event-info-sub">{{ $contrato->evento->cliente->celular }}</p>
                                    @endif
                                </td>
                                <td>
                                    <span class="finance-total">${{ number_format($contrato->monto_total, 2) }}</span>
                                </td>
                                <td>
                                    @php
                                        $estado = strtolower($contrato->evento->estado ?? 'cotizacion');
                                        $badgeClass = match($estado) {
                                            'cotizacion' => 'cotizacion',
                                            'confirmado' => 'confirmado',
                                            'cancelado' => 'cancelado',
                                            'finalizado' => 'finalizado',
                                            default => 'cotizacion'
                                        };
                                        $label = match($estado) {
                                            'finalizado' => 'Liquidado',
                                            default => ucfirst($estado)
                                        };
                                    @endphp
                                    <span class="event-badge {{ $badgeClass }}">{{ $label }}</span>
                                </td>
                                <td class="table-center">
                                    <menu class="actions-group" style="justify-content: center;">
                                        <a href="{{ route('contratos.show', $contrato->id) }}" target="_blank" class="btn-event-link" title="Ver PDF">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                            Ver PDF
                                        </a>

                                        <a href="{{ route('contratos.edit', $contrato->id) }}" class="btn-event-link" style="background: var(--accent-yellow); color: var(--primary-purple) !important; box-shadow: 0 4px 10px rgba(255, 213, 79, 0.4);" title="Editar Contrato">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                            Editar
                                        </a>

                                        <form action="{{ route('contratos.destroy', $contrato->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas anular este contrato? Se cancelará el evento.');" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-event-link generate" title="Anular Contrato" style="background: rgba(220, 38, 38, 0.1); color: #f87171; border-color: rgba(220, 38, 38, 0.2);">
                                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                Anular
                                            </button>
                                        </form>
                                    </menu>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="table-empty">
                                    No se encontraron contratos registrados.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <nav style="margin-top: 1rem;">
                    {{ $contratos->links() }}
                </nav>
            </section>
        </section>
    </main>

    <footer class="dashboard-footer eventos-footer">
        <p>© 2026 FantaSync · Módulo Legal</p>
    </footer>
</body>
</html>
