<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FantaSync - Reporte de Insumos</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/fiori.css') }}">
    <link rel="stylesheet" href="{{ asset('css/reportes.css') }}">
</head>
<body>

<header class="page-header">
    <nav class="wrap">
        <a href="{{ route('dashboard') }}" class="btn secondary">← Volver al Panel</a>
    </nav>
</header>

<main class="reporte-container">
    <section class="card">
        <header class="card-header">
            <span class="card-icon">📋</span>
            <h1>Resumen de Producción: {{ $evento->titulo }}</h1>
        </header>
        <article class="info-content">
            <p><strong>Fecha del Evento:</strong> {{ $evento->fecha->format('d/m/Y') }}</p>
            <p><strong>Salones Reservados:</strong> 
                @foreach($evento->salones as $salon)
                    <mark class="badge total">{{ $salon->nombre }}</mark>
                @endforeach
            </p>
        </article>
    </section>

    <section class="card" style="margin-top: 2rem;">
        <header class="card-header">
            <span class="card-icon">🥘</span>
            <h2>Lista Consolidada de Insumos (Cocina)</h2>
        </header>

        <table class="tabla-reporte">
            <thead>
                <tr>
                    <th>Materia Prima / Insumo</th>
                    <th>Cantidad Necesaria</th>
                    <th>Unidad</th>
                    <th>Disponibilidad</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                @if(!empty($reporteInsumos))
                    @foreach($reporteInsumos as $insumo)
                        <tr>
                            <td><strong>{{ $insumo['nombre'] }}</strong></td>
                            <td>{{ $insumo['requerido'] }}</td>
                            <td><mark>{{ $insumo['unidad'] }}</mark></td>
                            <td>{{ $insumo['stock'] }} {{ $insumo['unidad'] }}</td>
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
                        <td colspan="5" style="text-align: center; padding: 2rem; color: #6c757d;">
                            No hay platillos asignados para calcular insumos.
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
        
        <footer class="report-button-container">
            <button class="btn primary" onclick="window.print();">🖨️ Imprimir Comanda</button>
        </footer>
    </section>
</main>

</body>
</html>