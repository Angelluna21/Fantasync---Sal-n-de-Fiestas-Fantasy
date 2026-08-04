<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estadísticas Vendedoras · FantaSync</title>
    @vite(['resources/css/app.css', 'resources/css/dashboard.css', 'resources/css/salones.css'])
    <style>
        .stats-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            background: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid rgba(122, 40, 138, 0.2);
            box-shadow: 0 4px 15px rgba(122, 40, 138, 0.08);
        }
        .stats-table th {
            background: #7a288a;
            color: #ffffff;
            font-weight: 700;
            padding: 1rem;
            text-align: left;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }
        .stats-table td {
            padding: 1rem;
            border-bottom: 1px solid rgba(122, 40, 138, 0.1);
            color: #3d1b4a;
            font-size: 1rem;
        }
        .stats-table tr:last-child td {
            border-bottom: none;
        }
        .stats-table tr:hover td {
            background: #fdfaf6;
        }
        .amount-highlight {
            color: #d81b60;
            font-weight: 800;
            font-size: 1.1rem;
        }
        .filter-form {
            display: flex;
            gap: 1rem;
            align-items: center;
            background: #ffffff;
            padding: 1rem;
            border-radius: 12px;
            margin-bottom: 2rem;
            border: 1px solid rgba(122, 40, 138, 0.2);
            box-shadow: 0 4px 15px rgba(122, 40, 138, 0.08);
        }
        .filter-select {
            background: #fcfbfe;
            border: 2px solid rgba(122, 40, 138, 0.2);
            color: #3d1b4a;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-size: 1rem;
            outline: none;
            font-weight: 600;
        }
        .filter-select:focus {
            border-color: #ffd54f;
        }
    </style>
