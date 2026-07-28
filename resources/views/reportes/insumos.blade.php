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
        <nav class="top-nav" aria-label="Menú superior" style="align-items: flex-start; margin-bottom: 2rem;">
            <!-- Lado izquierdo: Logo y Botones de navegación -->
            <section style="display: flex; flex-direction: column; gap: 0.5rem; flex: 1;">
                <a href="{{ route('dashboard') }}" aria-label="Volver al panel" class="logo-link" style="width: fit-content;">
                    <img src="{{ asset('img/logo.png') }}" alt="Logo FantaSync" class="nav-logo" style="height: 100px;">
                </a>
                
                <section style="display: flex; gap: 0.5rem; flex-wrap: wrap; margin-top: 0.5rem;">
                    <a href="javascript:history.back()" class="btn-back-nav-glass" style="margin: 0; padding: 0.4rem 1rem; font-size: 0.85rem;">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="nav-icon"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                        Regresar
                    </a>
                    
                    <a href="{{ route('dashboard') }}" class="btn-back-nav-glass btn-dashboard" style="margin: 0; padding: 0.4rem 1rem; font-size: 0.85rem;">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="nav-icon"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                        Dashboard
                    </a>
                </section>
            </section>
            
            <!-- Lado derecho: Menú de usuario -->
            <section style="flex: 1; display: flex; justify-content: flex-end;">
                <x-user-menu />
            </section>
        </nav>

        <!-- Sección de reporte -->
        <section class="reportes-section">
            <article class="sucursal-card main-report-card">
                
                <!-- Encabezado integrado al contenedor -->
                <header style="border-bottom: 2px solid rgba(122, 40, 138, 0.15); padding-bottom: 1.5rem; margin-bottom: 2rem;">
                    <p class="eyebrow" style="color: var(--accent-magenta); margin-bottom: 0.2rem; font-size: 0.95rem; text-transform: uppercase; font-weight: 800; letter-spacing: 0.05em;">Producción de Cocina</p>
                    <h1 style="color: var(--primary-purple); font-size: 2.2rem; font-weight: 800; margin: 0 0 0.5rem 0; line-height: 1.2;">Lista de Insumos</h1>
                    <p style="color: var(--text-main); font-size: 1.1rem; margin: 0;">Evento: <strong style="color: var(--primary-purple);">{{ $evento->titulo }}</strong> ({{ $evento->fecha->format('d/m/Y') }})</p>
                </header>

                <header class="card-header-report" style="margin-bottom: 1.5rem; padding-bottom: 0; border: none;">
                    <h2 class="card-title">Salones Reservados:</h2>
                    <section class="salones-badges-container">
                        @foreach($evento->salones as $salon)
                            <span class="location-badge">
                                {{ $salon->nombre }}
                            </span>
                        @endforeach
                    </section>
                </header>

                <section class="platillos-preparar-card">
                    <header style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; margin-bottom: 1rem;">
                        <h3 class="platillos-title" style="margin: 0;">Platillos a Preparar:</h3>
                        @if($evento->contrato)
                            <a href="{{ route('reportes.comanda', $evento->contrato->id) }}" style="background: linear-gradient(135deg, var(--primary-purple), #4a148c); color: white; padding: 0.55rem 1.25rem; border-radius: 2rem; text-decoration: none; font-weight: 800; font-size: 0.85rem; display: inline-flex; align-items: center; gap: 0.5rem; box-shadow: 0 4px 12px rgba(122, 40, 138, 0.3); transition: all 0.3s ease;">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="18" height="18"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                Imprimir pedido del cliente
                            </a>
                        @endif
                    </header>
                    @php
                        $platillosList = collect();
                        foreach($evento->eventoSalones as $es) {
                            foreach($es->platillos as $pl) {
                                $cat = $pl->categoriaPlatillo ? $pl->categoriaPlatillo->nombre : 'Sin Categoría';
                                $id = $pl->id;
                                if(!$platillosList->has($id)) {
                                    $platillosList->put($id, [
                                        'nombre' => $pl->nombre,
                                        'categoria' => $cat,
                                        'porciones' => 0,
                                    ]);
                                }
                                $item = $platillosList->get($id);
                                $item['porciones'] += $pl->pivot->porciones_plan;
                                $platillosList->put($id, $item);
                            }
                        }
                        $ordenDeseado = ['Entradas', 'Cremas y Sopas', 'Platos Fuertes', 'Guarniciones (Formales)', 'Guisados', 'Parrillada (Carnes)', 'Guarniciones', 'Menú Infantil', 'Buffet Infantil', 'Bebidas', 'Dulces', 'Postres'];
                        $platillosPorCat = $platillosList->groupBy('categoria')->sortBy(function($val, $cat) use ($ordenDeseado) {
                            $pos = array_search($cat, $ordenDeseado);
                            return $pos === false ? 999 : $pos;
                        });
                    @endphp

                    @if($platillosPorCat->isEmpty())
                        <p class="no-platillos-item" style="margin: 0; padding: 0.5rem 0;">No hay platillos asignados a este evento aún.</p>
                    @else
                        <div style="display: flex; flex-direction: column; gap: 0.8rem;">
                            @foreach($platillosPorCat as $catName => $items)
                                <div>
                                    <h4 style="margin: 0 0 0.3rem 0; font-size: 0.8rem; color: var(--accent-magenta); text-transform: uppercase; letter-spacing: 0.04em; font-weight: 800;">
                                        ▫️ {{ $catName }}
                                    </h4>
                                    <ul class="platillos-list-badges" style="margin: 0;">
                                        @foreach($items as $platillo)
                                            <li class="platillo-chip">
                                                <strong>{{ $platillo['nombre'] }}</strong>
                                                <span class="platillo-details">({{ $platillo['porciones'] }} porciones)</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </section>

                @php
                    $groupedInsumos = collect($reporteInsumos)->groupBy('categoria');
                    $categoriaOrder = ['Frutas y Verduras', 'Carnes', 'Cremería', 'Abarrotes', 'General', 'Otros'];
                    $sortedGroups = $groupedInsumos->sortBy(function($val, $key) use ($categoriaOrder) {
                        $pos = array_search($key, $categoriaOrder);
                        return $pos === false ? 99 : $pos;
                    });
                @endphp

                <div style="display: flex; justify-content: flex-end; margin-bottom: 1.5rem;" class="no-print">
                    <a href="{{ route('reportes.compras-semana') }}" style="background: linear-gradient(135deg, var(--accent-yellow), #fbc02d); color: var(--primary-purple); padding: 0.65rem 1.5rem; border-radius: 2rem; text-decoration: none; font-weight: 800; font-size: 0.95rem; display: inline-flex; align-items: center; gap: 0.5rem; box-shadow: var(--shadow-sm); transition: all 0.3s ease;">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="20" height="20"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        Ver Lista Consolidada de la Semana (Central de Abastos)
                    </a>
                </div>

                <section class="insumos-categories-container">
                    @if(count($reporteInsumos) > 0)
                        @foreach($sortedGroups as $categoria => $insumos)
                            <article class="category-group-card">
                                <h3 class="category-group-title">
                                    {{ $categoria }}
                                </h3>
                                <figure class="table-responsive">
                                    <table class="tabla-reporte">
                                        <thead>
                                            <tr>
                                                <th>Materia Prima / Insumo</th>
                                                <th>Exacto</th>
                                                <th>Sugerido</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($insumos as $insumo)
                                                <tr>
                                                    <td><strong>{{ $insumo['nombre'] }}</strong></td>
                                                    <td class="col-requerido">{{ $insumo['exacto_format'] }}</td>
                                                    <td class="col-margen">{{ $insumo['seguro_format'] }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </figure>
                            </article>
                        @endforeach
                    @else
                        <section class="empty-insumos-state">
                            No hay platillos asignados para calcular insumos.
                        </section>
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

            <!-- Lista de Compras para Central de Abasto (Agrupada) -->
            <article class="sucursal-card shopping-list-card">
                <header class="card-header-shopping">
                    <p class="eyebrow-accent">LOGÍSTICA</p>
                    <h2 class="card-title">Lista de Compras (Central de Abasto)</h2>
                    <p class="card-subtitle">Lista consolidada por áreas de la Central de Abasto con totales comerciales.</p>
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
                                <menu class="shopping-items-grid">
                                    @foreach($compras as $insumo)
                                        <li class="shopping-item-card">
                                            <label class="checkbox-container">
                                                <input type="checkbox">
                                                <span class="checkmark"></span>
                                                <hgroup class="shopping-item-info">
                                                    <span class="shopping-item-name">{{ $insumo['nombre'] }}</span>
                                                    <span class="shopping-item-qty">{{ $insumo['comprar_format'] }}</span>
                                                </hgroup>
                                            </label>
                                        </li>
                                    @endforeach
                                </menu>
                            </section>
                        @endforeach
                    @else
                        <section class="all-stocked-message">
                            Hay stock suficiente de todos los ingredientes para este evento. No es necesario comprar nada.
                        </section>
                    @endif
                </section>
            </article>

            <!-- Bloque de Confirmación y Salida -->
            <article class="sucursal-card text-center no-print" style="margin-top: 1rem; padding: 3rem 2rem; text-align: center;">
                <svg fill="none" stroke="#1b8544" viewBox="0 0 24 24" width="60" height="60" style="margin: 0 auto 1rem;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h2 style="color: var(--primary-purple); font-size: 1.5rem; margin-bottom: 0.5rem;">¡Todo está guardado exitosamente!</h2>
                <p style="color: var(--text-muted); margin-bottom: 2rem; font-size: 1.05rem;">El evento y la lista logística están seguros. Puedes salir de esta pantalla sin preocuparte.</p>
                <a href="{{ route('dashboard') }}" class="btn-print" style="text-decoration: none; margin: 0 auto;">
                    Finalizar y Volver al Inicio
                </a>
            </article>
        </section>
    </main>

    <!-- Footer -->
    <footer class="dashboard-footer">
        <p>© 2026 FantaSync · Sistema de Gestión de Eventos Gastronómicos</p>
    </footer>
</body>
</html>