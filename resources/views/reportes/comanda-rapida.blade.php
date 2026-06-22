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
        <nav class="navigation-buttons" style="margin-bottom: 2rem;">
            <a href="javascript:history.back()" style="display: inline-flex; align-items: center; gap: 0.5rem; background-color: var(--primary-purple); color: white; padding: 0.75rem 1.5rem; border-radius: 8px; text-decoration: none; font-weight: 500; font-size: 0.95rem; box-shadow: 0 4px 6px rgba(122, 40, 138, 0.2); transition: all 0.3s ease;">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="20" height="20">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Regresar
            </a>
        </nav>

        <!-- Encabezado -->
        <header class="dashboard-header" style="margin-bottom: 2rem;">
            <hgroup>
                <h1 class="dashboard-title" style="color: var(--primary-purple); text-shadow: none;">Comanda Rápida / Banquete Independiente</h1>
                
                @if(!empty($comandaSession['nombre_cliente']))
                    <p style="font-size: 1.2rem; color: #333; font-weight: bold; margin-bottom: 0.5rem; text-shadow: none;">Cliente: {{ $comandaSession['nombre_cliente'] }}</p>
                @endif

                <p class="dashboard-description" style="color: var(--text-main); text-shadow: none;">Para: <strong>{{ $comandaSession['total'] }} personas</strong> ({{ $comandaSession['adultos'] }} adultos, {{ $comandaSession['ninos'] }} niños)</p>
                
                @if(!empty($comandaSession['fecha_evento']) || !empty($comandaSession['telefono']))
                    <p style="color: var(--text-main); text-shadow: none; margin-top: 0.5rem;">
                        @if(!empty($comandaSession['fecha_evento']))
                            <strong>Fecha:</strong> {{ \Carbon\Carbon::parse($comandaSession['fecha_evento'])->format('d/m/Y') }} &nbsp;
                        @endif
                        @if(!empty($comandaSession['telefono']))
                            <strong>Teléfono:</strong> {{ $comandaSession['telefono'] }}
                        @endif
                    </p>
                @endif
            </hgroup>
        </header>

        <!-- Sección de reporte -->
        <section class="reportes-section">
            <article class="sucursal-card main-report-card">

                <header class="card-header-report" style="border-bottom: 1px solid rgba(0,0,0,0.05); padding-bottom: 1rem; margin-bottom: 1rem;">
                    <h2 class="card-title">Resumen de Comanda</h2>
                </header>

                <section class="platillos-preparar-card">
                    <h3 class="platillos-title">Platillos a Preparar:</h3>
                    <ul class="platillos-list-badges">
                        @foreach($platillos as $platillo)
                        <li class="platillo-chip">
                            <strong>{{ $platillo->nombre }}</strong>
                        </li>
                        @endforeach

                        @if($platillos->isEmpty())
                        <li class="no-platillos-item">No hay platillos seleccionados.</li>
                        @endif
                    </ul>
                </section>

                @php
                $groupedInsumos = collect($reporteInsumos)->groupBy('categoria');
                $categoriaOrder = ['Frutas y Verduras', 'Cremería', 'Abarrotes', 'Carnes', 'Otros'];
                $sortedGroups = $groupedInsumos->sortBy(function($val, $key) use ($categoriaOrder) {
                $pos = array_search($key, $categoriaOrder);
                return $pos === false ? 99 : $pos;
                });
                @endphp


            </article>

            <!-- Lista de Compras para Central de Abasto -->
            <article class="sucursal-card shopping-list-card" style="margin-top: -1.5rem;">
                <header class="card-header-shopping" style="border-bottom: 2px solid var(--accent-magenta); padding-bottom: 1.5rem; margin-bottom: 2rem;">
                    <p class="eyebrow-accent" style="font-size: 1.1rem;">LISTA DE SUPERMERCADO</p>
                    <h2 class="card-title" style="font-size: 2rem;">Ingredientes a Comprar</h2>
                </header>

                @php
                $comprasInsumos = collect($reporteInsumos)->where('comprar_raw', '>', 0);
                $groupedCompras = $comprasInsumos->groupBy('categoria');
                $sortedCompras = $groupedCompras->sortBy(function($val, $key) use ($categoriaOrder) {
                $pos = array_search($key, $categoriaOrder);
                return $pos === false ? 99 : $pos;
                });
                @endphp

                <section class="shopping-list-categories">
                    @if($comprasInsumos->count() > 0)
                    @foreach($sortedCompras as $categoria => $compras)
                    <section class="shopping-category-section">
                        <h3 class="shopping-category-title">
                            {{ $categoria }}
                        </h3>
                        <menu class="shopping-items-grid" style="padding: 0; margin: 0;">
                            @foreach($compras as $insumo)
                            <article class="shopping-item-card">
                                <label class="checkbox-container">
                                    <input type="checkbox">
                                    <span class="checkmark"></span>
                                    <hgroup class="shopping-item-info">
                                        <span class="shopping-item-name">{{ $insumo['nombre'] }}</span>
                                        <span class="shopping-item-qty">{{ $insumo['comprar_format'] }}</span>
                                    </hgroup>
                                </label>
                            </article>
                            @endforeach
                        </menu>
                    </section>
                    @endforeach
                    @else
                    <aside class="all-stocked-message" style="background-color: #f8fafc; color: #475569; border-color: #e2e8f0;">
                        No hay insumos en esta lista. Selecciona los platillos deseados en el panel anterior para generar tu comanda.
                    </aside>
                    @endif
                </section>

                <footer class="report-button-container">
                    <button class="btn-print" onclick="window.print();">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="20" height="20">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                        </svg>
                        Imprimir Todo
                    </button>
                </footer>
            </article>
        </section>
    </main>

    <!-- Footer -->
    <footer class="dashboard-footer">
        <p>© 2026 FantaSync · Sistema de Gestión de Eventos Gastronómicos</p>
    </footer>
</body>

</html>