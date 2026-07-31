<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Contrato de Evento #{{ str_pad($contrato->id, 5, '0', STR_PAD_LEFT) }}</title>
    <style>
        @page {
            margin: 30px;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #222;
            line-height: 1.2;
            margin: 0;
            padding: 0;
            font-size: 10px;
        }
        
        /* Typography */
        h1, h2, h3, p { margin: 0; padding: 0; }
        
        /* Brand Colors */
        .primary { color: #7a288a; }
        .bg-primary { background-color: #7a288a; color: white; }
        .bg-light { background-color: #f4f0f5; }
        
        /* Header */
        .header-table {
            width: 100%;
            border-bottom: 2px solid #7a288a;
            padding-bottom: 5px;
            margin-bottom: 15px;
        }
        .header-table td {
            vertical-align: bottom;
        }
        .title {
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
            color: #7a288a;
        }
        .subtitle {
            font-size: 9px;
            color: #555;
            letter-spacing: 0.5px;
        }
        .folio-box {
            text-align: right;
        }
        .folio-box div {
            font-size: 14px;
            font-weight: bold;
            color: #333;
        }
        .folio-box span {
            font-size: 9px;
            color: #666;
        }

        /* Sections */
        .section-header {
            background-color: #7a288a;
            color: white;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            padding: 4px 8px;
            margin: 10px 0 5px;
            border-radius: 3px;
        }
        
        /* Two Column Layout (Client / Event) */
        .split-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 10px 0;
            margin-left: -10px;
            margin-bottom: 10px;
        }
        .split-table td.col {
            width: 50%;
            vertical-align: top;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 8px;
            background-color: #fafafa;
        }
        
        .info-row {
            margin-bottom: 4px;
        }
        .label {
            font-weight: bold;
            color: #555;
            display: inline-block;
            width: 90px;
        }
        .value {
            color: #111;
        }

        /* Compact Grids (Extras, Menu, Finance) */
        .grid-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            border-radius: 4px;
            overflow: hidden;
            border: 1px solid #ddd;
        }
        .grid-table th {
            background-color: #f4f0f5;
            color: #7a288a;
            font-weight: bold;
            font-size: 9px;
            text-transform: uppercase;
            padding: 5px;
            border-bottom: 1px solid #ddd;
            border-right: 1px solid #ddd;
            text-align: center;
        }
        .grid-table td {
            padding: 5px;
            border-bottom: 1px solid #eee;
            border-right: 1px solid #eee;
            text-align: center;
            font-size: 9px;
        }
        .grid-table th:last-child, .grid-table td:last-child {
            border-right: none;
        }
        .grid-table tr:last-child td {
            border-bottom: none;
        }
        .text-left { text-align: left !important; }
        .text-right { text-align: right !important; }

        /* Finance Layout */
        .finance-wrapper {
            width: 100%;
            margin-bottom: 10px;
        }
        .payments-side {
            width: 58%;
            float: left;
        }
        .totals-side {
            width: 40%;
            float: right;
        }
        .totals-box {
            border: 1px solid #7a288a;
            border-radius: 4px;
            background-color: #fdfafc;
            padding: 8px;
        }
        .total-row {
            width: 100%;
            margin-bottom: 4px;
        }
        .total-label {
            display: inline-block;
            width: 60%;
            font-weight: bold;
            color: #555;
            text-align: right;
        }
        .total-val {
            display: inline-block;
            width: 38%;
            text-align: right;
        }
        .grand-total {
            border-top: 1px solid #7a288a;
            margin-top: 4px;
            padding-top: 4px;
            font-size: 12px;
            color: #7a288a;
        }
        .grand-total .total-label, .grand-total .total-val {
            font-weight: bold;
            color: #7a288a;
        }
        
        .clear { clear: both; }

        /* Clauses */
        .clauses-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 15px 0;
            margin-left: -15px;
            margin-top: 15px;
        }
        .clauses-table td {
            width: 50%;
            vertical-align: top;
            font-size: 7.5px;
            color: #555;
            text-align: justify;
        }
        .clauses-title {
            font-weight: bold;
            font-size: 8px;
            color: #333;
            margin-bottom: 3px;
        }

        /* Signatures */
        .signatures {
            width: 100%;
            margin-top: 25px;
            page-break-inside: avoid;
        }
        .signatures td {
            width: 50%;
            text-align: center;
            vertical-align: bottom;
            padding: 10px;
        }
        .sign-line {
            border-bottom: 1px solid #333;
            width: 75%;
            margin: 0 auto 5px;
            height: 35px;
        }
        .sign-name {
            font-size: 10px;
            font-weight: bold;
            color: #222;
        }
        .sign-desc {
            font-size: 8px;
            color: #777;
        }
        
        .badge {
            background: #7a288a;
            color: white;
            padding: 1px 4px;
            border-radius: 2px;
            font-size: 8px;
        }
    </style>
