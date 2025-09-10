<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte Diario de Cursos</title>
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

        .empty-list {
            color: #888;
            font-style: italic;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header del correo -->
        <div class="header">
            <h1>Reporte Diario de Cursos</h1>
        </div>

        <!-- Contenido principal -->
        <div class="content">
            <h2>Cursos que inician hoy</h2>
            @if($coursesStartingToday->isEmpty())
            <p class="empty-list">No hay cursos que inicien hoy.</p>
            @else
            <table style="width: 100%; border-collapse: collapse; margin-top: 10px;">
                <thead>
                    <tr style="background-color: #f4f4f4; text-align: left;">
                        <th style="padding: 10px; border: 1px solid #ddd;">Cotización</th>
                        <th style="padding: 10px; border: 1px solid #ddd;">Curso</th>
                        <th style="padding: 10px; border: 1px solid #ddd;">Fecha de Inicio</th>
                        <th style="padding: 10px; border: 1px solid #ddd;">Hora de Inicio</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($coursesStartingToday as $course)
                    <tr>
                        <td style="padding: 10px; border: 1px solid #ddd;">{{ $course->referent }}</td>
                        <td style="padding: 10px; border: 1px solid #ddd;">{{ $course->name_course }}</td>
                        <td style="padding: 10px; border: 1px solid #ddd;">{{ \Carbon\Carbon::parse($course->f_star)->format('d-m-Y') }}</td>
                        <td style="padding: 10px; border: 1px solid #ddd;">{{ $course->h_star }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif

            <h2>Cursos que finalizan hoy</h2>
            @if($coursesEndingToday->isEmpty())
            <p class="empty-list">No hay cursos que finalicen hoy.</p>
            @else
            <table style="width: 100%; border-collapse: collapse; margin-top: 10px;">
                <thead>
                    <tr style="background-color: #f4f4f4; text-align: left;">
                        <th style="padding: 10px; border: 1px solid #ddd;">Cotización</th>
                        <th style="padding: 10px; border: 1px solid #ddd;">Curso</th>
                        <th style="padding: 10px; border: 1px solid #ddd;">Fecha de Término</th>
                        <th style="padding: 10px; border: 1px solid #ddd;">Hora de Término</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($coursesEndingToday as $course)
                    <tr>
                        <td style="padding: 10px; border: 1px solid #ddd;">{{ $course->referent }}</td>
                        <td style="padding: 10px; border: 1px solid #ddd;">{{ $course->name_course }}</td>
                        <td style="padding: 10px; border: 1px solid #ddd;">{{ \Carbon\Carbon::parse($course->f_end)->format('d-m-Y') }}</td>
                        <td style="padding: 10px; border: 1px solid #ddd;">{{ $course->h_end }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
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