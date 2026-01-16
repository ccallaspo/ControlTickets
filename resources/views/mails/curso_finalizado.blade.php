<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualización de Estado - OTEC Proyecta</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f0f4f8; /* Gris claro */
            margin: 0;
            padding: 0;
            color: #333;
        }

        .container {
            max-width: 600px;
            margin: 40px auto;
            background: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
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
            color: #1a3e6a; /* Azul oscuro */
        }
        .header p {
            margin: 8px 0 0;
            font-size: 15px;
            color: #6c757d; /* Gris medio */
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
            color: #495057; /* Gris oscuro */
            margin: 15px 0;
        }

        /* Timeline/Stepper Vertical */
        .timeline-container {
            background-color: #ffffff;
            border-radius: 8px;
            padding: 20px 0 10px 0;
            border: 1px solid #e9ecef;
            margin-top: 25px;
        }
        .timeline-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 15px;
            position: relative;
        }
        .timeline-icon-wrapper {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }
        .timeline-icon-wrapper::after {
            content: '';
            position: absolute;
            top: 40px;
            left: 50%;
            transform: translateX(-50%);
            width: 2px;
            height: calc(100% - 20px);
            background-color: #e0e6ef;
        }
        .timeline-item:last-child .timeline-icon-wrapper::after {
            display: none;
        }
        .timeline-icon {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            font-weight: bold;
            color: white;
            z-index: 1;
        }
        .timeline-icon.completed {
            background-color: #28a745;
        }
        .timeline-icon.current {
            background-color: #007bff;
            box-shadow: 0 0 0 4px rgba(0, 123, 255, 0.2);
            font-size: 10px;
        }
        .timeline-icon.pending {
            background-color: #e9ecef;
            border: 1px solid #ced4da;
        }
        .timeline-icon-inner {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background-color: #fff;
        }
        .timeline-text {
            margin-left: 15px;
            padding-top: 5px;
        }
        .timeline-text h3 {
            margin: 0;
            font-size: 16px;
            color: #1a3e6a;
            font-weight: 600;
        }
        .timeline-text.completed h3 {
            color: #888;
        }
        
        /* Líneas de progreso entre los pasos completados */
        .timeline-item.completed + .timeline-item .timeline-icon-wrapper::after {
            background-color: #28a745;
        }

        /* Detalles del servicio */
        .service-details {
            background-color: #f7f9fc;
            border-radius: 8px;
            padding: 20px 25px;
            border: 1px solid #e9ecef;
            margin-bottom: 25px;
        }
        .service-details ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .service-details ul li {
            padding: 8px 0;
            font-size: 15px;
            color: #495057;
            border-bottom: 1px solid #e0e6ef;
        }
        .service-details ul li:last-child {
            border-bottom: none;
        }
        .service-details ul li strong {
            color: #1a3e6a;
        }

        /* Botón */
        .btn {
            display: inline-block;
            background: #007bff; /* Azul primario */
            color: #ffffff !important;
            padding: 14px 28px;
            margin-top: 20px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 16px;
            font-weight: bold;
            transition: background 0.3s ease, transform 0.2s ease;
        }
        .btn:hover {
            background: #0056b3; /* Azul más oscuro */
            transform: translateY(-2px);
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
        .footer p {
            margin: 5px 0;
        }
        .footer small {
            display: block;
            margin-top: 10px;
            color: #aaa;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="https://otecproyecta.cl/wp-content/uploads/2024/02/logo-sin-fondo.002.png" alt="Logo OTEC Proyecta">
            <h1>Estado del Ticket</h1>
            <p>Actualización sobre el progreso de su servicio.</p>
        </div>
        <div class="content">
            <h2>¡Información del Curso!</h2>
       
            
            <div class="service-details">
                <ul>
                    <li><strong>Cotización:</strong> {{ $data->referent ?? 'N/A' }}</li>
                    <li><strong>Curso:</strong> {{ $data->name_course ?? 'N/A' }}</li>
                    <li><strong>Código ID:</strong> {{ $data->id_sence ?? 'N/A' }}</li>
                </ul>
            </div>

            <div class="timeline-container">
                
                <div class="timeline-item completed">
                    <div class="timeline-icon-wrapper">
                        <span class="timeline-icon completed"></span>
                    </div>
                    <div class="timeline-text completed">
                        <h3>Cotización Enviada</h3>
                    </div>
                </div>
                
                              <div class="timeline-item completed">
                    <div class="timeline-icon-wrapper">
                        <span class="timeline-icon completed"></span>
                    </div>
                    <div class="timeline-text completed">
                        <h3>Coordinar Curso</h3>
                    </div>
                </div>
                

                
                                <div class="timeline-item completed">
                    <div class="timeline-icon-wrapper">
                        <span class="timeline-icon completed"></span>
                    </div>
                    <div class="timeline-text completed">
                        <h3>Matricular Curso</h3>
                    </div>
                </div>
                
       
                
                <div class="timeline-item completed">
                    <div class="timeline-icon-wrapper">
                        <span class="timeline-icon completed"></span>
                    </div>
                    <div class="timeline-text completed">
                        <h3>Curso en Proceso</h3>
                    </div>
                </div>
                

                
                <div class="timeline-item current">
                    <div class="timeline-icon-wrapper">
                        <span class="timeline-icon current"></span>
                    </div>
                    <div class="timeline-text current">
                        <h3>Curso Finalizado</h3>
                    </div>
                </div>
                <div class="timeline-item pending">
                    <div class="timeline-icon-wrapper">
                        <span class="timeline-icon pending"></span>
                    </div>
                    <div class="timeline-text pending">
                        <h3>Generar DJ</h3>
                    </div>
                </div>
                <div class="timeline-item pending">
                    <div class="timeline-icon-wrapper">
                        <span class="timeline-icon pending"></span>
                    </div>
                    <div class="timeline-text pending">
                        <h3>Por Facturar</h3>
                    </div>
                </div>
            </div>


            <center>
                <a href="https://controlproyecta.cl/admin/followups/{{ $data->id }}" class="btn">Ver Ticket</a>
            </center>
        </div>
        <div class="footer">
            <p>Saludos cordiales,</p>
            <p><strong>Equipo OTEC Proyecta</strong></p>
            <small>Este es un correo automático. Por favor, no responder.</small>
        </div>
    </div>
</body>
</html>