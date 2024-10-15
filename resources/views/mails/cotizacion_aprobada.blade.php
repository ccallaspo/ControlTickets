<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subject ?? 'Notificación' }}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 0;
            line-height: 1.6;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            padding: 10px 0;
            border-bottom: 1px solid #ddd;
        }
        .header h1 {
            color: #4CAF50;
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 20px;
        }
        .content h2 {
            font-size: 22px;
            color: #333;
        }
        .content p {
            margin: 10px 0;
            color: #555;
        }
        .content ul {
            list-style-type: none;
            padding: 0;
        }
        .content ul li {
            background-color: #f9f9f9;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 10px;
            color: #444;
        }
        .footer {
            text-align: center;
            padding: 20px;
            font-size: 12px;
            color: #999;
            border-top: 1px solid #ddd;
        }
        .footer p {
            margin: 0;
        }
        .btn {
            display: inline-block;
            background-color: #4CAF50;
            color: #fff;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 16px;
            margin-top: 10px;
        }
        .btn:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header del correo -->
        <div class="header">
            <h1>{{ $subject ?? 'Cotización aprobada' }}</h1>
        </div>

        <!-- Contenido principal -->
        <div class="content">
            <h2>Estimado {{ $recipientName ?? 'Usuario' }},</h2>

            <p>Nos complace informarte que la cotización con el nombre <strong>{{ $data->name }}</strong> ha sido <strong>aprobada</strong>.</p>
            
            <p>Como siguiente paso, es necesario que procedas a agendar el curso. A continuación te detallamos lo que necesitamos:</p>
            <ul>
                <li><strong>Información de los participantes</strong> (nombres, correos electrónicos, roles)</li>
                <li><strong>Horarios sugeridos</strong></li>
                <li><strong>Fecha de inicio y fin del curso</strong></li>
            </ul>

            <h3>Detalles de la cotización:</h3>
            <ul>
                <li><strong>Nombre del curso:</strong> {{ $data->name }}</li>
                <li><strong>Descripción:</strong> {{ $data->description ?? 'N/A' }}</li>
                <li><strong>Fecha de aprobación:</strong> {{ $data->updated_at }}</li>
            </ul>

            <!-- Acción sugerida (botón) si aplica -->
            @if(isset($actionUrl))
                <a href="{{ $actionUrl }}" class="btn">Ver Cotización</a>
            @endif
        </div>

        <!-- Footer del correo -->
        <div class="footer">
            <p>Saludos cordiales,</p>
            <p><strong>Equipo de OTEC Proyecta</strong></p>
            <p><small>Este es un correo automático, por favor no responder.</small></p>
        </div>
    </div>
</body>
</html>
