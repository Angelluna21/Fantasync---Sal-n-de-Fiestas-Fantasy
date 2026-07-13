<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Contrato de Evento #{{ str_pad($contrato->id, 5, '0', STR_PAD_LEFT) }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #7a288a;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #7a288a;
            margin: 0;
            font-size: 24px;
            text-transform: uppercase;
        }
        .header p {
            margin: 5px 0 0;
            color: #666;
            font-size: 14px;
        }
        .section-title {
            background-color: #f8f9fa;
            color: #7a288a;
            padding: 10px;
            font-size: 16px;
            font-weight: bold;
            border-left: 4px solid #7a288a;
            margin: 20px 0 10px;
        }
        .info-grid {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .info-grid td {
            padding: 8px;
            vertical-align: top;
            width: 50%;
        }
        .label {
            font-weight: bold;
            color: #555;
            display: block;
            font-size: 12px;
            text-transform: uppercase;
        }
        .value {
            font-size: 15px;
            color: #111;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        .table th {
            background-color: #7a288a;
            color: white;
            font-weight: bold;
        }
        .totals-box {
            float: right;
            width: 300px;
            background: #f8f9fa;
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 5px;
        }
        .totals-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
        }
        .totals-row.grand-total {
            font-size: 18px;
            font-weight: bold;
            color: #7a288a;
            border-top: 2px solid #ddd;
            padding-top: 10px;
            margin-top: 10px;
        }
        .clauses {
            font-size: 12px;
            color: #555;
            text-align: justify;
            margin-top: 40px;
            clear: both;
        }
        .clauses h3 {
            font-size: 14px;
            color: #333;
        }
        .signatures {
            margin-top: 60px;
            width: 100%;
            border-collapse: collapse;
            page-break-inside: avoid;
        }
        .signatures td {
            text-align: center;
            width: 50%;
            padding: 20px;
        }
        .sign-line {
            border-top: 1px solid #333;
            width: 80%;
            margin: 0 auto;
            padding-top: 10px;
            font-weight: bold;
        }
        .sign-title {
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>

    <header class="header">
        <h1>FantaSync Eventos</h1>
        <p>Contrato de Prestación de Servicios para Salón de Fiestas</p>
        <p>Folio: <strong>#CNT-{{ str_pad($contrato->id, 5, '0', STR_PAD_LEFT) }}</strong> | Fecha: {{ $contrato->created_at->format('d/m/Y') }}</p>
    </header>

    <h2 class="section-title">Datos del Cliente</h2>
    <table class="info-grid">
        <tr>
            <td>
                <span class="label">Nombre Completo</span>
                <span class="value">{{ $contrato->evento->cliente->nombre_completo ?? 'N/A' }}</span>
            </td>
            <td>
                <span class="label">Teléfono / Celular</span>
                <span class="value">{{ $contrato->evento->cliente->celular ?? 'N/A' }}</span>
            </td>
        </tr>
    </table>

    <h2 class="section-title">Detalles del Evento</h2>
    <table class="info-grid">
        <tr>
            <td>
                <span class="label">Tipo de Evento</span>
                <span class="value">{{ $contrato->evento->titulo }}</span>
            </td>
            <td>
                <span class="label">Fecha y Hora</span>
                <span class="value">{{ $contrato->evento->fecha->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($contrato->evento->hora_inicio)->format('H:i') }} a {{ \Carbon\Carbon::parse($contrato->evento->hora_fin)->format('H:i') }}</span>
            </td>
        </tr>
        <tr>
            <td>
                <span class="label">Salones Asignados</span>
                <span class="value">
                    @foreach($contrato->evento->salones as $salon)
                        {{ $salon->nombre }} ({{ $salon->sucursal->nombre ?? 'N/A' }})<br>
                    @endforeach
                </span>
            </td>
            <td>
                <span class="label">Invitados</span>
                <span class="value">
                    @php
                        $totalAdultos = $contrato->evento->salones->sum('pivot.adultos');
                        $totalNinos = $contrato->evento->salones->sum('pivot.ninos');
                    @endphp
                    Adultos: {{ $totalAdultos }} | Niños: {{ $totalNinos }}<br>
                    Total: {{ $totalAdultos + $totalNinos }} personas
                </span>
            </td>
        </tr>
    </table>

    @if($contrato->evento->eventoSalones->isNotEmpty() && $contrato->evento->eventoSalones->first()->platillos->isNotEmpty())
        <h2 class="section-title">Servicio Gastronómico Seleccionado</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Platillo / Guisado</th>
                    <th>Porciones Estimadas</th>
                </tr>
            </thead>
            <tbody>
                @foreach($contrato->evento->eventoSalones->first()->platillos as $platillo)
                    <tr>
                        <td>{{ $platillo->nombre }}</td>
                        <td>{{ $platillo->pivot->porciones_plan }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <h2 class="section-title">Resumen Financiero</h2>
    <section class="totals-box">
        <p class="totals-row">
            <span>Subtotal:</span>
            <span>${{ number_format($contrato->monto_total, 2) }}</span>
        </p>
        <p class="totals-row">
            <span>Anticipo Pagado:</span>
            <span>${{ number_format($contrato->monto_total - $contrato->saldo_pendiente, 2) }}</span>
        </p>
        <p class="totals-row grand-total">
            <span>Saldo Pendiente:</span>
            <span>${{ number_format($contrato->saldo_pendiente, 2) }}</span>
        </p>
    </section>
    <br style="clear: both;">

    <article class="clauses">
        <h3>Términos y Condiciones Generales</h3>
        <p><strong>1. PRESTACIÓN DEL SERVICIO:</strong> El prestador se compromete a facilitar el salón y los servicios descritos en este contrato en la fecha y hora acordadas. El cliente se compromete a respetar las instalaciones y el horario establecido.</p>
        <p><strong>2. PAGOS Y CANCELACIONES:</strong> El saldo pendiente debe ser liquidado en su totalidad a más tardar 15 días antes de la fecha del evento. En caso de cancelación por parte del cliente, el anticipo no será reembolsable por concepto de gastos administrativos y bloqueo de fecha.</p>
        <p><strong>3. RESPONSABILIDAD:</strong> El cliente asume la responsabilidad por cualquier daño causado a las instalaciones, mobiliario o equipo por él o cualquiera de sus invitados durante el desarrollo del evento.</p>
    </article>

    <table class="signatures">
        <tr>
            <td>
                <div class="sign-line">{{ $contrato->evento->cliente->nombre_completo ?? 'El Cliente' }}</div>
                <div class="sign-title">Firma del Cliente (Conformidad)</div>
            </td>
            <td>
                <div class="sign-line">FantaSync Eventos</div>
                <div class="sign-title">Representante Autorizado</div>
            </td>
        </tr>
    </table>

</body>
</html>
