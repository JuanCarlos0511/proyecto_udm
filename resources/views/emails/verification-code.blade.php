<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Código de Verificación - Clínica Miel</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #4a6bff;
            padding: 20px;
            text-align: center;
            color: white;
            border-radius: 5px 5px 0 0;
        }
        .content {
            background-color: #f9f9f9;
            padding: 30px;
            border-radius: 0 0 5px 5px;
            border: 1px solid #e0e0e0;
            border-top: none;
        }
        .verification-code {
            font-size: 32px;
            font-weight: bold;
            text-align: center;
            margin: 30px 0;
            letter-spacing: 5px;
            color: #4a6bff;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 12px;
            color: #888;
        }
        .note {
            background-color: #fff8e1;
            padding: 15px;
            border-radius: 5px;
            border-left: 4px solid #ffc107;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Clínica Miel</h1>
        </div>
        <div class="content">
            <h2>Verificación de Correo Electrónico</h2>
            
            <p>Gracias por registrarte en Clínica Miel. Para completar tu registro, por favor ingresa el siguiente código de verificación:</p>
            
            <div class="verification-code">
                {{ $verificationCode }}
            </div>
            
            <div class="note">
                <p><strong>Nota:</strong> Este código expirará en 30 minutos por razones de seguridad.</p>
            </div>
            
            <p>Si no solicitaste este código, puedes ignorar este correo electrónico.</p>
            
            <p>Saludos,<br>El equipo de Clínica Miel</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} Clínica Miel. Todos los derechos reservados.</p>
            <p>Este es un correo electrónico automático, por favor no responda a este mensaje.</p>
        </div>
    </div>
</body>
</html>
