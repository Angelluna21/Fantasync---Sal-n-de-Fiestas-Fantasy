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

        <nav class="navigation-buttons">
            <a href="{{ route('dashboard') }}" class="btn-back-nav-glass">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="nav-icon"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
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

        <section class="reportes-section">
            @if($comandaGlobal->isEmpty())
                <article class="sucursal-card comanda-card-empty">
                    <p class="comanda-empty-text">Este contrato aún no tiene platillos asignados en la comanda.</p>
                </article>
            @else
                @foreach($comandaGlobal as $categoria => $platillos)
                    <article class="sucursal-card comanda-category-card">
                        <header class="card-header comanda-card-header">
                            <h2 class="card-title comanda-category-title">{{ $categoria }}</h2>
                        </header>

                        <table class="tabla-reporte">
                            <thead>
                                <tr>
                                    <th class="comanda-table-th-platillo">Platillo</th>
                                    <th class="comanda-table-th-total">Total Porciones</th>
                                    <th class="comanda-table-th-dist">Distribución por Salón</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($platillos as $platillo)
                                    <tr>
                                        <td>
                                            <strong class="comanda-platillo-name">{{ $platillo['nombre'] }}</strong>
                                        </td>
                                        <td class="comanda-total-porciones">
                                            {{ $platillo['porciones_totales'] }}
                                        </td>
                                        <td>
                                            <ul class="comanda-salon-list">
                                                @foreach($platillo['salones'] as $salon)
                                                    <li class="comanda-salon-item">
                                                        <strong>{{ $salon['nombre'] }}:</strong> {{ $salon['porciones'] }} porciones
                                                        @if($salon['notas'])
                                                            <br><small class="comanda-salon-nota"><em>Nota: {{ $salon['notas'] }}</em></small>
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
            <footer class="comanda-actions-footer">
                <button class="btn-print" onclick="window.print();">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="20" height="20" style="margin-right: 0.5rem;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                    </svg>
                    Imprimir Comanda
                </button>

                <a href="{{ route('reportes.insumos', $contrato->evento->id) }}" class="btn-insumos-link">
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
