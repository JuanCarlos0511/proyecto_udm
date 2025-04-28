<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Citas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo {
            max-width: 150px;
            margin-bottom: 10px;
        }
        h1 {
            color: #2c3e50;
            font-size: 24px;
            margin-bottom: 5px;
        }
        .date-range {
            font-size: 14px;
            color: #7f8c8d;
            margin-bottom: 20px;
        }
        .summary-box {
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
        }
        .summary-item {
            text-align: center;
            flex: 1;
        }
        .summary-value {
            font-size: 24px;
            font-weight: bold;
            color: #3498db;
            margin-bottom: 5px;
        }
        .summary-label {
            font-size: 12px;
            color: #7f8c8d;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        th {
            background-color: #3498db;
            color: white;
            font-weight: bold;
            text-align: left;
            padding: 10px;
        }
        td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .footer {
            text-align: center;
            font-size: 12px;
            color: #7f8c8d;
            margin-top: 30px;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        .page-number {
            text-align: right;
            font-size: 12px;
            color: #7f8c8d;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Reporte de Citas Realizadas</h1>
        <div class="date-range">
            Período: {{ $startDate->format('d/m/Y') }} - {{ $endDate->format('d/m/Y') }}
        </div>
    </div>

    <div class="summary-box">
        <div class="summary-item">
            <div class="summary-value">{{ $totalAppointments }}</div>
            <div class="summary-label">Citas Totales</div>
        </div>
        <div class="summary-item">
            <div class="summary-value">{{ $uniquePatients }}</div>
            <div class="summary-label">Pacientes Atendidos</div>
        </div>
        <div class="summary-item">
            <div class="summary-value">${{ number_format($totalIncome, 2) }}</div>
            <div class="summary-label">Ingresos Totales</div>
        </div>
    </div>

    <h2>Detalle de Citas</h2>
    
    <table>
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Paciente</th>
                <th>Asunto</th>
                <th>Modalidad</th>
                <th>Precio</th>
            </tr>
        </thead>
        <tbody>
            @forelse($appointments as $appointment)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($appointment->date)->format('d/m/Y') }}</td>
                    <td>{{ $appointment->user->name }}</td>
                    <td>{{ $appointment->subject }}</td>
                    <td>{{ $appointment->modality }}</td>
                    <td>${{ number_format($appointment->price, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center;">No hay citas en el período seleccionado</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Este reporte fue generado el {{ now()->format('d/m/Y H:i:s') }}</p>
        <p>Clínica UDM - Sistema de Administración</p>
    </div>

    <div class="page-number">
        Página 1
    </div>
</body>
</html>
