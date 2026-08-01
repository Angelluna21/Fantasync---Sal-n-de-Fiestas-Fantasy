<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recetario Global de Platillos</title>
    @vite(['resources/css/app.css', 'resources/css/reportes.css'])
</head>
<body class="report-body">
    <main class="report-container">
        <!-- Encabezado del Reporte -->
        <header class="report-header">
            <section class="report-brand">
                <img src="{{ asset('img/logo.png') }}" alt="Logo FantaSync" class="report-logo">
                <div class="brand-text">
                    <h1>Recetario Global de Platillos</h1>
                    <p>Catálogo de Ingredientes y Fórmulas Base</p>
                </div>
            </section>
            
            <section class="report-meta">
                <div class="meta-item">
                    <span class="meta-label">Fecha de Emisión:</span>
                    <span class="meta-value">{{ \Carbon\Carbon::now()->format('d/m/Y h:i A') }}</span>
                </div>
                <div class="meta-item">
                    <span class="meta-label">Total Platillos:</span>
                    <span class="meta-value">
                        {{ array_reduce($recetarioSorted, fn($carry, $cat) => $carry + count($cat), 0) }}
                    </span>
                </div>
            </section>
        </header>

        <section class="report-content">
            <article class="report-instructions mb-4">
                <p><strong>Nota para el equipo de cocina:</strong> Las cantidades mostradas en este recetario están calculadas por la <strong>fórmula base (100 porciones usualmente)</strong> y ya incluyen el redondeo comercial para facilitar la preparación.</p>
            </article>

            @foreach($recetarioSorted as $categoria => $platillos)
                @if(count($platillos) > 0)
                <section class="category-section" style="margin-top: 2rem;">
                    <h2 class="category-title" style="color: var(--primary-purple, #7a288a); border-bottom: 2px solid #eee; padding-bottom: 0.5rem; margin-bottom: 1rem;">
                        {{ $categoria }}
                    </h2>
                    
                    <div class="recipes-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1.5rem;">
                        @foreach($platillos as $item)
                            <article class="recipe-card" style="background: #fff; border: 1px solid #e0e0e0; border-radius: 8px; padding: 1.25rem; box-shadow: 0 2px 4px rgba(0,0,0,0.05); page-break-inside: avoid;">
                                <h3 style="margin-top: 0; color: #333; font-size: 1.15rem; margin-bottom: 1rem;">{{ $item['platillo']->nombre }}</h3>
                                
                                @if(count($item['ingredientes']) > 0)
                                    <ul class="ingredients-list" style="list-style: none; padding: 0; margin: 0;">
                                        @foreach($item['ingredientes'] as $ingrediente)
                                            <li style="display: flex; justify-content: space-between; border-bottom: 1px dashed #eee; padding: 0.4rem 0;">
                                                <span class="ingredient-name" style="color: #555;">
                                                    {{ $ingrediente['nombre'] }} 
                                                    @if($ingrediente['es_fijo'])
                                                        <small style="color: #888;">(Fijo)</small>
                                                    @endif
                                                </span>
                                                <strong class="ingredient-qty" style="color: #222;">
                                                    {{ $ingrediente['format'] }}
                                                </strong>
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p style="color: #999; font-style: italic; font-size: 0.9rem;">Sin ingredientes asignados.</p>
                                @endif
                            </article>
                        @endforeach
                    </div>
                </section>
                @endif
            @endforeach
        </section>
    </main>

    <footer class="report-footer">
        <p>Generado por FantaSync &copy; {{ date('Y') }}</p>
    </footer>

    <!-- Botón Flotante para Imprimir -->
    <button onclick="window.print()" class="fab-print" title="Imprimir Recetario">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
    </button>
</body>
</html>
