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
            <h1>{{ $subject ?? 'Notificación importante' }}</h1>
        </div>

        <!-- Contenido principal -->
        <div class="content">
            <h2>Estimado {{ $recipientName ?? 'Usuario' }},</h2>

            <p>{{ $messageContent ?? 'Te informamos que se ha realizado una actualización en tu cotización.' }}</p>

            <!-- Detalles personalizados según el estatus -->
            @if(isset($data))
            <h3>Detalles de la cotización:</h3>
            <ul>
                <li><strong>Nombre:</strong> {{ $data->name }}</li>
                <li><strong>Descripción:</strong> {{ $data->description ?? 'N/A' }}</li>
                <li><strong>Fecha:</strong> {{ $data->updated_at }}</li>
                <!-- Agrega más detalles según sea necesario -->
            </ul>
            @endif

            <!-- Acción sugerida (botón) si aplica -->
            @if(isset($actionUrl))
                <a href="{{ $actionUrl }}" class="btn">Ver Detalles</a>
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
