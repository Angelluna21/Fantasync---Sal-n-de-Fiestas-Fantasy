<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Bitácora de Actividades | FantaSync</title>
        @vite(['resources/css/app.css', 'resources/css/dashboard.css'])
        <style>
            .log-created { color: var(--finance-positive); }
            .log-updated { color: var(--finance-pending); }
            .log-deleted { color: var(--finance-negative); }
            .badge-action {
                display: inline-block;
                padding: 4px 10px;
                border-radius: 9999px;
                font-size: 0.75rem;
                font-weight: 600;
                text-transform: uppercase;
                letter-spacing: 0.05em;
            }
            .badge-action.created { background-color: rgba(34, 197, 94, 0.1); color: #22c55e; }
            .badge-action.updated { background-color: rgba(234, 179, 8, 0.1); color: #eab308; }
            .badge-action.deleted { background-color: rgba(239, 68, 68, 0.1); color: #ef4444; }
            .model-name {
                font-weight: 600;
                color: var(--text-color);
            }
        </style>
    </head>
    <body class="dashboard-page">
        <!-- Fondo Abstracto Animado -->
        <figure class="dashboard-background" aria-hidden="true"></figure>

        <!-- Navegación superior y Encabezado Unificado -->
        <section class="top-nav" aria-label="Menú superior" style="align-items: flex-start; margin-bottom: 2rem; padding: 2rem 2rem 0; width: 100%; box-sizing: border-box;">
            <!-- Lado Izquierdo: Logo y Botón Volver -->
            <section style="display: flex; flex-direction: column; gap: 0.5rem; flex: 1; align-items: flex-start;">
                <a href="{{ route('dashboard') }}" aria-label="Volver al panel" class="logo-link" style="width: fit-content;">
                    <img src="{{ asset('img/logo.png') }}" alt="Logo FantaSync" class="nav-logo" style="height: 100px;">
                </a>
                <a href="{{ route('dashboard') }}" class="btn-back-nav" style="width: fit-content; margin-bottom: 0; padding: 0.4rem 1rem; font-size: 0.85rem; background: rgba(255, 255, 255, 0.15); border: 1px solid rgba(255, 255, 255, 0.3); color: white; display: inline-flex; align-items: center; gap: 0.5rem; border-radius: 8px; text-decoration: none; transition: all 0.3s;">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 16px; height: 16px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Volver al Panel
                </a>
            </section>

            <!-- Centro: Encabezado -->
            <header class="dashboard-header" style="margin: 2rem 0 0 0; flex: 2; display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center; max-width: none;">
                <hgroup style="text-align: center;">
                    <p class="eyebrow" style="margin-bottom: 0; text-align: center; color: rgba(255,255,255,0.8); font-size: 0.95rem; text-transform: uppercase; letter-spacing: 0.1em;">Administración</p>
                    <h1 class="dashboard-title" style="font-size: 2.8rem; margin-top: 0.2rem; text-align: center; color: white;">Bitácora de Actividades</h1>
                    <p class="dashboard-description" style="margin: 0.5rem auto 0; font-size: 1.1rem; text-align: center; color: rgba(255,255,255,0.9);">Supervisa y audita en tiempo real todos los movimientos y cambios del sistema.</p>
                </hgroup>
            </header>

            <!-- Lado Derecho: Menú Usuario -->
            <section style="flex: 1; display: flex; justify-content: flex-end; align-items: flex-start; padding-top: 15px;">
                <x-user-menu />
            </section>
        </section>

        <main role="main" class="main-logs">
            <section class="logs-container" style="max-width: 1000px;">
                <header class="logs-header" style="display: flex; align-items: center; justify-content: center; gap: 1rem; margin-bottom: 2rem; border-bottom: 2px solid var(--border-color); padding-bottom: 1rem;">
                    <span style="background: rgba(122, 40, 138, 0.1); padding: 12px; border-radius: 50%; display: flex; color: var(--primary-purple);">
                        <svg width="28" height="28" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </span>
                    <h2 style="margin: 0; color: var(--primary-purple); font-size: 1.5rem;">Historial de Cambios del Sistema</h2>
                </header>

                <figure class="figure-logs">
                    <table class="logs-table">
                        <thead>
                            <tr>
                                <th>Usuario</th>
                                <th>Acción</th>
                                <th>Registro Modificado</th>
                                <th>Detalle</th>
                                <th>Fecha y Hora</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($logs as $log)
                                <tr>
                                    <td>
                                        <span class="badge-user">{{ $log->user->name ?? 'Desconocido' }}</span>
                                    </td>
                                    <td>
                                        <span class="badge-action {{ $log->action }}">
                                            @if($log->action == 'created') Creado
                                            @elseif($log->action == 'updated') Actualizado
                                            @elseif($log->action == 'deleted') Eliminado
                                            @else {{ $log->action }} @endif
                                        </span>
                                    </td>
                                    <td>
                                        @php
                                            $modelClass = class_basename($log->model_type);
                                        @endphp
                                        <span class="model-name">{{ $modelClass }} #{{ $log->model_id }}</span>
                                    </td>
                                    <td class="logs-monospace-text" style="font-size: 0.75rem; max-width: 200px; overflow-x: auto; white-space: nowrap;">
                                        @if($log->changes)
                                            <details>
                                                <summary style="cursor: pointer; color: var(--primary-purple);">Ver Cambios</summary>
                                                <pre style="text-align: left; margin-top: 5px;">{{ json_encode($log->changes, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                            </details>
                                        @else
                                            <span style="color: var(--text-muted);">Sin detalles</span>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ $log->created_at->format('d M Y') }}</strong><br>
                                        <small class="logs-small-text">{{ $log->created_at->format('H:i:s') }}</small>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="logs-empty-cell">
                                        <p>No hay registros de actividad aún.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </figure>

                @if($logs->hasPages())
                    <nav class="pagination-container" aria-label="Navegación de páginas">
                        {{ $logs->links() }}
                    </nav>
                @endif
            </section>
        </main>

        <!-- Pie de página -->
        <footer class="dashboard-footer">
            <p>© 2026 FantaSync · Sistema de Gestión de Eventos Gastronómicos</p>
        </footer>
    </body>
</html>
