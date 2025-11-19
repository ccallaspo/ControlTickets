<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte Diario de Cursos - Operaciones</title>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f0f4f8;
            margin: 0;
            padding: 0;
            color: #333;
        }

        .container {
            max-width: 700px;
            margin: 40px auto;
            background: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
        }

        /* Header */
        .header {
            background: #ffffff;
            text-align: center;
            padding: 30px 20px;
            border-bottom: 1px solid #e0e6ef;
        }

        .header img {
            max-width: 100px;
            margin-bottom: 15px;
        }

        .header h1 {
            margin: 0;
            font-size: 26px;
            font-weight: 600;
            color: #1a3e6a;
        }

        .header p {
            margin: 8px 0 0;
            font-size: 15px;
            color: #6c757d;
        }

        /* Contenido */
        .content {
            padding: 30px 40px;
        }

        .content h2 {
            font-size: 22px;
            color: #1a3e6a;
            margin-top: 0;
            margin-bottom: 20px;
            font-weight: 600;
        }

        .content p {
            font-size: 16px;
            line-height: 1.7;
            color: #495057;
            margin: 15px 0;
        }

        /* Tablas */
        .table-container {
            margin-top: 20px;
            overflow-x: auto;
        }

        .course-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 14px;
            table-layout: fixed;
        }

        .course-table th,
        .course-table td {
            padding: 10px;
            border: 1px solid #e0e6ef;
            text-align: left;
            word-wrap: break-word;
        }

        .course-table th {
            background-color: #f7f9fc;
            color: #1a3e6a;
            font-weight: 600;
        }

        .empty-list {
            color: #888;
            font-style: italic;
        }

        /* Columnas */
        .course-table .col-cotizacion { width: 20%; }
        .course-table .col-id { width: 20%; }
        .course-table .col-curso { width: 45%; }
        .course-table .col-accion { width: 15%; }

        /* Estados */
        .status-proceso {
            color: #007bff;
            font-weight: bold;
        }

        .status-generar a {
            color: #28a745 !important;
            font-weight: bold;
            text-decoration: none;
        }

        /* NUEVO: Badge para indicar dato de ejecución */
        .badge-exec {
            display: inline-block;
            background-color: #ffc107; /* Amarillo */
            color: #000;
            font-size: 10px;
            padding: 2px 4px;
            border-radius: 3px;
            margin-left: 5px;
            vertical-align: middle;
            font-weight: bold;
            text-transform: uppercase;
        }

        /* Footer */
        .footer {
            background: #f8f9fa;
            border-top: 1px solid #e0e6ef;
            text-align: center;
            padding: 25px;
            font-size: 13px;
            color: #888;
        }

        .footer p { margin: 5px 0; }
        .footer small { display: block; margin-top: 10px; color: #aaa; }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <img src="https://otecproyecta.cl/wp-content/uploads/2024/02/logo-sin-fondo.002.png" alt="Logo OTEC Proyecta">
            <h1>Reporte Diario de Cursos - Operaciones</h1>
            <h1>{{ \Carbon\Carbon::now()->format('d-m-Y') }}</h1>
        </div>

        <div class="content">
            <h2>Cursos que inician hoy</h2>
            @if($coursesStartingToday->isEmpty())
                <p class="empty-list">No hay cursos que inicien hoy.</p>
            @else
                <div class="table-container">
                    <table class="course-table">
                        <thead>
                            <tr>
                                <th class="col-cotizacion">Cotización</th>
                                <th class="col-id">Código ID</th>
                                <th class="col-curso">Curso</th>
                                <th class="col-accion">Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($coursesStartingToday as $course)
                            <tr>
                                <td>{{ $course->referent }}</td>
                                
                                <td>
                                    {{ $course->has_execution_data ? $course->exec_id_sence : $course->id_sence }}
                                </td>

                                <td>
                                    {{ $course->has_execution_data ? $course->exec_name_course : $course->name_course }}
                                    
                                    @if($course->has_execution_data)
                                        <span class="badge-exec">Ejecución</span>
                                    @endif
                                </td>
                                
                                <td class="status-proceso">Curso en Proceso</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
            
            <br><br>

            <h2>Cursos que finalizan hoy</h2>
            @if($coursesEndingToday->isEmpty())
                <p class="empty-list">No hay cursos que finalicen hoy.</p>
            @else
                <div class="table-container">
                    <table class="course-table">
                        <thead>
                            <tr>
                                <th class="col-cotizacion">Cotización</th>
                                <th class="col-id">Código ID</th>
                                <th class="col-curso">Curso</th>
                                <th class="col-accion">Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($coursesEndingToday as $course)
                            <tr>
                                <td>{{ $course->referent }}</td>
                                
                                <td>
                                    {{ $course->has_execution_data ? $course->exec_id_sence : $course->id_sence }}
                                </td>

                                <td>
                                    {{ $course->has_execution_data ? $course->exec_name_course : $course->name_course }}

                                    @if($course->has_execution_data)
                                        <span class="badge-exec">Ejecución</span>
                                    @endif
                                </td>

                                <td>
                                    <a href="https://controlproyecta.cl/admin/followups/{{ $course->id }}" class="status-generar">Ver Curso</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <div class="footer">
            <p>Saludos cordiales,</p>
            <p><strong>Equipo de OTEC Proyecta</strong></p>
            <small>Este es un correo automático, por favor no responder.</small>
        </div>
    </div>
</body>
</html>