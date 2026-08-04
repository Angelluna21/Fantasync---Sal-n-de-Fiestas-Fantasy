<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recetario Global de Platillos</title>
    @vite(['resources/css/app.css', 'resources/css/dashboard.css'])
    <style>
        .recetario-layout {
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem;
            position: relative;
            z-index: 10;
        }
        .header-glass {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.4);
            border-radius: 24px;
            padding: 2rem 3rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 3rem;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.05);
        }
        .brand-text h1 {
            font-size: 2.2rem;
            font-weight: 800;
            color: var(--primary-purple);
            margin: 0 0 0.5rem 0;
            line-height: 1.1;
        }
        .brand-text p {
            color: var(--text-muted);
            font-size: 1.1rem;
            margin: 0;
        }
        .meta-container {
            text-align: right;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }
        .meta-item {
            background: rgba(255, 255, 255, 0.5);
            padding: 0.5rem 1rem;
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.6);
            display: inline-flex;
            gap: 0.5rem;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.02);
        }
        .meta-label {
            font-size: 0.85rem;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-weight: 700;
        }
        .meta-value {
            font-weight: 800;
            color: var(--accent-magenta);
        }
        .info-alert {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(216, 27, 96, 0.3);
            border-left: 5px solid var(--accent-magenta);
            padding: 1.25rem 2rem;
            border-radius: 12px;
            margin-bottom: 3rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }
        .category-title-glass {
            font-size: 1.6rem;
            color: var(--primary-purple);
            font-weight: 800;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .category-title-glass::after {
            content: '';
            flex: 1;
            height: 2px;
            background: linear-gradient(90deg, rgba(122, 40, 138, 0.15) 0%, transparent 100%);
        }
        .recipes-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 2rem;
            margin-bottom: 4rem;
        }
        .recipe-glass-card {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.6);
            border-radius: 20px;
            padding: 1.75rem;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.03);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        .recipe-glass-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-purple), var(--accent-magenta));
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        .recipe-glass-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.08);
            border-color: rgba(216, 27, 96, 0.2);
        }
        .recipe-glass-card:hover::before {
            opacity: 1;
        }
        .recipe-name {
            font-size: 1.25rem;
            color: var(--text-main);
            font-weight: 800;
            margin-top: 0;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .ingredients-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .ingredient-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.6rem 0;
            border-bottom: 1px dashed rgba(0, 0, 0, 0.06);
        }
        .ingredient-item:last-child {
            border-bottom: none;
        }
        .ingredient-name {
            color: #444;
            font-size: 0.95rem;
            font-weight: 500;
        }
        .ingredient-qty {
            background: rgba(122, 40, 138, 0.06);
            color: var(--primary-purple);
            padding: 0.25rem 0.75rem;
            border-radius: 2rem;
            font-size: 0.85rem;
            font-weight: 700;
        }
        .btn-fab-print {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            width: 65px;
            height: 65px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-purple), var(--accent-magenta));
            color: white;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 25px rgba(216, 27, 96, 0.4);
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 100;
        }
        .btn-fab-print:hover {
            transform: scale(1.1) rotate(5deg);
            box-shadow: 0 12px 35px rgba(216, 27, 96, 0.5);
        }
        .btn-fab-print svg {
            width: 28px;
            height: 28px;
        }
        
        @media print {
            .dashboard-background, .btn-fab-print, .info-alert {
                display: none !important;
            }
            body {
                background: white !important;
            }
            .header-glass {
                background: none !important;
                border: none !important;
                box-shadow: none !important;
                padding: 0 !important;
                margin-bottom: 1.5rem !important;
                border-bottom: 2px solid #000 !important;
                border-radius: 0 !important;
            }
            .brand-text h1 {
                color: #000 !important;
                font-size: 1.5rem !important;
            }
            .meta-item {
                border: none !important;
                box-shadow: none !important;
                background: none !important;
                padding: 0 !important;
            }
            .meta-value, .meta-label {
                color: #000 !important;
            }
            .category-title-glass {
                color: #000 !important;
                font-size: 1.2rem !important;
                margin-bottom: 0.5rem !important;
            }
            .category-title-glass::after {
                background: #000 !important;
                height: 1px !important;
            }
            .recipes-grid {
                display: block !important;
                column-count: 2;
                column-gap: 1.5rem;
                margin-bottom: 2rem !important;
            }
            .recipe-glass-card {
                background: none !important;
                border: 1px solid #ccc !important;
                box-shadow: none !important;
                padding: 0.75rem !important;
                margin-bottom: 1rem !important;
                page-break-inside: avoid;
                break-inside: avoid;
                border-radius: 4px !important;
            }
            .recipe-name {
                font-size: 1rem !important;
                color: #000 !important;
                margin-bottom: 0.5rem !important;
            }
            .ingredient-item {
                padding: 0.15rem 0 !important;
                border-bottom: 1px solid #eee !important;
            }
            .ingredient-name {
                font-size: 0.8rem !important;
            }
            .ingredient-qty {
                background: none !important;
                color: #000 !important;
                padding: 0 !important;
                font-size: 0.8rem !important;
            }
        }
    </style>
