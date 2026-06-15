<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comanda de Cocina · FantaSync</title>
    @vite(['resources/css/app.css', 'resources/css/dashboard.css', 'resources/css/reportes.css'])
</head>
<body>
    <figure class="dashboard-background" aria-hidden="true"></figure>
    
    <main class="dashboard-layout">
        <nav class="top-nav" aria-label="Menú superior">
            <a href="{{ route('dashboard') }}" aria-label="Volver al panel" class="logo-link">
                <img src="{{ asset('img/logo.png') }}" alt="Logo FantaSync" class="nav-logo">
            </a>
            <x-user-menu />
        </nav>

        <nav style="max-width: 1200px; margin: 0 auto; width: 100%;">
            <a href="{{ route('dashboard') }}" class="btn-back-nav" style="background: rgba(255, 255, 255, 0.15); color: #ffffff; border: 1px solid rgba(255, 255, 255, 0.25); backdrop-filter: blur(4px); padding: 0.5rem 1.15rem; font-size: 0.9rem; margin-bottom: 0.5rem; transition: all 0.3s; text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem; border-radius: 2rem;">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 18px; height: 18px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Volver al Panel
            </a>
        </nav>

        <header class="dashboard-header">
            <hgroup>
                <p class="eyebrow">🍳 Orden de Cocina</p>
                <h1 class="dashboard-title">Comanda Global del Evento</h1>
                <p class="dashboard-description">Evento: <strong>{{ $contrato->evento->titulo }}</strong> (Festejado: {{ $contrato->evento->nombre_festejado }})</p>
            </hgroup>
        </header>

        <section class="reportes-section" style="max-width: 1200px; margin: 0 auto; width: 100%;">
            @if($comandaGlobal->isEmpty())
                <article class="sucursal-card" style="padding: 2rem; text-align: center;">
                    <p style="color: #6c757d; font-size: 1.1rem;">Este contrato aún no tiene platillos asignados en la comanda.</p>
                </article>
            @else
                @foreach($comandaGlobal as $categoria => $platillos)
                    <article class="sucursal-card" style="padding: 2rem; margin-bottom: 2rem;">
                        <header class="card-header" style="margin-bottom: 1.5rem; border-bottom: 2px solid var(--accent-yellow); padding-bottom: 0.5rem;">
                            <h2 class="card-title" style="color: var(--accent-magenta);">{{ $categoria }}</h2>
                        </header>

                        <table class="tabla-reporte">
                            <thead>
                                <tr>
                                    <th style="width: 40%;">Platillo</th>
                                    <th style="width: 20%;">Total Porciones</th>
                                    <th style="width: 40%;">Distribución por Salón</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($platillos as $platillo)
                                    <tr>
                                        <td>
                                            <strong style="font-size: 1.1rem;">{{ $platillo['nombre'] }}</strong>
                                        </td>
                                        <td style="font-weight: bold; font-size: 1.2rem; color: var(--primary-purple);">
                                            {{ $platillo['porciones_totales'] }}
                                        </td>
                                        <td>
                                            <ul style="list-style: none; padding: 0; margin: 0;">
                                                @foreach($platillo['salones'] as $salon)
                                                    <li style="margin-bottom: 0.5rem; background: rgba(0,0,0,0.03); padding: 0.5rem; border-radius: 6px;">
                                                        <strong>{{ $salon['nombre'] }}:</strong> {{ $salon['porciones'] }} porciones
                                                        @if($salon['notas'])
                                                            <br><small style="color: #d9534f;"><em>Nota: {{ $salon['notas'] }}</em></small>
                                                        @endif
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </article>
                @endforeach
            @endif

            <!-- Botón para ver los insumos a comprar para esta comanda -->
            <footer style="display: flex; justify-content: space-between; margin-top: 3rem;">
                <button class="btn-print" onclick="window.print();" style="background: var(--primary-purple); color: white; border: none; padding: 0.8rem 1.5rem; border-radius: 2rem; font-weight: bold; cursor: pointer; display: flex; align-items: center; transition: background 0.3s;">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="20" height="20" style="margin-right: 0.5rem;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                    </svg>
                    Imprimir Comanda
                </button>

                <a href="{{ route('reportes.insumos', $contrato->evento->id) }}" style="background: var(--accent-magenta); color: white; text-decoration: none; padding: 0.8rem 1.5rem; border-radius: 2rem; font-weight: bold; display: flex; align-items: center;">
                    Ver Insumos Necesarios
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="20" height="20" style="margin-left: 0.5rem;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </footer>
        </section>
    </main>
</body>
</html>
