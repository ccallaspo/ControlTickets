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
            background-color: #32506e;
            color: #fff;
            text-align: center;
            padding: 20px;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
        }
        .header p {
            margin: 0;
            font-size: 16px;
            opacity: 0.9;
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

        .red{
            color: red;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header del correo -->
        <div class="header">
            <h1>{{ $subject ?? 'Notificación importante' }}</h1>
            <p>{{ $subtitle ?? 'Sigue el flujo de trabajo para completar el proceso.' }}</p>
        </div>

        <!-- Contenido principal -->
        <div class="content">
        <h2>Estimado colaborador,</h2>

            <p>{{ $messageContent ?? 'Las DJ Particiapantes fueron generadas. El siguiente paso es facturar.' }}</p>

            <!-- Detalles de la cotización -->
            @if(isset($data))
            <h3>Detalles de la cotización:</h3>
            <ul>
                <li><strong>Cotización:</strong> {{ $data->referent ?? 'N/A' }}</li>
                <li><strong>Curso:</strong> {{ $data->name_course ?? 'N/A' }}</li>
                <li><strong>Código:</strong> {{ $data->cod_sence_course ?? 'N/A' }}</li>
                <li><strong>Última actualización:</strong> {{ $data->updated_at }}</li>
            </ul>
            @endif

            <p>Ahora, por favor procede a <strong class="red">Por facturar</strong> para continuar el proceso.</p>

            <!-- Acción sugerida (botón) -->
            @if(isset($actionUrl))
                <a href="{{ $actionUrl }}" class="btn">Agendar Curso</a>
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
