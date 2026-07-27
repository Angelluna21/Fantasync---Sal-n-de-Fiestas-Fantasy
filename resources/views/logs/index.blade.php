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
                    <h1 class="dashboard-title" style="font-size: 2.8rem; margin-top: 0.2rem; text-align: center; color: white;">Bitácora de Accesos</h1>
                    <p class="dashboard-description" style="margin: 0.5rem auto 0; font-size: 1.1rem; text-align: center; color: rgba(255,255,255,0.9);">Consulta el historial de inicios de sesión y direcciones IP de los usuarios.</p>
                </hgroup>
            </header>

            <!-- Lado Derecho: Menú Usuario -->
            <section style="flex: 1; display: flex; justify-content: flex-end; align-items: flex-start; padding-top: 15px;">
                <x-user-menu />
            </section>
        </section>

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
