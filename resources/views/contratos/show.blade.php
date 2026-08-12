<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del Contrato #{{ $contrato->id }} · Fantasy</title>
    @vite(['resources/css/app.css', 'resources/css/dashboard.css', 'resources/css/eventos.css'])
    <style>
        .contract-detail-container {
            max-width: 1000px;
            margin: 0 auto;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            padding: 3rem;
            color: #3d1b4a;
        }
        .detail-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #f3e5f5;
            padding-bottom: 1.5rem;
            margin-bottom: 2rem;
        }
        .detail-header h2 {
            font-size: 2.2rem;
            color: #9b30b0;
            margin: 0;
        }
        .badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 800;
            font-size: 0.9rem;
            text-transform: uppercase;
        }
        .badge.finalizado { background: #e8f5e9; color: #2e7d32; }
        .badge.cancelado { background: #ffebee; color: #c62828; }
        .badge.confirmado { background: #e3f2fd; color: #1565c0; }
        .badge.cotizacion { background: #fff8e1; color: #f57f17; }
        
        .detail-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 2rem;
        }
        .detail-section {
            background: #fafafa;
            padding: 1.5rem;
            border-radius: 12px;
            border: 1px solid #eee;
        }
        .detail-section h3 {
            font-size: 1.3rem;
            color: #6a4a75;
            margin-bottom: 1rem;
            border-bottom: 1px solid #e0e0e0;
            padding-bottom: 0.5rem;
        }
        .detail-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.8rem;
            font-size: 1.05rem;
        }
        .detail-item strong {
            color: #555;
        }
        .finance-highlight {
            font-size: 1.5rem;
            font-weight: 900;
            color: #d81b60;
        }
    </style>
</head>
<body>
    <figure class="dashboard-background" aria-hidden="true"></figure>
    
    <main class="dashboard-layout">
        <nav class="top-nav" style="margin-bottom: 2rem;">
            <a href="{{ route('contratos.index') }}" class="btn-back-nav" style="padding: 0.5rem 1.2rem; background: rgba(255,255,255,0.2); color: white; border-radius: 8px; text-decoration: none;">
                ← Volver a Contratos
            </a>
        </nav>

        <article class="contract-detail-container">
            <header class="detail-header">
                <div>
                    <h2>Contrato #CNT-{{ str_pad($contrato->id, 5, '0', STR_PAD_LEFT) }}</h2>
                    <p style="color: #666; margin-top: 0.5rem;">Fecha de Emisión/Firma: {{ $contrato->fecha_firma ? $contrato->fecha_firma->format('d/m/Y') : $contrato->created_at->format('d/m/Y') }}</p>
                </div>
                <div>
                    @php
                        $estado = strtolower($contrato->evento->estado ?? 'cotizacion');
                    @endphp
                    <span class="badge {{ $estado }}">{{ ucfirst($estado) }}</span>
                </div>
            </header>

            <div class="detail-grid">
                <!-- Información del Cliente -->
                <section class="detail-section">
                    <h3>Datos del Cliente</h3>
                    <div class="detail-item"><strong>Nombre:</strong> <span>{{ $contrato->evento->cliente->nombre_completo ?? 'N/A' }}</span></div>
                    <div class="detail-item"><strong>Celular:</strong> <span>{{ $contrato->evento->cliente->celular ?? 'N/A' }}</span></div>
                    <div class="detail-item"><strong>Email:</strong> <span>{{ $contrato->evento->cliente->correo_electronico ?? 'N/A' }}</span></div>
                    <div class="detail-item"><strong>INE:</strong> <span>{{ $contrato->evento->cliente->ine_numero ?? 'N/A' }}</span></div>
                    <div class="detail-item"><strong>Domicilio:</strong> <span style="text-align: right; max-width: 60%;">{{ $contrato->evento->cliente->domicilio ?? 'N/A' }}</span></div>
                </section>

                <!-- Información del Evento -->
                <section class="detail-section">
                    <h3>Detalles del Evento</h3>
                    <div class="detail-item"><strong>Festejado:</strong> <span>{{ $contrato->evento->nombre_festejado ?? 'N/A' }}</span></div>
                    <div class="detail-item"><strong>Tipo:</strong> <span>{{ $contrato->evento->tipo_evento ?? 'N/A' }}</span></div>
                    <div class="detail-item"><strong>Fecha:</strong> <span>{{ $contrato->evento->fecha ? $contrato->evento->fecha->format('d/m/Y') : 'N/A' }}</span></div>
                    <div class="detail-item"><strong>Recepción:</strong> <span>{{ $contrato->evento->hora_recepcion ?? '00:00' }} hrs</span></div>
                    <div class="detail-item"><strong>Duración:</strong> <span>{{ $contrato->evento->horas_duracion ?? 0 }} hrs</span></div>
                    
                    @if($contrato->evento->salones->count() > 0)
                        @php $salonPivot = $contrato->evento->eventoSalones->first(); @endphp
                        <div class="detail-item"><strong>Adultos:</strong> <span>{{ $salonPivot->adultos ?? 0 }}</span></div>
                        <div class="detail-item"><strong>Niños:</strong> <span>{{ $salonPivot->ninos ?? 0 }}</span></div>
                        <div class="detail-item"><strong>Total Personas:</strong> <span>{{ $salonPivot->total_personas ?: ($salonPivot->adultos + $salonPivot->ninos) }}</span></div>
                    @endif

                    <h4 style="margin-top: 1.5rem; color: #555; font-size: 1.1rem; border-bottom: 1px solid #ccc; padding-bottom: 0.3rem;">Mantelería</h4>
                    <div class="detail-item"><strong>Color de Mantelería:</strong> <span>{{ $contrato->evento->color_manteleria ?: 'N/A' }}</span></div>
                    <div class="detail-item"><strong>Cubre Mantel:</strong> <span>{{ $contrato->evento->color_cubre_mantel ?: 'N/A' }}</span></div>
                    <div class="detail-item"><strong>Moños:</strong> <span>{{ $contrato->evento->color_monos ?: 'N/A' }}</span></div>
                    <div class="detail-item"><strong>Camino Mesa:</strong> <span>{{ $contrato->evento->color_camino_mesa ?: 'N/A' }}</span></div>
                </section>

                <!-- Finanzas -->
                <section class="detail-section">
                    <h3>Resumen Financiero</h3>
                    <div class="detail-item"><strong>Monto Total:</strong> <span>${{ number_format($contrato->monto_total, 2) }}</span></div>
                    <div class="detail-item"><strong>Total Abonado:</strong> <span style="color: #2e7d32; font-weight: 800;">${{ number_format($contrato->anticipo, 2) }}</span></div>
                    <div class="detail-item"><strong>Saldo Pendiente:</strong> <span class="finance-highlight">${{ number_format($contrato->saldo_pendiente, 2) }}</span></div>
                    
                    <h4 style="margin-top: 1.5rem; color: #555; font-size: 1.1rem; border-bottom: 1px solid #ccc; padding-bottom: 0.3rem;">Historial de Pagos</h4>
                    @php $pagos = $contrato->servicios_extras['historial_pagos'] ?? []; @endphp
                    @if(count($pagos) > 0)
                        <ul style="list-style: none; padding: 0; margin-top: 0.5rem;">
                            @foreach($pagos as $p)
                                <li style="display: flex; justify-content: space-between; padding: 0.3rem 0; border-bottom: 1px dashed #eee;">
                                    <span>Recibo: {{ $p['recibo'] ?? 'S/N' }} ({{ $p['fecha'] ?? '' }})</span>
                                    <strong>${{ number_format((float)($p['monto'] ?? 0), 2) }}</strong>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p style="color: #999; font-style: italic;">No hay pagos registrados.</p>
                    @endif
                </section>

                <!-- Desglose y Vendedoras -->
                <section class="detail-section">
                    <h3>Extras y Comisiones</h3>
                    @php $extras = $contrato->servicios_extras ?? []; @endphp
                    <div class="detail-item"><strong>¿Tiene Misa?:</strong> <span>{{ !empty($extras['tiene_misa']) ? 'Sí' : 'No' }}</span></div>
                    <div class="detail-item"><strong>Invitaciones:</strong> <span>{{ $extras['invitacion_estado'] ?? 'No especificadas' }}</span></div>
                    <div class="detail-item">
                        <strong>Piñata:</strong> 
                        <span>{{ !empty($extras['tiene_pinata']) ? 'Sí' . (!empty($extras['detalle_pinata']) ? ' (' . $extras['detalle_pinata'] . ')' : '') : 'No' }}</span>
                    </div>
                    <div class="detail-item">
                        <strong>Show:</strong> 
                        <span>{{ !empty($extras['tiene_show']) ? 'Sí' . (!empty($extras['detalle_show']) ? ' (' . $extras['detalle_show'] . ')' : '') : 'No' }}</span>
                    </div>
                    <div class="detail-item"><strong>Arco de Globos:</strong> <span>{{ !empty($extras['arco_globos']) ? 'Sí' : 'No' }}</span></div>
                    <div class="detail-item"><strong>Derecho de Pista:</strong> <span>{{ !empty($extras['derecho_pista_check']) ? 'Sí' : 'No' }}</span></div>
                    
                    <h4 style="margin-top: 1.5rem; color: #555; font-size: 1.1rem; border-bottom: 1px solid #ccc; padding-bottom: 0.3rem;">Vendedoras Asignadas</h4>
                    @if($contrato->vendedoras && $contrato->vendedoras->count() > 0)
                        <ul style="list-style: none; padding: 0; margin-top: 0.5rem;">
                            @foreach($contrato->vendedoras as $v)
                                <li style="padding: 0.3rem 0; font-weight: 600; color: #9b30b0;">• {{ $v->nombre }} {{ $v->apellidos }}</li>
                            @endforeach
                        </ul>
                    @else
                        <p style="color: #999; font-style: italic;">Sin vendedoras asignadas.</p>
                    @endif

                    <h4 style="margin-top: 1.5rem; color: #555; font-size: 1.1rem; border-bottom: 1px solid #ccc; padding-bottom: 0.3rem;">Venta de Hr. Extra</h4>
                    <p style="margin-top: 0.5rem; font-weight: 800; color: #d81b60;">
                        @php 
                            $quien = $extras['quien_vendio_hora_extra'] ?? ''; 
                            $nombreExtra = 'Ninguno / Incluido';
                            if ($quien === 'capitan') $nombreExtra = 'Capitán de Meseros';
                            elseif (str_starts_with($quien, 'vendedora_')) {
                                $vid = str_replace('vendedora_', '', $quien);
                                $vend = \App\Models\Vendedora::find($vid);
                                if($vend) $nombreExtra = $vend->nombre . ' ' . $vend->apellidos;
                            }
                        @endphp
                        {{ $nombreExtra }}
                    </p>
                </section>
            </div>
            
            <div style="margin-top: 3rem; text-align: center;">
                <a href="{{ route('contratos.pdf', $contrato->id) }}" target="_blank" class="btn-submit" style="display: inline-block; padding: 1rem 2rem; background: #6a4a75; color: white; border-radius: 8px; text-decoration: none; font-weight: bold; font-size: 1.1rem;">
                    Descargar o Imprimir PDF
                </a>
            </div>
        </article>
    </main>
</body>
</html>
