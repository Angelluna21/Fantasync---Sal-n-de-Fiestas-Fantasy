<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compras de la Semana · Central de Abastos · FantaSync</title>
    @vite(['resources/css/app.css', 'resources/css/dashboard.css', 'resources/css/reportes.css'])
    <style>
        .filter-date-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(10px);
            border: 1px solid var(--border-color);
            border-radius: 1.5rem;
            padding: 1.5rem 2rem;
            margin-bottom: 2rem;
            box-shadow: var(--shadow-sm);
        }
        .filter-form {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            flex-wrap: wrap;
        }
        .form-group-inline {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .form-group-inline label {
            font-weight: 700;
            color: var(--primary-purple);
            font-size: 0.95rem;
        }
        .date-input {
            padding: 0.5rem 1rem;
            border: 2px solid var(--border-color);
            border-radius: 0.75rem;
            font-family: inherit;
            font-size: 0.95rem;
            color: var(--text-main);
            outline: none;
            transition: all 0.2s ease;
        }
        .date-input:focus {
            border-color: var(--primary-purple);
            box-shadow: 0 0 0 3px rgba(122, 40, 138, 0.15);
        }
        .btn-filter {
            background: var(--primary-purple);
            color: white;
            border: none;
            padding: 0.6rem 1.5rem;
            border-radius: 0.75rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .btn-filter:hover {
            background: #5c1d6e;
            transform: translateY(-2px);
        }
        .event-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            background: rgba(122, 40, 138, 0.08);
            color: var(--primary-purple);
            padding: 0.3rem 0.8rem;
            border-radius: 1rem;
            font-size: 0.85rem;
            font-weight: 700;
            border: 1px solid rgba(122, 40, 138, 0.2);
        }
        .event-desglose-item {
            font-size: 0.85rem;
            color: var(--text-main);
            margin-bottom: 0.2rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .event-desglose-bullet {
            width: 6px;
            height: 6px;
            background: var(--accent-magenta);
            border-radius: 50%;
            display: inline-block;
        }
        .total-comprar-badge {
            background: linear-gradient(135deg, var(--accent-yellow), #fbc02d);
            color: var(--primary-purple);
            font-weight: 800;
            font-size: 1.1rem;
            padding: 0.4rem 1rem;
            border-radius: 0.75rem;
            box-shadow: var(--shadow-sm);
            display: inline-block;
        }
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background: white !important;
                color: black !important;
            }
            .dashboard-layout {
                padding: 0 !important;
                margin: 0 !important;
                max-width: 100% !important;
            }
            .sucursal-card, .category-group-card {
                box-shadow: none !important;
                border: 1px solid #ccc !important;
                background: white !important;
                page-break-inside: avoid;
            }
            .total-comprar-badge {
                background: #eee !important;
                color: black !important;
                border: 1px solid #333 !important;
                box-shadow: none !important;
            }
            .tabla-reporte th {
                background-color: #333 !important;
                color: white !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>
<body>
    <figure class="dashboard-background no-print" aria-hidden="true"></figure>
    
    <main class="dashboard-layout">
        <!-- Navegación superior -->
        <nav class="top-nav no-print" aria-label="Menú superior" style="align-items: flex-start; margin-bottom: 2rem;">
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

                    <a href="{{ route('eventos.index') }}" class="btn-back-nav-glass" style="margin: 0; padding: 0.4rem 1rem; font-size: 0.85rem;">
                        🎉 Lista de Eventos
                    </a>
                </section>
            </section>
            
            <!-- Lado derecho: Botón Impresión y Usuario -->
            <section style="flex: 1; display: flex; flex-direction: column; align-items: flex-end; gap: 1rem;">
                <x-user-menu />
                <button class="btn-back-nav-glass no-print" onclick="window.print();" style="background: linear-gradient(135deg, var(--accent-yellow), #fbc02d); color: var(--primary-purple); border: none; font-size: 1rem; padding: 0.65rem 1.5rem; box-shadow: var(--shadow-yellow); cursor: pointer; font-weight: 800;">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="20" height="20" style="margin-right: 0.4rem;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    🖨️ Imprimir Lista para la Central de Abastos
                </button>
            </section>
        </nav>

        <!-- Filtro por rango de fechas -->
        <section class="filter-date-card no-print">
            <form action="{{ route('reportes.compras-semana') }}" method="GET" class="filter-form">
                <div class="form-group-inline">
                    <label for="fecha_inicio">📅 Desde:</label>
                    <input type="date" id="fecha_inicio" name="fecha_inicio" value="{{ $fechaInicio }}" class="date-input" required>
                </div>
                <div class="form-group-inline">
                    <label for="fecha_fin">Hasta:</label>
                    <input type="date" id="fecha_fin" name="fecha_fin" value="{{ $fechaFin }}" class="date-input" required>
                </div>
                <button type="submit" class="btn-filter">
                    🔍 Filtrar Compras de este Período
                </button>
            </form>
        </section>

        <!-- Tarjeta Principal del Reporte -->
        <section class="reportes-section">
            <article class="sucursal-card main-report-card" style="padding: 2rem;">
                <header style="border-bottom: 2px solid rgba(122, 40, 138, 0.15); padding-bottom: 1.5rem; margin-bottom: 2rem;">
                    <p class="eyebrow" style="color: var(--accent-magenta); margin-bottom: 0.2rem; font-size: 0.95rem; text-transform: uppercase; font-weight: 800; letter-spacing: 0.05em;">🛒 Compras y Almacén · Central de Abastos</p>
                    <h1 style="color: var(--primary-purple); font-size: 2.2rem; font-weight: 800; margin: 0 0 0.5rem 0; line-height: 1.2;">Lista Consolidada de Compras Semanales / por Período</h1>
                    <p style="color: var(--text-main); font-size: 1.1rem; margin: 0;">Período seleccionado: <strong style="color: var(--primary-purple);">{{ \Carbon\Carbon::parse($fechaInicio)->format('d/m/Y') }} al {{ \Carbon\Carbon::parse($fechaFin)->format('d/m/Y') }}</strong></p>
                </header>

                <header class="card-header-report" style="margin-bottom: 2rem; padding-bottom: 0; border: none;">
                    <h2 class="card-title" style="font-size: 1.2rem; margin-bottom: 0.8rem;">🎉 Eventos incluidos en esta compra ({{ count($eventos) }}):</h2>
                    @if(count($eventos) > 0)
                        <section style="display: flex; flex-wrap: wrap; gap: 0.6rem;">
                            @foreach($eventos as $ev)
                                <span class="event-pill">
                                    📅 {{ $ev->fecha->format('d/m/Y') }} — <strong>{{ $ev->titulo }}</strong>
                                </span>
                            @endforeach
                        </section>
                    @else
                        <p style="color: #c62828; font-weight: 700;">⚠️ No se encontraron eventos programados con salones asignados en este rango de fechas.</p>
                    @endif
                </header>

                <!-- Tablas por Categoría de Supermercado -->
                @if(count($sortedGroups) > 0)
                    <section class="insumos-categories-container">
                        @foreach($sortedGroups as $categoria => $insumos)
                            <article class="category-group-card" style="margin-bottom: 2rem;">
                                <h3 class="category-group-title" style="background: var(--primary-purple); color: white; padding: 0.75rem 1.25rem; margin: 0; border-radius: 1rem 1rem 0 0; font-size: 1.15rem;">
                                    ▫️ {{ $categoria }}
                                </h3>
                                <figure class="table-responsive" style="margin: 0;">
                                    <table class="tabla-reporte" style="width: 100%; border-collapse: collapse;">
                                        <thead>
                                            <tr style="background: rgba(122, 40, 138, 0.05); text-align: left;">
                                                <th style="padding: 0.8rem 1rem; width: 35%;">Materia Prima / Insumo</th>
                                                <th style="padding: 0.8rem 1rem; width: 25%; text-align: center;">Total a Comprar (Sugerido Central)</th>
                                                <th style="padding: 0.8rem 1rem; width: 40%;">Desglose por Evento (Para repartir en cocina)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($insumos as $insumo)
                                                <tr style="border-bottom: 1px solid var(--border-color);">
                                                    <td style="padding: 0.8rem 1rem; vertical-align: middle;">
                                                        <strong style="font-size: 1.1rem; color: var(--primary-purple);">{{ $insumo['nombre'] }}</strong>
                                                        <span style="display: block; font-size: 0.8rem; color: var(--text-muted);">Unidad: {{ $insumo['unidad'] }}</span>
                                                    </td>
                                                    <td style="padding: 0.8rem 1rem; text-align: center; vertical-align: middle;">
                                                        <span class="total-comprar-badge">
                                                            {{ $insumo['comprar_format'] }}
                                                        </span>
                                                        <span style="display: block; font-size: 0.75rem; color: var(--text-muted); margin-top: 0.2rem;">(Exacto con merma: {{ $insumo['seguro_format'] }})</span>
                                                    </td>
                                                    <td style="padding: 0.8rem 1rem; vertical-align: middle;">
                                                        @foreach($insumo['eventos_desglose'] as $desglose)
                                                            <div class="event-desglose-item">
                                                                <span class="event-desglose-bullet"></span>
                                                                <strong>{{ $desglose['format'] }}</strong> — <span>{{ $desglose['evento_titulo'] }} ({{ $desglose['evento_fecha'] }})</span>
                                                            </div>
                                                        @endforeach
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </figure>
                            </article>
                        @endforeach
                    </section>
                @endif

                <footer class="comanda-actions-footer no-print" style="display: flex; justify-content: center; gap: 1rem; margin-top: 2rem; margin-bottom: 3rem;">
                    <button class="btn-print" onclick="window.print();" style="background: linear-gradient(135deg, var(--primary-purple), #4a148c); color: white; border: none; font-size: 1.1rem; font-weight: 800; padding: 0.8rem 2.5rem; border-radius: 2rem; box-shadow: 0 4px 15px rgba(122, 40, 138, 0.4); cursor: pointer; display: inline-flex; align-items: center; gap: 0.5rem; transition: all 0.3s ease;">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="22" height="22"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                        Imprimir Lista para Central de Abastos
                    </button>
                </footer>
            </article>
        </section>
    </main>
</body>
</html>
