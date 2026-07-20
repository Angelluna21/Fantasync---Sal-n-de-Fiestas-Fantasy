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

        <!-- Encabezado Principal -->
        <header class="top-nav header-unified" style="padding: 2rem 2rem 0;">
            <a href="{{ route('dashboard') }}" style="display: flex; align-items: center; gap: 0.5rem; color: white; text-decoration: none; font-weight: bold; padding: 0.5rem 1rem; background: rgba(255,255,255,0.1); border-radius: 9999px; transition: background 0.3s;">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Dashboard
            </a>

            <hgroup class="header-titles" style="text-align: center; color: white;">
                <p class="eyebrow" style="color: rgba(255,255,255,0.8); margin: 0; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.1em;">Administración</p>
                <h1 class="dashboard-title" style="color: white; margin: 0; font-size: 2rem;">Bitácora de Actividades</h1>
            </hgroup>

            <nav aria-label="Opciones de usuario">
                <x-user-menu />
            </nav>
        </header>

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
                                <th>Detalles (JSON)</th>
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
