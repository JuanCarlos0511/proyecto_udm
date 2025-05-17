<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Factura - Clínica Miel</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header img {
            max-width: 200px;
        }
        .invoice-info {
            margin-bottom: 30px;
        }
        .invoice-info table {
            width: 100%;
            border-collapse: collapse;
        }
        .invoice-info td {
            padding: 5px;
        }
        .patient-info {
            margin-bottom: 30px;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Clínica Miel</h1>
        <p>Factura #{{ $bill->id }}</p>
    </div>

    <div class="invoice-info">
        <table>
            <tr>
                <td><strong>Fecha:</strong></td>
                <td>{{ $bill->created_at->format('d/m/Y') }}</td>
                <td><strong>RFC:</strong></td>
                <td>{{ $bill->rfc }}</td>
            </tr>
            <tr>
                <td><strong>Código Postal:</strong></td>
                <td>{{ $bill->codigo_postal }}</td>
                <td><strong>Régimen Fiscal:</strong></td>
                <td>{{ $bill->regimen_fiscal }}</td>
            </tr>
            <tr>
                <td><strong>Uso CFDI:</strong></td>
                <td>{{ $bill->cfdi }}</td>
                <td><strong>Estado:</strong></td>
                <td>{{ ucfirst($bill->status) }}</td>
            </tr>
        </table>
    </div>

    <div class="patient-info">
        <h3>Información del Paciente</h3>
        <p><strong>Nombre:</strong> {{ $patient->name }}</p>
        <p><strong>Email:</strong> {{ $patient->email }}</p>
        <p><strong>Cuenta con Seguro:</strong> {{ $bill->cuenta_con_seguro ? 'Sí' : 'No' }}</p>
    </div>

    <div class="footer">
        <p>Este documento es una representación impresa de un CFDI</p>
        <p>Clínica Miel - Todos los derechos reservados</p>
    </div>
</body>
</html>
