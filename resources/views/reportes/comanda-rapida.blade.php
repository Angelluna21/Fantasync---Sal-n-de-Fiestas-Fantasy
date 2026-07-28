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
        <nav class="navigation-buttons nav-buttons-rapida">
            <a href="javascript:history.back()" class="btn-back-purple">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="20" height="20">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Regresar
            </a>
        </nav>

        <!-- Encabezado -->
        <header class="dashboard-header header-rapida">
            <hgroup>
                <h1 class="dashboard-title title-rapida">Comanda Rápida / Banquete Independiente</h1>
                
                @if(!empty($comandaSession['nombre_cliente']))
                    <p class="dashboard-cliente">Cliente: {{ $comandaSession['nombre_cliente'] }}</p>
                @endif

                <p class="dashboard-description dashboard-description-rapida">Para: <strong>{{ $comandaSession['total'] }} personas</strong> ({{ $comandaSession['adultos'] }} adultos, {{ $comandaSession['ninos'] }} niños)</p>
                
                @if(!empty($comandaSession['fecha_evento']) || !empty($comandaSession['telefono']))
                    <p class="dashboard-fecha">
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

                <header class="card-header-report card-header-rapida">
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
                $categoriaOrder = ['Frutas y Verduras', 'Carnes', 'Cremería', 'Abarrotes', 'General', 'Otros'];
                $sortedGroups = $groupedInsumos->sortBy(function($val, $key) use ($categoriaOrder) {
                $pos = array_search($key, $categoriaOrder);
                return $pos === false ? 99 : $pos;
                });
                @endphp


            </article>

            <!-- Lista de Compras para Central de Abasto -->
            <article class="sucursal-card shopping-list-card shopping-list-card-rapida">
                <header class="card-header-shopping card-header-shopping-rapida">
                    <p class="eyebrow-accent eyebrow-accent-rapida">LISTA DE SUPERMERCADO</p>
                    <h2 class="card-title card-title-rapida">Ingredientes a Comprar</h2>
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
                        <menu class="shopping-items-grid shopping-items-grid-reset">
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
                    <aside class="all-stocked-message all-stocked-message-alt">
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