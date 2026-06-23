<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Bitácora de Accesos | FantaSync</title>
        @vite(['resources/css/app.css', 'resources/css/dashboard.css'])
    </head>
    <body class="dashboard-page">
        <!-- Fondo Abstracto Animado -->
        <figure class="dashboard-background" aria-hidden="true"></figure>

        <!-- Encabezado Principal -->
        <header class="dashboard-header">
            <menu class="header-content header-content-no-padding">
                <a href="{{ route('dashboard') }}" class="btn-back-dashboard">
                    <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Regresar al Dashboard
                </a>

                <x-user-menu />
            </menu>
        </header>

        <main role="main" class="main-logs">
            <section class="logs-container">
                <header class="logs-header">
                    <svg width="32" height="32" fill="none" stroke="var(--primary-purple)" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    <h2>Registro de Inicios de Sesión</h2>
                </header>

                <figure class="figure-logs">
                    <table class="logs-table">
                        <thead>
                            <tr>
                                <th>Usuario</th>
                                <th>Fecha y Hora</th>
                                <th>Dirección IP</th>
                                <th>Navegador / Dispositivo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($logs as $log)
                                <tr>
                                    <td>
                                        <span class="badge-user">{{ $log->user->name ?? 'Desconocido' }}</span>
                                    </td>
                                    <td>
                                        <strong>{{ $log->logged_in_at->format('d M Y') }}</strong><br>
                                        <small class="logs-small-text">{{ $log->logged_in_at->format('H:i:s') }}</small>
                                    </td>
                                    <td class="logs-monospace-text">{{ $log->ip_address ?? 'N/A' }}</td>
                                    <td class="logs-ellipsis-text" title="{{ $log->user_agent }}">
                                        {{ $log->user_agent ?? 'Desconocido' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="logs-empty-cell">
                                        <p>No hay registros de inicio de sesión aún.</p>
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

        
    </body>
</html>
