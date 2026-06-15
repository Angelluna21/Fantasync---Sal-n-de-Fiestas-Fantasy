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
        <!-- Navegación superior -->
        <nav class="top-nav" aria-label="Menú superior">
            <a href="{{ route('dashboard') }}" aria-label="Volver al panel" class="logo-link">
                <img src="{{ asset('img/logo.png') }}" alt="Logo FantaSync" class="nav-logo">
            </a>

            <x-user-menu />
        </nav>

        <!-- Volver al Panel -->
        <nav style="max-width: 1200px; margin: 0 auto; width: 100%;">
            <a href="{{ route('dashboard') }}" class="btn-back-nav">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Volver al Panel
            </a>
        </nav>

        <!-- Encabezado -->
        <header class="dashboard-header">
            <hgroup>
                <p class="eyebrow">Dirección General</p>
                <h1 class="dashboard-title">Dashboard de Dirección</h1>
                <p class="dashboard-description">Supervisa la agenda de eventos, el estado de las cotizaciones y el flujo financiero de los contratos vigentes.</p>
            </hgroup>
        </header>

        <!-- Sección de Indicadores (KPIs) -->
        <section class="metrics-grid" aria-label="Tarjetas de Indicadores Financieros y Operativos">
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
                        <span style="font-size: 0.9rem; font-weight: 700; color: var(--text-muted);">({{ $totalEventos > 0 ? round(($confirmados / $totalEventos) * 100) : 0 }}%)</span>
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
            <h2 class="section-title" style="margin-bottom: 1rem; color: var(--primary-purple); font-weight: 800; font-size: 1.2rem;">
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
                            <th style="text-align: center;">Acción</th>
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
                                        <h3 class="event-info-name" style="font-size: 0.95rem; font-weight: 700;">{{ $evento->cliente->nombre_completo }}</h3>
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
                                                    <span style="font-weight: 500; font-size: 0.75rem; opacity: 0.85;">({{ $salon->sucursal->nombre }})</span>
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
                                <td style="text-align: center;">
                                    <menu class="actions-group">
                                        @if($evento->salones->count() > 0)
                                            <a href="{{ route('salones.show', $evento->salones->first()->id) }}" class="btn-event-link" title="Ver en Calendario">
                                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                Ver Agenda
                                            </a>
                                        @endif

                                        @if($evento->contrato)
                                            <a href="{{ route('contrato.demo', ['id' => $evento->contrato->id]) }}" class="btn-event-link" title="Ver Contrato / Cotización">
                                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                Ver Contrato
                                            </a>
                                        @else
                                            <a href="{{ route('contratos.crear', ['new' => 1]) }}" class="btn-event-link generate" title="Generar Contrato">
                                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                Generar Contrato
                                            </a>
                                        @endif
                                    </menu>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" style="text-align: center; padding: 3rem 1.5rem; color: var(--text-muted); font-weight: 700;">
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
    <footer class="dashboard-footer" style="margin-top: 4rem;">
        <p>© 2026 FantaSync · Dashboard Administrativo de Operaciones Gastronómicas</p>
    </footer>
</body>
</html>
