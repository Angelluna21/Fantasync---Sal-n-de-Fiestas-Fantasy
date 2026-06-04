<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Insumos · FantaSync</title>
    @vite(['resources/css/app.css', 'resources/css/dashboard.css', 'resources/css/reportes.css'])
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
            <a href="{{ route('dashboard') }}" class="btn-back-nav" style="background: rgba(255, 255, 255, 0.15); color: #ffffff; border: 1px solid rgba(255, 255, 255, 0.25); backdrop-filter: blur(4px); padding: 0.5rem 1.15rem; font-size: 0.9rem; margin-bottom: 0.5rem; transition: all 0.3s; text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem; border-radius: 2rem;" onmouseover="this.style.background='var(--accent-yellow)'; this.style.color='var(--primary-purple)'; this.style.borderColor='var(--accent-yellow)';" onmouseout="this.style.background='rgba(255, 255, 255, 0.15)'; this.style.color='#ffffff'; this.style.borderColor='rgba(255, 255, 255, 0.25)';">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 18px; height: 18px; transition: transform 0.3s;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Volver al Panel
            </a>
        </nav>

        <!-- Encabezado -->
        <header class="dashboard-header">
            <hgroup>
                <p class="eyebrow">📋 Producción de Cocina</p>
                <h1 class="dashboard-title">Lista Consolidada de Insumos</h1>
                <p class="dashboard-description">Evento: <strong>{{ $evento->titulo }}</strong> ({{ $evento->fecha->format('d/m/Y') }})</p>
            </hgroup>
        </header>

        <!-- Sección de reporte -->
        <section class="reportes-section" style="max-width: 1200px; margin: 0 auto; width: 100%;">
            <article class="sucursal-card" style="padding: 2rem;">
                
                <header class="card-header" style="margin-bottom: 1.5rem;">
                    <h2 class="card-title">Salones Reservados:</h2>
                    <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                        @foreach($evento->salones as $salon)
                            <span class="location-badge" style="background: var(--accent-magenta); color: white; padding: 0.3rem 0.8rem; border-radius: 2rem; font-size: 0.9rem;">
                                {{ $salon->nombre }}
                            </span>
                        @endforeach
                    </div>
                </header>

                <table class="tabla-reporte">
                    <thead>
                        <tr>
                            <th>Materia Prima / Insumo</th>
                            <th>Cantidad Necesaria</th>
                            <th>Disponibilidad</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(!empty($reporteInsumos))
                            @foreach($reporteInsumos as $insumo)
                                <tr>
                                    <td><strong>{{ $insumo['nombre'] }}</strong></td>
                                    <td style="font-weight: bold; color: var(--accent-magenta);">{{ $insumo['requerido_format'] }}</td>
                                    <td>{{ $insumo['stock_format'] }}</td>
                                    <td>
                                        @if($insumo['estado'] === 'verde')
                                            <span class="status-badge status-verde">🟢 Disponible</span>
                                        @elseif($insumo['estado'] === 'amarillo')
                                            <span class="status-badge status-amarillo">🟡 Stock Bajo</span>
                                        @else
                                            <span class="status-badge status-rojo">🔴 Sin Stock</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="4" style="text-align: center; padding: 2rem; color: #6c757d;">
                                    No hay platillos asignados para calcular insumos.
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
                
                <footer class="report-button-container" style="display: flex; justify-content: center; margin-top: 3rem;">
                    <button class="btn-print" onclick="window.print();" style="background: var(--primary-purple); color: white; border: none; padding: 0.8rem 1.5rem; border-radius: 2rem; font-weight: bold; cursor: pointer; display: flex; align-items: center; transition: background 0.3s;">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="20" height="20" style="margin-right: 0.5rem;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                        </svg>
                        Imprimir Todo
                    </button>
                </footer>
            </article>

            <!-- Lista de Compras para Central de Abasto -->
            <article class="sucursal-card" style="padding: 2rem; margin-top: 2rem; page-break-before: always;">
                <header class="card-header" style="margin-bottom: 1.5rem;">
                    <p class="eyebrow" style="color: var(--accent-magenta); font-weight: bold;">🛒 LOGÍSTICA</p>
                    <h2 class="card-title">Lista de Compras (Central de Abasto)</h2>
                    <p style="color: #666; margin-top: 0.5rem;">Esta tabla filtra automáticamente lo que ya tienes en stock y muestra únicamente las faltantes a comprar para este evento.</p>
                </header>

                <table class="tabla-reporte" style="margin-top: 1rem;">
                    <thead>
                        <tr>
                            <th style="width: 50px; text-align: center;">[  ]</th>
                            <th>Insumo a Comprar</th>
                            <th>Cantidad a Comprar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $hayCompras = false;
                        @endphp
                        
                        @if(!empty($reporteInsumos))
                            @foreach($reporteInsumos as $insumo)
                                @if($insumo['comprar_raw'] > 0)
                                    @php $hayCompras = true; @endphp
                                    <tr>
                                        <td style="text-align: center;">
                                            <input type="checkbox" style="width: 18px; height: 18px; cursor: pointer;">
                                        </td>
                                        <td><strong>{{ $insumo['nombre'] }}</strong></td>
                                        <td style="font-weight: bold; color: var(--accent-magenta); font-size: 1.1rem;">
                                            {{ $insumo['comprar_format'] }}
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        @endif

                        @if(!$hayCompras)
                            <tr>
                                <td colspan="3" style="text-align: center; padding: 2rem; color: #28a745; font-weight: bold;">
                                    ¡Excelente! Hay stock suficiente de todos los ingredientes para este evento. No es necesario comprar nada.
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </article>
        </section>
    </main>

    <!-- Footer -->
    <footer class="dashboard-footer">
        <p>© 2026 FantaSync · Sistema de Gestión de Eventos Gastronómicos</p>
    </footer>
</body>
</html>