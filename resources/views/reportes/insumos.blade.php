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

        <!-- Navegación de regreso -->
        <nav class="navigation-buttons">
            <a href="javascript:history.back()" class="btn-back-nav-glass">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="nav-icon"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                Regresar a la Vista Anterior
            </a>
            
            <a href="{{ route('dashboard') }}" class="btn-back-nav-glass btn-dashboard">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="nav-icon"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                Ir al Dashboard
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
        <section class="reportes-section">
            <article class="sucursal-card main-report-card">
                
                <header class="card-header-report">
                    <h2 class="card-title">Salones Reservados:</h2>
                    <div class="salones-badges-container">
                        @foreach($evento->salones as $salon)
                            <span class="location-badge">
                                {{ $salon->nombre }}
                            </span>
                        @endforeach
                    </div>
                </header>

                <div class="platillos-preparar-card">
                    <h3 class="platillos-title">🍽️ Platillos a Preparar:</h3>
                    <ul class="platillos-list-badges">
                        @php $tienePlatillos = false; @endphp
                        @foreach($evento->eventoSalones as $eventoSalon)
                            @foreach($eventoSalon->platillos as $platillo)
                                @php $tienePlatillos = true; @endphp
                                <li class="platillo-chip">
                                    <strong>{{ $platillo->nombre }}</strong>
                                    <span class="platillo-details">({{ $platillo->pivot->porciones_plan }} porciones en {{ $eventoSalon->salon->nombre ?? 'Salón' }})</span>
                                </li>
                            @endforeach
                        @endforeach
                        
                        @if(!$tienePlatillos)
                            <li class="no-platillos-item">No hay platillos asignados a este evento aún.</li>
                        @endif
                    </ul>
                </div>

                @php
                    $groupedInsumos = collect($reporteInsumos)->groupBy('categoria');
                    $categoriaOrder = ['Frutas y Verduras', 'Cremería', 'Abarrotes', 'Carnes', 'Otros'];
                    $sortedGroups = $groupedInsumos->sortBy(function($val, $key) use ($categoriaOrder) {
                        $pos = array_search($key, $categoriaOrder);
                        return $pos === false ? 99 : $pos;
                    });
                    
                    $emojiMap = [
                        'Frutas y Verduras' => '🥬',
                        'Cremería' => '🥛',
                        'Carnes' => '🥩',
                        'Abarrotes' => '🥫',
                        'Otros' => '📦'
                    ];
                @endphp

                <div class="insumos-categories-container">
                    @if(count($reporteInsumos) > 0)
                        @foreach($sortedGroups as $categoria => $insumos)
                            <div class="category-group-card">
                                <h3 class="category-group-title">
                                    <span class="category-icon">{{ $emojiMap[$categoria] ?? '📦' }}</span>
                                    {{ $categoria }}
                                </h3>
                                <div class="table-responsive">
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
                                            @foreach($insumos as $insumo)
                                                <tr>
                                                    <td><strong>{{ $insumo['nombre'] }}</strong></td>
                                                    <td class="col-requerido">{{ $insumo['requerido_format'] }}</td>
                                                    <td class="col-stock">{{ $insumo['stock_format'] }}</td>
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
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="empty-insumos-state">
                            No hay platillos asignados para calcular insumos.
                        </div>
                    @endif
                </div>
                
                <footer class="report-button-container">
                    <button class="btn-print" onclick="window.print();">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="20" height="20">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                        </svg>
                        Imprimir Todo
                    </button>
                </footer>
            </article>

            <!-- Lista de Compras para Central de Abasto (Agrupada) -->
            <article class="sucursal-card shopping-list-card">
                <header class="card-header-shopping">
                    <p class="eyebrow-accent">🛒 LOGÍSTICA</p>
                    <h2 class="card-title">Lista de Compras (Central de Abasto)</h2>
                    <p class="card-subtitle">Esta lista se separa por áreas de la Central de Abasto y descuenta automáticamente lo que ya tienes en stock.</p>
                </header>

                @php
                    $comprasInsumos = collect($reporteInsumos)->where('comprar_raw', '>', 0);
                    $groupedCompras = $comprasInsumos->groupBy('categoria');
                    $sortedCompras = $groupedCompras->sortBy(function($val, $key) use ($categoriaOrder) {
                        $pos = array_search($key, $categoriaOrder);
                        return $pos === false ? 99 : $pos;
                    });
                @endphp

                <div class="shopping-list-categories">
                    @if($comprasInsumos->count() > 0)
                        @foreach($sortedCompras as $categoria => $compras)
                            <div class="shopping-category-section">
                                <h3 class="shopping-category-title">
                                    <span class="category-icon">{{ $emojiMap[$categoria] ?? '📦' }}</span>
                                    {{ $categoria }}
                                </h3>
                                <div class="shopping-items-grid">
                                    @foreach($compras as $insumo)
                                        <div class="shopping-item-card">
                                            <label class="checkbox-container">
                                                <input type="checkbox">
                                                <span class="checkmark"></span>
                                                <div class="shopping-item-info">
                                                    <span class="shopping-item-name">{{ $insumo['nombre'] }}</span>
                                                    <span class="shopping-item-qty">{{ $insumo['comprar_format'] }}</span>
                                                </div>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="all-stocked-message">
                            🎉 ¡Excelente! Hay stock suficiente de todos los ingredientes para este evento. No es necesario comprar nada.
                        </div>
                    @endif
                </div>
            </article>
        </section>
    </main>

    <!-- Footer -->
    <footer class="dashboard-footer">
        <p>© 2026 FantaSync · Sistema de Gestión de Eventos Gastronómicos</p>
    </footer>
</body>
</html>