</head>
<body>
    <figure class="dashboard-background" aria-hidden="true"></figure>
    
    <main class="dashboard-layout">
        <!-- Navegación superior y Encabezado Unificado -->
        <nav class="top-nav" style="align-items: flex-start; margin-bottom: 2rem; padding-bottom: 0;">
            <section style="display: flex; flex-direction: column; gap: 0.5rem; flex: 1;">
                <a href="{{ route('dashboard') }}" aria-label="Volver al panel" class="logo-link" style="width: fit-content;">
                    <img src="{{ asset('img/logo.png') }}" alt="Logo FantaSync" class="nav-logo" style="height: 100px;">
                </a>
                
                <section style="display: flex; gap: 0.5rem;">
                    <a href="{{ route('vendedoras.index') }}" class="btn-back-nav" style="width: fit-content; margin-bottom: 0; padding: 0.4rem 1rem; font-size: 0.85rem; background: rgba(255, 255, 255, 0.15); border: 1px solid rgba(255, 255, 255, 0.3); color: white;">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        Volver a Vendedoras
                    </a>
                </section>
            </section>

            <header class="dashboard-header" style="margin: 3rem 0 0 0; flex: 2; display: flex; flex-direction: column; justify-content: center; max-width: none; text-align: center;">
                <hgroup style="text-align: center;">
                    <p class="eyebrow" style="margin: 0 auto; color: rgba(255, 255, 255, 0.8);">Reportes y Métricas</p>
                    <h1 class="dashboard-title" style="margin: 0 auto; font-size: 2.5rem; color: white;">Estadísticas de Ventas</h1>
                    <p class="dashboard-description" style="margin: 0.5rem auto 0; max-width: 600px; color: rgba(255, 255, 255, 0.9);">Compara el rendimiento de las vendedoras por volumen de contratos y ventas generadas.</p>
                </hgroup>
            </header>

            <aside style="flex: 1; display: flex; justify-content: flex-end; padding-top: 15px;">
                <x-user-menu />
            </aside>
        </nav>

        <section class="salones-section" style="max-width: 1000px; margin: 0 auto;">
            
            <!-- Filtro -->
            <form action="{{ route('vendedoras.estadisticas') }}" method="GET" class="filter-form" style="flex-wrap: wrap;">
                
                <div style="display: flex; gap: 0.5rem; align-items: center;">
                    <label for="vendedora_id" style="color: #7a288a; font-weight: 800;">Vendedora:</label>
                    <select name="vendedora_id" id="vendedora_id" class="filter-select" onchange="this.form.submit()">
                        <option value="todas" {{ (isset($vendedoraId) && $vendedoraId == 'todas') ? 'selected' : '' }}>Todas las Vendedoras</option>
                        @foreach($todasLasVendedoras as $v)
                            <option value="{{ $v->id }}" {{ (isset($vendedoraId) && $vendedoraId == $v->id) ? 'selected' : '' }}>{{ $v->nombre }} {{ $v->apellidos }}</option>
                        @endforeach
                    </select>
                </div>

                <div style="display: flex; gap: 0.5rem; align-items: center;">
                    <label for="periodo" style="color: #7a288a; font-weight: 800;">Periodo para Ventas:</label>
                    <select name="periodo" id="periodo" class="filter-select" onchange="this.form.submit()">
                        <option value="todos" {{ $periodo == 'todos' ? 'selected' : '' }}>Histórico Completo (Todos)</option>
                        <option value="semana" {{ $periodo == 'semana' ? 'selected' : '' }}>Esta Semana</option>
                        <option value="mes" {{ $periodo == 'mes' ? 'selected' : '' }}>Este Mes</option>
                        <option value="anio" {{ $periodo == 'anio' ? 'selected' : '' }}>Este Año</option>
                    </select>
                </div>
            </form>

            <!-- Tabla de Estadísticas -->
            <table class="stats-table">
                <thead>
                    <tr>
                        <th>Pos.</th>
                        <th>Vendedora</th>
                        @if($periodo === 'todos')
                            <th style="text-align: center; border-left: 1px solid rgba(122, 40, 138, 0.2);" title="Vendidos Esta Semana">C. Sem</th>
                            <th style="text-align: center;" title="Vendidos Este Mes">C. Mes</th>
                            <th style="text-align: center;" title="Vendidos Este Año">C. Año</th>
                            <th style="text-align: center; border-right: 1px solid rgba(122, 40, 138, 0.2);" title="Total Histórico">C. Total</th>
                        @else
                            <th style="text-align: center;">Contratos Cerrados</th>
                        @endif
                        <th style="text-align: right;">Vendido Bruto</th>
                        <th style="text-align: right; color: #d81b60;">Desc. Hr Extra</th>
                        <th style="text-align: right; color: #2e7d32;">Bono (10%)</th>
                        <th style="text-align: right; color: #ffd54f;">Comisiones (Neta)</th>
                    </tr>
                </thead>
                <tbody>
                    @php 
                        $pos = 1; 
                        $totalVentas = 0; 
                        $totalContratos = 0; 
                        $totalSem = 0;
                        $totalMes = 0;
                        $totalAnio = 0;
                        $totalHist = 0;
                        $totalDescuentos = 0; 
                        $totalBonos = 0; 
                        $totalComisiones = 0; 
                    @endphp
                    @forelse($stats as $stat)
                        @php
                            $totalVentas += $stat['monto_total'];
                            $totalContratos += $stat['cantidad_contratos'];
                            $totalSem += $stat['cnt_semana'];
                            $totalMes += $stat['cnt_mes'];
                            $totalAnio += $stat['cnt_anio'];
                            $totalHist += $stat['cnt_historico'];
                            $totalDescuentos += $stat['monto_descontado'];
                            $totalBonos += $stat['bono_extras'];
                            $totalComisiones += $stat['comisiones'];
                        @endphp
                        <tr>
                            <td style="font-weight: 800; color: #9b30b0;">#{{ $pos++ }}</td>
                            <td>
                                <strong>{{ $stat['vendedora']->nombre }} {{ $stat['vendedora']->apellidos }}</strong>
                                @if($stat['vendedora']->estado !== 'activo')
                                    <span style="font-size: 0.75rem; color: #d81b60; margin-left: 0.5rem;">(Inactiva)</span>
                                @endif
                            </td>
                            @if($periodo === 'todos')
                                <td style="text-align: center; color: #555; border-left: 1px dashed rgba(122, 40, 138, 0.1);">{{ $stat['cnt_semana'] }}</td>
                                <td style="text-align: center; color: #555;">{{ $stat['cnt_mes'] }}</td>
                                <td style="text-align: center; color: #555;">{{ $stat['cnt_anio'] }}</td>
                                <td style="text-align: center; font-size: 1.1rem; font-weight: 800; color: #3d1b4a; border-right: 1px dashed rgba(122, 40, 138, 0.1);">{{ $stat['cnt_historico'] }}</td>
                            @else
                                <td style="text-align: center; font-size: 1.1rem; font-weight: 800; color: #3d1b4a;">
                                    {{ $stat['cantidad_contratos'] }}
                                </td>
                            @endif
                            <td style="text-align: right; font-weight: 600;">
                                ${{ number_format($stat['monto_total'], 2) }}
                            </td>
                            <td style="text-align: right; color: #d81b60; font-size: 0.95rem;">
                                -${{ number_format($stat['monto_descontado'], 2) }}
                            </td>
                            <td style="text-align: right; color: #2e7d32; font-size: 0.95rem;">
                                +${{ number_format($stat['bono_extras'], 2) }}
                            </td>
                            <td style="text-align: right; color: #2e7d32; font-weight: 800; font-size: 1.1rem;">
                                ${{ number_format($stat['comisiones'], 2) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 2rem; color: #6a4a75;">
                                No hay datos para el periodo seleccionado.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="2" style="text-align: right; background: #fdfaf6; color: #3d1b4a;">TOTALES:</th>
                        @if($periodo === 'todos')
                            <th style="text-align: center; background: #fdfaf6; font-size: 1.1rem; color: #3d1b4a; border-left: 1px solid rgba(122, 40, 138, 0.2);">{{ $totalSem }}</th>
                            <th style="text-align: center; background: #fdfaf6; font-size: 1.1rem; color: #3d1b4a;">{{ $totalMes }}</th>
                            <th style="text-align: center; background: #fdfaf6; font-size: 1.1rem; color: #3d1b4a;">{{ $totalAnio }}</th>
                            <th style="text-align: center; background: #fdfaf6; font-size: 1.1rem; color: #3d1b4a; border-right: 1px solid rgba(122, 40, 138, 0.2);">{{ $totalHist }}</th>
                        @else
                            <th style="text-align: center; background: #fdfaf6; font-size: 1.1rem; color: #3d1b4a;">{{ $totalContratos }}</th>
                        @endif
                        <th style="text-align: right; background: #fdfaf6; font-size: 1.1rem; color: #3d1b4a; font-weight: 900;">
                            ${{ number_format($totalVentas, 2) }}
                        </th>
                        <th style="text-align: right; background: #fdfaf6; font-size: 1.0rem; color: #d81b60;">
                            -${{ number_format($totalDescuentos, 2) }}
                        </th>
                        <th style="text-align: right; background: #fdfaf6; font-size: 1.0rem; color: #2e7d32;">
                            +${{ number_format($totalBonos, 2) }}
                        </th>
                        <th style="text-align: right; background: #fdfaf6; font-size: 1.2rem; color: #2e7d32; font-weight: 900;">
                            ${{ number_format($totalComisiones, 2) }}
                        </th>
                    </tr>
                </tfoot>
            </table>

        </section>
    </main>

    <footer class="dashboard-footer">
        <p>© 2026 FantaSync · Sistema de Gestión de Eventos Gastronómicos</p>
    </footer>
</body>
</html>
