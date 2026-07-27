<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Nóminas - FantaSync</title>
    <style>
        @page { size: A4 landscape; margin: 15px; }
        {!! file_get_contents(public_path('css/pdf-reportes.css')) !!}
        table.data-table th, table.data-table td { padding: 10px; }
        .signature-box {
            background-color: #ffffff !important;
            border: 1px solid #888888 !important;
            height: 35px;
            width: 200px;
        }
    </style>
</head>
<body>

    <header class="header">
        <h1>FantaSync</h1>
        <h2>Reporte de Nóminas y Pagos</h2>
        <p>Fecha de generación: {{ date('d/m/Y H:i') }}</p>
        @if($search)
            <p><strong>Filtro aplicado:</strong> "{{ $search }}"</p>
        @endif
    </header>

    <table class="kpi-container">
        <tr>
            <td>
                <span class="kpi-value">${{ number_format($totalAPagar, 2) }}</span>
                <span class="kpi-label">Total a Pagar</span>
            </td>
            <td>
                <span class="kpi-value">${{ number_format($operacionTotal, 2) }}</span>
                <span class="kpi-label">Operación</span>
            </td>
            <td>
                <span class="kpi-value">${{ number_format($cocinaTotal, 2) }}</span>
                <span class="kpi-label">Cocina</span>
            </td>
            <td>
                <span class="kpi-value">${{ number_format($oficinaTotal, 2) }}</span>
                <span class="kpi-label">Oficina</span>
            </td>
        </tr>
    </table>

    <table class="data-table">
        <thead>
            <tr>
                <th>Empleado</th>
                <th>Puesto</th>
                <th>Salario</th>
                <th>Evento</th>
                <th>Fecha</th>
                <th>Estado</th>
                <th style="width: 200px; text-align: center;">Firma de Recibido</th>
            </tr>
        </thead>
        <tbody>
            @foreach($nominas as $nomina)
                <tr>
                    <td><strong>{{ $nomina->nombre_empleado }}</strong></td>
                    <td>{{ $nomina->puesto }}</td>
                    <td><strong>${{ number_format($nomina->monto_total, 2) }}</strong></td>
                    <td>{{ $nomina->evento->titulo ?? 'N/A' }}</td>
                    <td>{{ \Carbon\Carbon::parse($nomina->fecha_trabajo)->format('d/m/Y') }}</td>
                    <td class="status-{{ strtolower($nomina->estado_pago) }}">{{ $nomina->estado_pago }}</td>
                    <td class="signature-box"></td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <footer class="footer">
        FantaSync ERP - Reporte generado automáticamente.
    </footer>

</body>
</html>
