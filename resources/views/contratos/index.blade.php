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
                    
                    <select name="periodo" class="form-control" style="width: auto; padding: 0.75rem 1rem; border-radius: 8px; border: 1px solid rgba(255,255,255,0.2); background: rgba(0,0,0,0.2); color: white;" title="Filtro rápido">
                        <option value="">Cualquier fecha</option>
                        <option value="semana" {{ ($periodo ?? '') == 'semana' ? 'selected' : '' }}>Esta Semana</option>
                        <option value="mes" {{ ($periodo ?? '') == 'mes' ? 'selected' : '' }}>Este Mes</option>
                        <option value="anio" {{ ($periodo ?? '') == 'anio' ? 'selected' : '' }}>Este Año</option>
                    </select>

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
                    @if(!empty($search) || !empty($month) || !empty($year) || !empty($periodo))
                        <a href="{{ route('contratos.index') }}" class="btn-submit" style="width: auto; padding: 0.75rem 1.5rem; text-decoration: none; background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.3); color: white; box-shadow: none;">Ver Todos</a>
                    @endif
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
                                    <menu class="actions-group" style="display: flex; flex-direction: row; gap: 0.5rem; justify-content: center; align-items: center; margin: 0; padding: 0; list-style: none;">
                                        
                                        <a href="{{ route('eventos.menu', $contrato->evento_id) }}" class="btn-event-link" title="Pedido de Platillos" style="display: inline-flex; align-items: center; justify-content: center; padding: 0.3rem 0.5rem; border-radius: 6px; font-size: 0.75rem; font-weight: 600; text-decoration: none; background: #00bcd4; color: white; border: none; box-shadow: 0 4px 10px rgba(0, 188, 212, 0.3); gap: 0.2rem; transition: all 0.2s;">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 14px; height: 14px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                                            Platillos
                                        </a>
                                        
                                        <a href="{{ route('reportes.insumos', $contrato->evento_id) }}" target="_blank" class="btn-event-link" title="Reporte de Insumos" style="display: inline-flex; align-items: center; justify-content: center; padding: 0.3rem 0.5rem; border-radius: 6px; font-size: 0.75rem; font-weight: 600; text-decoration: none; background: #4caf50; color: white; border: none; box-shadow: 0 4px 10px rgba(76, 175, 80, 0.3); gap: 0.2rem; transition: all 0.2s;">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 14px; height: 14px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                                            Insumos
                                        </a>
                                        
                                        <a href="{{ route('contratos.show', $contrato->id) }}" class="btn-event-link" title="Consultar Contrato" style="display: inline-flex; align-items: center; justify-content: center; padding: 0.3rem 0.5rem; border-radius: 6px; font-size: 0.75rem; font-weight: 600; text-decoration: none; background: #9b30b0; color: white; border: none; box-shadow: 0 4px 10px rgba(155, 48, 176, 0.3); gap: 0.2rem; transition: all 0.2s;">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 14px; height: 14px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                            Consultar
                                        </a>

                                        <a href="{{ route('contratos.pdf', $contrato->id) }}" target="_blank" class="btn-event-link" title="Imprimir PDF" style="display: inline-flex; align-items: center; justify-content: center; padding: 0.4rem; border-radius: 6px; text-decoration: none; background: #6a4a75; color: white; border: none; box-shadow: 0 4px 10px rgba(106, 74, 117, 0.3); transition: all 0.2s;">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 16px; height: 16px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                        </a>

                                        <a href="{{ route('contratos.edit', $contrato->id) }}" class="btn-event-link" style="display: inline-flex; align-items: center; justify-content: center; padding: 0.3rem 0.5rem; border-radius: 6px; font-size: 0.75rem; font-weight: 600; text-decoration: none; background: var(--accent-yellow); color: var(--primary-purple); border: none; box-shadow: 0 4px 10px rgba(255, 213, 79, 0.4); gap: 0.2rem; transition: all 0.2s;" title="Editar Contrato">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 14px; height: 14px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                            Editar
                                        </a>

                                        <form action="{{ route('contratos.destroy', $contrato->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas anular este contrato? Se cancelará el evento.');" style="display: inline-flex; margin: 0;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-event-link" title="Anular Contrato" style="display: inline-flex; align-items: center; justify-content: center; padding: 0.3rem; border-radius: 6px; border: none; background: #ef4444; color: white; box-shadow: 0 4px 10px rgba(239, 68, 68, 0.3); cursor: pointer; transition: all 0.2s;">
                                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 16px; height: 16px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
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
