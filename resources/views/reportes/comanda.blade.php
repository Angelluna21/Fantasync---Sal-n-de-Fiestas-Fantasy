<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comanda de Producción · FantaSync</title>
    @vite(['resources/css/app.css', 'resources/css/dashboard.css', 'resources/css/reportes.css'])
    <style>
        .comanda-header-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(16px);
            border: 1px solid var(--border-color);
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: var(--shadow-sm);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1.5rem;
        }
        .comanda-meta-item {
            display: flex;
            flex-direction: column;
            gap: 0.2rem;
        }
        .comanda-meta-label {
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--text-muted);
            font-weight: 800;
        }
        .comanda-meta-value {
            font-size: 1.1rem;
            color: var(--primary-purple);
            font-weight: 800;
        }
        .category-badge-group {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            background: rgba(122, 40, 138, 0.1);
            color: var(--primary-purple);
            padding: 0.35rem 0.85rem;
            border-radius: 1.5rem;
            font-size: 0.8rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            margin-left: 0.75rem;
        }
        .print-checkbox {
            width: 22px;
            height: 22px;
            border: 2px solid #ccc;
            border-radius: 4px;
            display: inline-block;
        }
        .porciones-badge {
            background: linear-gradient(135deg, var(--accent-yellow), #fbc02d);
            color: var(--primary-purple);
            font-size: 1.25rem;
            font-weight: 900;
            padding: 0.4rem 1rem;
            border-radius: 12px;
            display: inline-block;
            box-shadow: 0 2px 8px rgba(255, 193, 7, 0.3);
            min-width: 50px;
            text-align: center;
        }
        .salon-dist-box {
            background: rgba(122, 40, 138, 0.03);
            border-left: 4px solid var(--accent-magenta);
            padding: 0.6rem 1rem;
            margin-bottom: 0.5rem;
            border-radius: 0 8px 8px 0;
        }
        .salon-dist-box:last-child {
            margin-bottom: 0;
        }
        .nota-box {
            background: rgba(216, 27, 96, 0.08);
            color: #b0134e;
            padding: 0.35rem 0.65rem;
            border-radius: 6px;
            font-size: 0.85rem;
            font-weight: 700;
            margin-top: 0.35rem;
            display: block;
        }
        @media print {
            body {
                background: white !important;
                color: black !important;
                font-family: 'Helvetica Neue', Arial, sans-serif !important;
            }
            .dashboard-background, .top-nav, .navigation-buttons, .comanda-actions-footer, .no-print {
                display: none !important;
            }
            .dashboard-layout {
                padding: 0 !important;
                margin: 0 !important;
                max-width: 100% !important;
            }
            .comanda-header-card {
                box-shadow: none !important;
                border: 2px solid #000 !important;
                background: #fff !important;
                padding: 1rem !important;
                margin-bottom: 1.5rem !important;
                border-radius: 8px !important;
            }
            .comanda-meta-label, .comanda-meta-value {
                color: #000 !important;
            }
            .sucursal-card {
                box-shadow: none !important;
                border: none !important;
                background: white !important;
                padding: 0 !important;
                margin-bottom: 2rem !important;
                page-break-inside: avoid;
                break-inside: avoid;
            }
            .card-header {
                border-bottom: 2px solid #000 !important;
                padding-bottom: 0.5rem !important;
                margin-bottom: 0.75rem !important;
            }
            .card-title {
                color: #000 !important;
                font-size: 1.4rem !important;
            }
            .category-badge-group {
                background: #eee !important;
                color: #000 !important;
                border: 1px solid #000 !important;
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact;
            }
            .tabla-reporte {
                width: 100% !important;
                border-collapse: collapse !important;
            }
            .tabla-reporte th, .tabla-reporte td {
                border: 1px solid #000 !important;
                padding: 8px 10px !important;
                color: #000 !important;
            }
            .tabla-reporte th {
                background: #eee !important;
                font-weight: bold !important;
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact;
            }
            .porciones-badge {
                background: none !important;
                box-shadow: none !important;
                border: 2px solid #000 !important;
                color: #000 !important;
                font-size: 1.2rem !important;
                padding: 2px 8px !important;
            }
            .salon-dist-box {
                background: none !important;
                border: none !important;
                border-left: 3px solid #000 !important;
                padding: 4px 8px !important;
            }
            .nota-box {
                background: #fff !important;
                color: #000 !important;
                border: 1px dashed #000 !important;
                padding: 4px !important;
            }
            .print-checkbox {
                border: 2px solid #000 !important;
                width: 18px !important;
                height: 18px !important;
                border-radius: 50% !important;
            }
            .comanda-list-item {
                border-bottom: 1px solid #000 !important;
                break-inside: avoid;
            }
            .comanda-portions {
                border: 1px solid #000 !important;
                background: none !important;
                color: #000 !important;
            }
            .comanda-card {
                padding: 0 !important;
                border: none !important;
            }
            .comanda-category-section {
                margin-bottom: 1rem !important;
            }
        }
        /* Nuevos estilos compactos para la lista de platillos */
        .comanda-list {
            display: flex;
            flex-direction: column;
        }
        .comanda-list-item {
            display: flex;
            align-items: center;
            padding: 0.75rem 0;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            gap: 1rem;
        }
        .comanda-list-item:last-child {
            border-bottom: none;
        }
        .comanda-checkbox {
            width: 20px;
            height: 20px;
            border: 2px solid #ccc;
            border-radius: 50%;
            flex-shrink: 0;
            transition: all 0.2s ease;
        }
        .comanda-details {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 0.2rem;
        }
        .comanda-name {
            font-size: 1.05rem;
            font-weight: 700;
            color: var(--text-main);
            margin: 0;
            line-height: 1.2;
        }
        .comanda-portions {
            background: rgba(122, 40, 138, 0.08);
            color: var(--primary-purple);
            font-size: 1rem;
            font-weight: 800;
            padding: 0.25rem 0.85rem;
            border-radius: 1rem;
            white-space: nowrap;
        }
        .comanda-card {
            padding: 1.5rem !important;
        }
    </style>
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
                    
                    <a href="{{ route('reportes.insumos', $contrato->evento->id) }}" class="btn-back-nav-glass" style="margin: 0; padding: 0.4rem 1rem; font-size: 0.85rem; background: rgba(122, 40, 138, 0.2); border-color: var(--primary-purple);">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="nav-icon"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                        Ver Lista de Insumos
                    </a>

                    <a href="{{ route('dashboard') }}" class="btn-back-nav-glass btn-dashboard" style="margin: 0; padding: 0.4rem 1rem; font-size: 0.85rem;">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="nav-icon"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                        Dashboard
                    </a>
                </section>
            </section>
            
            <!-- Lado derecho: Menú y Botón Imprimir -->
            <section style="flex: 1; display: flex; flex-direction: column; align-items: flex-end; gap: 1rem;">
                <x-user-menu />
            </section>
        </nav>

        <!-- Tarjeta de Encabezado Profesional -->
        @php
            $totAdultos = $contrato->evento->salones->sum('pivot.adultos');
            $totNinos = $contrato->evento->salones->sum('pivot.ninos');
            $hrec = $contrato->evento->hora_recepcion ? \Carbon\Carbon::parse($contrato->evento->hora_recepcion)->format('H:i') . ' hrs' : 'No definida';
            $hini = $contrato->evento->hora_inicio ? \Carbon\Carbon::parse($contrato->evento->hora_inicio)->format('H:i') . ' hrs' : 'No definida';
            $sucursales = $contrato->evento->salones->map(fn($s) => $s->sucursal ? $s->sucursal->nombre : null)->filter()->unique()->implode(', ');
            if (empty($sucursales)) {
                $sucursales = 'Sucursal Fantasy';
            }
        @endphp
        @php
            $eventoNotas = $contrato->evento->notas ?? '';
            $extrasJsonStr = '';
            $extrasObj = null;
            if (preg_match('/Extras:\s*(\{.*\})/', $eventoNotas, $matches)) {
                $extrasJsonStr = $matches[1];
                $extrasObj = json_decode($extrasJsonStr);
            }
        @endphp

        <article class="comanda-header-card">
            <section style="flex: 1 1 280px;">
                <p class="eyebrow" style="color: var(--accent-magenta); margin-bottom: 0.2rem; font-size: 0.95rem; text-transform: uppercase; font-weight: 800;">Orden de Producción Gastronómica</p>
                <h1 style="color: var(--primary-purple); font-size: 2.2rem; font-weight: 900; margin: 0 0 0.4rem 0; line-height: 1.1;">Comanda de Cocina</h1>
                <p style="color: var(--text-main); font-size: 1.15rem; margin: 0;">Evento: <strong style="color: var(--primary-purple);">{{ $contrato->evento->titulo }}</strong></p>
            </section>

            <section style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 1.25rem; flex: 2 1 500px; background: rgba(122, 40, 138, 0.04); padding: 1.25rem; border-radius: 16px; border: 1px solid rgba(122, 40, 138, 0.15);">
                <div class="comanda-meta-item">
                    <span class="comanda-meta-label">Fecha del Evento</span>
                    <span class="comanda-meta-value">{{ $contrato->evento->fecha->format('d/m/Y') }}</span>
                </div>
                <div class="comanda-meta-item">
                    <span class="comanda-meta-label">Cliente</span>
                    <span class="comanda-meta-value">{{ $contrato->evento->cliente->nombre_completo ?? $contrato->evento->titulo }}</span>
                </div>
                <div class="comanda-meta-item">
                    <span class="comanda-meta-label">Sucursal</span>
                    <span class="comanda-meta-value">{{ $sucursales }}</span>
                </div>
                <div class="comanda-meta-item">
                    <span class="comanda-meta-label">Invitados Adultos</span>
                    <span class="comanda-meta-value">{{ $totAdultos }} <small style="font-size: 0.8rem; font-weight: 700; color: var(--text-muted);">adultos</small></span>
                </div>
                <div class="comanda-meta-item">
                    <span class="comanda-meta-label">Invitados Niños</span>
                    <span class="comanda-meta-value">{{ $totNinos }} <small style="font-size: 0.8rem; font-weight: 700; color: var(--text-muted);">niños</small></span>
                </div>
                <div class="comanda-meta-item">
                    <span class="comanda-meta-label">Recepción</span>
                    <span class="comanda-meta-value">{{ $hrec }}</span>
                </div>
                <div class="comanda-meta-item">
                    <span class="comanda-meta-label">Hora de Inicio</span>
                    <span class="comanda-meta-value">{{ $hini }}</span>
                </div>
                @if($extrasObj)
                <div class="comanda-meta-item">
                    <span class="comanda-meta-label">Café</span>
                    <span class="comanda-meta-value" style="color: {{ isset($extrasObj->tiene_cafe) && $extrasObj->tiene_cafe ? 'green' : 'red' }}">{{ isset($extrasObj->tiene_cafe) && $extrasObj->tiene_cafe ? 'Sí' : 'No' }}</span>
                </div>
                @endif
            </section>
        </article>

        <!-- Sección de reporte por Categorías Ordenadas -->
        <section class="reportes-section" style="gap: 2rem;">
            @if($comandaGlobal->isEmpty())
                <article class="sucursal-card main-report-card" style="text-align: center; padding: 4rem 2rem;">
                    <p style="font-size: 1.2rem; color: var(--text-muted); font-weight: 700;">Este contrato aún no tiene platillos asignados en la comanda.</p>
                </article>
            @else
                <article class="sucursal-card main-report-card comanda-card">
                    @foreach($comandaGlobal as $categoria => $platillos)
                        @php
                            if (in_array($categoria, ['Entradas', 'Cremas y Sopas', 'Platos Fuertes', 'Guarniciones (Formales)'])) {
                                $grupoLabel = 'Servicio en Tiempos';
                            } elseif (in_array($categoria, ['Guisados', 'Parrillada (Carnes)', 'Guarniciones'])) {
                                $grupoLabel = 'Taquiza / Buffet';
                            } elseif (in_array($categoria, ['Menú Infantil', 'Buffet Infantil'])) {
                                $grupoLabel = 'Menú Infantil';
                            } elseif (in_array($categoria, ['Bebidas'])) {
                                $grupoLabel = 'Bar y Bebidas';
                            } elseif (in_array($categoria, ['Dulces', 'Postres'])) {
                                $grupoLabel = 'Postres y Dulces';
                            } else {
                                $grupoLabel = 'Otros Platillos';
                            }
                        @endphp

                        <div class="comanda-category-section" style="margin-bottom: 2rem;">
                            <header class="card-header" style="border-bottom: 2px solid rgba(122, 40, 138, 0.15); padding-bottom: 0.5rem; margin-bottom: 0.75rem; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap;">
                                <h2 class="card-title" style="margin: 0; display: flex; align-items: center; gap: 0.5rem; font-size: 1.35rem; font-weight: 900; color: var(--primary-purple);">
                                    <span>{{ $categoria }}</span>
                                    <span class="category-badge-group" style="font-size: 0.75rem; padding: 0.2rem 0.6rem; background: rgba(122, 40, 138, 0.08);">{{ $grupoLabel }}</span>
                                </h2>
                                <span style="font-size: 0.85rem; font-weight: 700; color: var(--text-muted); background: #f0f0f0; padding: 0.2rem 0.6rem; border-radius: 12px;">{{ count($platillos) }} platillo(s)</span>
                            </header>

                            <div class="comanda-list">
                                @foreach($platillos as $platillo)
                                    <div class="comanda-list-item">
                                        <div class="comanda-checkbox print-checkbox" title="Marcar como listo"></div>
                                        <div class="comanda-details">
                                            <p class="comanda-name">{{ $platillo['nombre'] }}</p>
                                            
                                            @if(!empty($platillo['ingredientes']))
                                                <div style="margin-top: 0.4rem; margin-bottom: 0.2rem; padding-left: 0.75rem; border-left: 3px solid rgba(122, 40, 138, 0.4);">
                                                    <p style="font-size: 0.75rem; font-weight: 800; color: var(--primary-purple); margin: 0 0 0.2rem 0; text-transform: uppercase; letter-spacing: 0.05em;">Ingredientes a picar / preparar:</p>
                                                    <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-wrap: wrap; gap: 0.4rem;">
                                                        @foreach($platillo['ingredientes'] as $nombreIng => $datosIng)
                                                            <li style="font-size: 0.8rem; background: #f8f9fa; padding: 0.2rem 0.5rem; border-radius: 6px; border: 1px solid #e9ecef; display: inline-flex; gap: 0.3rem;">
                                                                <strong style="color: #495057;">{{ $nombreIng }}:</strong> <span style="color: var(--primary-purple); font-weight: 700;">{{ $datosIng['format'] }}</span>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endif

                                            @foreach($platillo['salones'] as $salon)
                                                @if($salon['notas'] && !str_contains($salon['notas'], 'Registrado desde el configurador'))
                                                    <span class="nota-box" style="margin-top: 0.2rem; display: inline-block; padding: 0.2rem 0.5rem; font-size: 0.75rem;">Nota: {{ $salon['notas'] }}</span>
                                                @endif
                                            @endforeach
                                        </div>
                                        <div class="comanda-portions">
                                            {{ $platillo['porciones_totales'] }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </article>
            @endif

            <!-- Pie de página con acciones -->
            <footer class="comanda-actions-footer no-print" style="display: flex; justify-content: center; gap: 1rem; margin-top: 1rem; margin-bottom: 4rem; flex-wrap: wrap;">
                <button class="btn-print" onclick="window.print();" style="background: linear-gradient(135deg, var(--primary-purple), #4a148c); color: white; border: none; font-size: 1.1rem; font-weight: 800; padding: 0.8rem 2.5rem; border-radius: 2rem; box-shadow: 0 4px 15px rgba(122, 40, 138, 0.4); cursor: pointer; display: inline-flex; align-items: center; gap: 0.5rem; transition: all 0.3s ease;">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="22" height="22"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    Imprimir Comanda
                </button>

                <a href="{{ route('reportes.insumos', $contrato->evento->id) }}" class="btn-back-nav-glass" style="background: rgba(255, 255, 255, 0.8); color: var(--primary-purple); border-color: var(--border-color); font-size: 1.05rem; padding: 0.8rem 2rem;">
                    Ir a Lista de Insumos
                </a>
            </footer>
        </section>
    </main>
</body>
</html>