</head>
<body>

    <!-- Header -->
    <table class="header-table">
        <tr>
            <td style="width: 60%;">
                <div class="title">FantaSync Eventos</div>
                <div class="subtitle">CONTRATO DE ARRENDAMIENTO Y PRESTACIÓN DE SERVICIOS</div>
            </td>
            <td class="folio-box" style="width: 40%;">
                <span>FOLIO DEL CONTRATO</span>
                <div>#CNT-{{ str_pad($contrato->id, 5, '0', STR_PAD_LEFT) }}</div>
                <span>FECHA: {{ $contrato->created_at->format('d / m / Y') }}</span>
            </td>
        </tr>
    </table>

    <!-- Declaracion -->
    <p style="font-size: 9px; text-align: justify; margin-bottom: 10px; color: #444;">
        Conste por el presente documento, el Contrato de Prestación de Servicios que celebran 
        <strong>FantaSync Eventos</strong> y el/la C. <strong>{{ $contrato->evento->cliente->nombre_completo ?? '___________________________' }}</strong> ("EL CLIENTE"), 
        sujetándose a las declaraciones y cláusulas operativas detalladas a continuación.
    </p>

    <!-- Client and Event Details (2 Columns) -->
    <table class="split-table">
        <tr>
            <!-- Columna Izquierda: Cliente -->
            <td class="col">
                <div class="section-header" style="margin-top: 0;">1. Datos del Cliente</div>
                <div class="info-row"><span class="label">Titular:</span> <span class="value">{{ mb_strtoupper($contrato->evento->cliente->nombre_completo ?? 'N/A') }}</span></div>
                <div class="info-row"><span class="label">Teléfono:</span> <span class="value">{{ $contrato->evento->cliente->celular ?? 'N/A' }}</span></div>
                <div class="info-row"><span class="label">Domicilio:</span> <span class="value">{{ $contrato->evento->cliente->domicilio ?? 'N/A' }}</span></div>
                <div class="info-row"><span class="label">INE/Folio:</span> <span class="value">{{ $contrato->evento->cliente->ine_numero ?? 'N/A' }}</span></div>
            </td>
            
            <!-- Columna Derecha: Evento -->
            <td class="col">
                <div class="section-header" style="margin-top: 0;">2. Detalles del Evento</div>
                <div class="info-row"><span class="label">Tipo/Cliente:</span> <span class="value">{{ mb_strtoupper($contrato->evento->tipo_evento) }} / {{ $contrato->evento->cliente->nombre_completo ?? 'N/A' }}</span></div>
                <div class="info-row"><span class="label">Fecha y Horario:</span> <span class="value">{{ $contrato->evento->fecha->format('d/m/Y') }} | {{ \Carbon\Carbon::parse($contrato->evento->hora_inicio)->format('H:i') }} a {{ \Carbon\Carbon::parse($contrato->evento->hora_inicio)->addHours($contrato->evento->horas_duracion)->format('H:i') }} hrs</span></div>
                <div class="info-row">
                    <span class="label">Salón(es):</span> 
                    <span class="value">
                        @foreach($contrato->evento->salones as $salon)
                            {{ $salon->nombre }}@if(!$loop->last), @endif
                        @endforeach
                    </span>
                </div>
                <div class="info-row">
                    <span class="label">Capacidad:</span> 
                    <span class="value">
                        @php
                            $totalAdultos = $contrato->evento->salones->sum('pivot.adultos');
                            $totalNinos = $contrato->evento->salones->sum('pivot.ninos');
                        @endphp
                        {{ $totalAdultos }} Adultos, {{ $totalNinos }} Niños <span class="badge">Total: {{ $totalAdultos + $totalNinos }}</span>
                    </span>
                </div>
            </td>
        </tr>
    </table>

    <!-- Configuration Matrix -->
    <div class="section-header">3. Especificaciones Operativas y Complementos</div>
    <table class="grid-table">
        <tr>
            <th>Horas Base</th>
            <th>H. Adicionales</th>
            <th>Hrs Recepción</th>
            <th>Hrs Inicio</th>
            <th>Invitación</th>
            <th>Misa</th>
            <th>Piñata</th>
            <th>Show</th>
            <th>Globos</th>
        </tr>
        <tr>
            <td><strong>6 hrs</strong></td>
            <td><strong>{{ $contrato->servicios_extras['horas_adicionales'] ?? '0' }} hrs</strong></td>
            <td>{{ $contrato->servicios_extras['recepcion_hora'] ?? '--:--' }}</td>
            <td>{{ $contrato->servicios_extras['inicio_hora'] ?? '--:--' }}</td>
            <td>
                @if(!empty($contrato->servicios_extras['invitacion_estado']))
                    {{ $contrato->servicios_extras['invitacion_estado'] }}
                    @if(!empty($contrato->servicios_extras['invitacion_detalle']))
                        ({{ $contrato->servicios_extras['invitacion_detalle'] }})
                    @endif
                @else
                    {{ $contrato->servicios_extras['invitacion'] ?? 'No' }}
                @endif
            </td>
            <td>{!! !empty($contrato->servicios_extras['tiene_misa']) ? '<span style="color:#7a288a;font-weight:bold;">Sí</span>' : 'No' !!}</td>
            <td>{!! !empty($contrato->servicios_extras['tiene_pinata']) ? '<span style="color:#7a288a;font-weight:bold;">Sí</span>' : 'No' !!}</td>
            <td>{!! !empty($contrato->servicios_extras['tiene_show']) ? '<span style="color:#7a288a;font-weight:bold;">Sí</span>' : 'No' !!}</td>
            <td>{!! !empty($contrato->servicios_extras['arco_globos']) ? '<span style="color:#7a288a;font-weight:bold;">Sí</span>' : 'No' !!}</td>
        </tr>
    </table>

    <!-- Menu -->
    @if($contrato->evento->eventoSalones->isNotEmpty() && $contrato->evento->eventoSalones->first()->platillos->isNotEmpty())
    <div class="section-header">4. Servicio Gastronómico Acordado</div>
    <table class="grid-table">
        <tr>
            <th class="text-left" style="width: 100%;">Menú / Elemento Gastronómico</th>
        </tr>
        @foreach($contrato->evento->eventoSalones->first()->platillos as $platillo)
        <tr>
            <td class="text-left">{{ $platillo->nombre }}</td>
        </tr>
        @endforeach
    </table>
    @endif

    <!-- Finance -->
    <div class="section-header">5. Resumen Financiero y Pagos</div>
    <div class="finance-wrapper">
        <div class="payments-side">
            <table class="grid-table" style="margin-bottom: 0;">
                <tr>
                    <th colspan="3" class="text-left" style="background:#fff; border:none; padding-bottom:5px;">HISTORIAL DE PAGOS / ABONOS</th>
                </tr>
                <tr>
                    <th class="text-left">Fecha</th>
                    <th class="text-left">Recibo / Referencia</th>
                    <th class="text-right">Monto</th>
                </tr>
                @if(!empty($contrato->servicios_extras['historial_pagos']))
                    @foreach($contrato->servicios_extras['historial_pagos'] as $pago)
                        @if(!empty($pago['monto']))
                        <tr>
                            <td class="text-left">{{ $pago['fecha'] ?? 'N/A' }}</td>
                            <td class="text-left">{{ $pago['recibo'] ?? 'N/A' }}</td>
                            <td class="text-right">${{ number_format((float)$pago['monto'], 2) }}</td>
                        </tr>
                        @endif
                    @endforeach
                @else
                    <tr>
                        <td colspan="3" class="text-left" style="color: #888; font-style: italic;">No se han registrado abonos.</td>
                    </tr>
                @endif
            </table>
        </div>

        <div class="totals-side">
            <div class="totals-box">
                <div class="total-row">
                    <span class="total-label">Costo Total del Evento:</span>
                    <span class="total-val">${{ number_format($contrato->monto_total, 2) }}</span>
                </div>
                <div class="total-row">
                    <span class="total-label">Abonos Recibidos:</span>
                    <span class="total-val">-${{ number_format($contrato->monto_total - $contrato->saldo_pendiente, 2) }}</span>
                </div>
                <div class="total-row grand-total">
                    <span class="total-label">{{ $contrato->saldo_pendiente < 0 ? 'SALDO A FAVOR:' : 'SALDO PENDIENTE:' }}</span>
                    <span class="total-val">${{ number_format(abs($contrato->saldo_pendiente), 2) }}</span>
                </div>
            </div>
        </div>
        <div class="clear"></div>
    </div>

    <!-- Clauses (2 columns) -->
    <table class="clauses-table">
        <tr>
            <td>
                <div class="clauses-title">PRIMERA. PRESTACIÓN DEL SERVICIO Y AFORO</div>
                El prestador se compromete a facilitar las instalaciones del salón y los servicios descritos en este contrato. EL CLIENTE se compromete a utilizar las instalaciones exclusivamente para el propósito declarado y a respetar la capacidad máxima permitida de invitados. Si se excede, el prestador se reserva el derecho de negar el acceso a personas adicionales por motivos de protección civil.
                
                <br><br><div class="clauses-title">SEGUNDA. PAGOS Y CANCELACIONES</div>
                El saldo pendiente debe ser liquidado íntegramente a más tardar 15 días previos a la realización del evento. De no cumplirse este plazo, el prestador podrá cancelar el evento sin responsabilidad. En caso de cancelación o aplazamiento por parte de EL CLIENTE, los anticipos no serán reembolsables bajo ninguna circunstancia, aplicando como penalización por gastos administrativos y bloqueo de la fecha.
            </td>
            <td>
                <div class="clauses-title">TERCERA. DAÑOS Y RESPONSABILIDAD</div>
                EL CLIENTE asume la responsabilidad total por cualquier daño, deterioro o pérdida causada a las instalaciones, mobiliario, equipo de audio y decoración durante el desarrollo del evento, ya sea provocado por él mismo o por sus invitados. FantaSync no se hace responsable por pérdida de objetos personales, regalos o valores dentro de las instalaciones, salón o estacionamiento.
                
                <br><br><div class="clauses-title">CUARTA. TIEMPO ADICIONAL</div>
                Las horas adicionales al servicio pactado originalmente estarán sujetas a la disponibilidad del recinto y del personal operativo. Toda hora o fracción adicional deberá solicitarse durante el evento y pagarse en efectivo en ese momento, tabulándose según las tarifas vigentes el día del evento.
            </td>
        </tr>
    </table>

    <!-- Signatures -->
    <table class="signatures">
        <tr>
            <td>
                <div class="sign-line"></div>
                <div class="sign-name">{{ mb_strtoupper($contrato->evento->cliente->nombre_completo ?? 'EL CLIENTE') }}</div>
                <div class="sign-desc">Firma de Conformidad - EL CLIENTE</div>
            </td>
            <td>
                <div class="sign-line"></div>
                <div class="sign-name">FANTASYNC EVENTOS</div>
                <div class="sign-desc">Firma de Representante Autorizado</div>
            </td>
        </tr>
    </table>

</body>
</html>
