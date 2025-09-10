<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subject ?? 'Bienvenido al Curso' }}</title>
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
        
        .myp{
            padding: 0 !important;
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
            background-color: #32506e;
            /* Azul oscuro profesional */
            color: #fff;
            padding: 12px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 16px;
            font-weight: bold;
            transition: background-color 0.3s ease, transform 0.2s ease;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Bienvenidos al Curso</h1>
        </div>

        <div class="content">
            <h2>Estimado/a Participante,</h2>
            <p>Nos complace darle la bienvenida al curso <strong>“{{ $data['name_course'] }}”</strong>, que se desarrollará en modalidad e-learning sincrónica.</p>

            <h3>Detalles del curso:</h3>
            <ul>
                <li><strong>Fecha de inicio:</strong> {{ $data['f_star'] }}</li>
                <li><strong>Fecha de término:</strong> {{ $data['f_end'] }}</li>
                <li><strong>Clases online: </strong> <a href="{{ $data['link_clases'] }}">Link de la clase online</a> de {{ $data['h_star'] }} a {{ $data['h_end'] }}</li>
            </ul>

            <h3>Acceso a la plataforma:</h3>
            <p>Para comenzar, ingrese a la plataforma de estudio con el siguiente enlace: <a href="{{ $data['link_moodle'] }}">Acceder al Curso</a></p>

            <h3>Credenciales de acceso:</h3>
            <ul>
                <li><strong>Usuario:</strong> Su RUT sin puntos, con guion y dígito verificador (Ejemplo: 12321987-k).</li>
                <li><strong>Contraseña:</strong> {{ $data['password'] }} </li>
            </ul>

            @if(!empty($data['horarios']))
            <h4>Calendario de clase:</h4>
            <table style="width: 100%; border-collapse: collapse; margin-top: 10px;">
                <thead>
                    <tr>
                        <th style="padding: 8px; border: 1px; background-color: #32506e; color:#fff">Día</th>
                        <th style="padding: 8px; border: 1px; background-color: #32506e; color:#fff">Hora de Inicio</th>
                        <th style="padding: 8px; border: 1px; background-color: #32506e; color:#fff">Hora de Término</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data['horarios'] as $horario)
                    <tr>
                        <td style="padding: 8px; border: 1px solid #ddd;">{{ \Carbon\Carbon::parse($horario['day'])->format('d-m-Y') }}</td>
                        <td style="padding: 8px; border: 1px solid #ddd;">{{ $horario['start_time'] }}</td>
                        <td style="padding: 8px; border: 1px solid #ddd;">{{ $horario['end_time'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <h3>Horarios:</h3>
            <p>No se han asignado horarios para este curso.</p>
            @endif
            <br>

            <p>OTEC Proyecta lo acompañará durante todo el proceso de ejecución a través de nuestros distintos canales de atención.</p>

            <strong>Soporte:</strong>
            <p>WhatsApp: <a href="tel:+56983829060" class="myp" style="text-decoration: none; color: #32506e;">+56 9 8382 9060</a></p>
            <p>Email: <a href="mailto:soporte@otecproyecta.cl" class="myp" style="text-decoration: none; color: #32506e;">soporte@otecproyecta.cl</a></p>

        </div>
        <br>
        <div class="footer">
            <p>Para más información, puedes escribirnos a <a href="mailto:contacto@otecproyecta.cl">contacto@otecproyecta.cl</a>.</p>
            <p><small>Este es un correo automático, por favor no responder.</small></p>
        </div>
    </div>
</body>

</html>