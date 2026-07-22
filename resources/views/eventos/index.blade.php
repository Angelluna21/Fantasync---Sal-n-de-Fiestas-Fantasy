<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard de Dirección · FantaSync</title>
    @vite(['resources/css/app.css', 'resources/css/dashboard.css', 'resources/css/eventos.css'])
</head>
<body>
    <figure class="dashboard-background" aria-hidden="true"></figure>
    
    <main class="dashboard-layout">
        <!-- Navegación superior y Encabezado Unificado -->
        <div class="top-nav" style="align-items: flex-start; margin-bottom: 2rem; padding-bottom: 0;">
            <!-- Lado Izquierdo: Logo y Botón Volver -->
            <div style="display: flex; flex-direction: column; gap: 0.5rem; flex: 1;">
                <a href="{{ route('dashboard') }}" aria-label="Volver al panel" class="logo-link" style="width: fit-content;">
                    <img src="{{ asset('img/logo.png') }}" alt="Logo FantaSync" class="nav-logo" style="height: 100px;">
                </a>
                <a href="{{ route('dashboard') }}" class="btn-back-nav" style="width: fit-content; margin-bottom: 0; padding: 0.4rem 1rem; font-size: 0.85rem; background: rgba(255, 255, 255, 0.15); border: 1px solid rgba(255, 255, 255, 0.3); color: white;">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Volver al Panel
                </a>
            </div>

            <!-- Centro: Encabezado -->
            <header class="dashboard-header" style="margin: 3rem 0 0 0; flex: 2; display: flex; flex-direction: column; justify-content: center; max-width: none;">
                <hgroup>
                    <p class="eyebrow" style="margin-bottom: 0;">Dirección General</p>
                    <h1 class="dashboard-title" style="font-size: 2.5rem; margin-top: 0.2rem;">Eventos</h1>
                    <p class="dashboard-description" style="margin: 0.5rem auto 0; font-size: 1.05rem;">Supervisa la agenda de eventos, el estado de las cotizaciones y el flujo financiero de los contratos vigentes.</p>
                </hgroup>
            </header>

            <!-- Lado Derecho: Menú Usuario -->
            <div style="flex: 1; display: flex; justify-content: flex-end; padding-top: 15px;">
                <x-user-menu />
            </div>
        </div>

        <!-- Sección de Indicadores (KPIs) -->
        <section class="metrics-grid" aria-label="Tarjetas de Indicadores Financieros y Operativos" style="margin-top: 7rem;">
            <!-- Total de Eventos -->
            <article class="metric-card total">
                <figure class="metric-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </figure>
                <hgroup class="metric-content">
                    <span class="metric-value">{{ $totalEventos }}</span>
                    <span class="metric-label">Eventos Agendados</span>
                </hgroup>
            </article>

            <!-- Eventos Confirmados -->
            <article class="metric-card confirmados">
                <figure class="metric-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </figure>
                <hgroup class="metric-content">
                    <span class="metric-value">
                        {{ $confirmados }}
                        <span class="metric-percent">({{ $totalEventos > 0 ? round(($confirmados / $totalEventos) * 100) : 0 }}%)</span>
                    </span>
                    <span class="metric-label">Confirmados</span>
                </hgroup>
            </article>

            <!-- Monto Total Contratado -->
            <article class="metric-card monto">
                <figure class="metric-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </figure>
                <hgroup class="metric-content">
                    <span class="metric-value">${{ number_format($montoTotal, 2) }}</span>
                    <span class="metric-label">Ingresos Contratados</span>
                </hgroup>
            </article>

            <!-- Saldos Pendientes -->
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

        <!-- Tabla de Eventos -->
        <section class="eventos-section" aria-label="Tabla de Gestión Administrativa">
            <h2 class="section-title eventos-table-title">
                Control de Agenda y Finanzas
            </h2>

            <section class="table-wrapper">
                <table class="eventos-table">
                    <thead>
                        <tr>
                            <th>Evento / Celebración</th>
                            <th>Cliente</th>
                            <th>Ubicación</th>
                            <th>Estado</th>
                            <th>Monto Total</th>
                            <th>Saldo Pendiente</th>
                            <th class="table-center">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($eventos as $evento)
                            <tr>
                                <!-- Evento / Celebración -->
                                <td>
                                    <h3 class="event-info-name">{{ $evento->titulo }}</h3>
                                    <p class="event-info-sub">
                                        {{ $evento->fecha->format('d/m/Y') }} · {{ $evento->hora_inicio ? \Carbon\Carbon::parse($evento->hora_inicio)->format('H:i') : 'N/A' }} hrs
                                    </p>
                                </td>

                                <!-- Cliente -->
                                <td>
                                    @if($evento->cliente)
                                        <h3 class="event-info-name event-info-name-client">{{ $evento->cliente->nombre_completo }}</h3>
                                        <p class="event-info-sub">{{ $evento->cliente->celular }}</p>
                                    @else
                                        <span class="finance-muted">No especificado</span>
                                    @endif
                                </td>

                                <!-- Ubicación -->
                                <td>
                                    @if($evento->salones->count() > 0)
                                        @foreach($evento->salones as $salon)
                                            <span class="badge-sucursal">
                                                {{ $salon->nombre }} 
                                                @if($salon->sucursal)
                                                    <span class="badge-sucursal-sub">({{ $salon->sucursal->nombre }})</span>
                                                @endif
                                            </span>
                                        @endforeach
                                    @else
                                        <span class="finance-muted">Sin salón asignado</span>
                                    @endif
                                </td>

                                <!-- Estado -->
                                <td>
                                    <span class="event-badge {{ $evento->estado }}">
                                        @if($evento->estado === 'cotizacion')
                                            Cotización
                                        @elseif($evento->estado === 'confirmado')
                                            Confirmado
                                        @elseif($evento->estado === 'finalizado')
                                            Finalizado
                                        @elseif($evento->estado === 'cancelado')
                                            Cancelado
                                        @else
                                            {{ $evento->estado }}
                                        @endif
                                    </span>
                                </td>

                                <!-- Monto Total -->
                                <td>
                                    @if($evento->contrato)
                                        <span class="finance-total">${{ number_format($evento->contrato->monto_total, 2) }}</span>
                                    @else
                                        <span class="finance-muted">S/C (Ficha Preliminar)</span>
                                    @endif
                                </td>

                                <!-- Saldo Pendiente -->
                                <td>
                                    @if($evento->contrato)
                                        @if($evento->contrato->saldo_pendiente > 0)
                                            <span class="finance-pending">${{ number_format($evento->contrato->saldo_pendiente, 2) }}</span>
                                        @else
                                            <span class="finance-positive">Pagado</span>
                                        @endif
                                    @else
                                        <span class="finance-muted">-</span>
                                    @endif
                                </td>

                                <!-- Acciones -->
                                <td class="table-center">
                                    <menu class="actions-group">
                                        @if($evento->salones->count() > 0)
                                            <a href="{{ route('salones.show', $evento->salones->first()->id) }}" class="btn-event-link" title="Ver en Calendario">
                                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                Ver Agenda
                                            </a>
                                        @endif

                                        @if($evento->contrato)
                                            <a href="{{ route('contratos.show', $evento->contrato->id) }}" class="btn-event-link" title="Ver Contrato / Cotización">
                                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                Ver Contrato
                                            </a>
                                            
                                            <a href="{{ route('reportes.insumos', $evento->id) }}" class="btn-event-link" title="Reporte de Insumos / Lista de Compras">
                                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                                Insumos
                                            </a>
                                        @else
                                            <a href="{{ route('contratos.crear', ['new' => 1]) }}" class="btn-event-link generate" title="Generar Contrato">
                                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                Generar Contrato
                                            </a>
                                        @endif
                                        
                                        <!-- Botón Eliminar Evento -->
                                        <form action="{{ route('eventos.destroy', $evento->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Estás seguro de querer eliminar este evento? Esta acción no se puede deshacer.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-event-link delete" title="Eliminar Evento">
                                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                Eliminar
                                            </button>
                                        </form>
                                    </menu>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="table-empty">
                                    No hay eventos registrados en la agenda de dirección.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </section>
        </section>
    </main>

    <!-- Footer -->
    <footer class="dashboard-footer eventos-footer">
        <p>© 2026 FantaSync · Dashboard Administrativo de Operaciones Gastronómicas</p>
    </footer>
</body>
</html>