</head>
<body>
    <figure class="dashboard-background" aria-hidden="true"></figure>

    <main class="recetario-layout">
        <!-- Navegación y Encabezado del Reporte -->
        <header class="header-glass">
            <section style="display: flex; align-items: center; gap: 1.5rem;">
                <img src="{{ asset('img/logo.png') }}" alt="Logo FantaSync" style="height: 80px; filter: drop-shadow(0 4px 6px rgba(0,0,0,0.1));">
                <div class="brand-text">
                    <h1>Recetario Global de Platillos</h1>
                    <p>Catálogo de Ingredientes y Fórmulas Base</p>
                </div>
            </section>
            
            <section class="meta-container">
                <div class="meta-item">
                    <span class="meta-label">Fecha de Emisión:</span>
                    <span class="meta-value">{{ \Carbon\Carbon::now()->format('d/m/Y h:i A') }}</span>
                </div>
                <div class="meta-item">
                    <span class="meta-label">Total de Fórmulas:</span>
                    <span class="meta-value">
                        {{ array_reduce($recetarioSorted, fn($carry, $cat) => $carry + count($cat), 0) }}
                    </span>
                </div>
            </section>
        </header>

        <section class="info-alert" style="margin-top: 2rem;">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 24px; height: 24px; color: var(--accent-magenta);">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <p style="margin: 0; color: var(--text-main); font-size: 0.95rem;">
                <strong>Nota Operativa:</strong> Las cantidades en este recetario están calculadas sobre la <strong>fórmula base (100 porciones usualmente)</strong> e incluyen márgenes de redondeo comercial para facilitar la preparación en cocina.
            </p>
        </section>

        @foreach($recetarioSorted as $categoria => $platillos)
            @if(count($platillos) > 0)
            <section style="margin-top: 1rem;">
                <h2 class="category-title-glass">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 24px; height: 24px;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    {{ $categoria }}
                </h2>
                
                <div class="recipes-grid">
                    @foreach($platillos as $item)
                        <article class="recipe-glass-card">
                            <h3 class="recipe-name">
                                {{ $item['platillo']->nombre }}
                            </h3>
                            
                            @if(count($item['ingredientes']) > 0)
                                <ul class="ingredients-list">
                                    @foreach($item['ingredientes'] as $ingrediente)
                                        <li class="ingredient-item">
                                            <span class="ingredient-name">
                                                {{ $ingrediente['nombre'] }} 
                                                @if($ingrediente['es_fijo'])
                                                    <span style="font-size: 0.75rem; color: #888; background: #eee; padding: 2px 6px; border-radius: 4px; margin-left: 4px;">Fijo</span>
                                                @endif
                                            </span>
                                            <span class="ingredient-qty">
                                                {{ $ingrediente['format'] }}
                                            </span>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p style="color: #a0a0a0; font-style: italic; font-size: 0.9rem; text-align: center; padding: 1rem 0;">Fórmula sin ingredientes registrados</p>
                            @endif
                        </article>
                    @endforeach
                </div>
            </section>
            @endif
        @endforeach
    </main>

    <!-- Botón Flotante para Imprimir -->
    <button onclick="window.print()" class="btn-fab-print" title="Imprimir Recetario">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
    </button>
</body>
</html>
