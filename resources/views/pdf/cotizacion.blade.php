<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Aptos:wght@400&display=swap" rel="stylesheet">

    <title>OTEC PROYECTA</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            position: relative;
            height: 100vh;
        }

        .container {
            padding: 20px;
            box-sizing: border-box;
        }

        .fixed-image {
            position: fixed;
            top: 0;
            left: 0;
            width: 200px;
            margin: -35;
        }

        .fixed-image-bottom-right {
            position: fixed;
            bottom: 0;
            right: 0;
            width: 200px;
            margin: -35;
        }

        .header-text {
            position: fixed;
            top: 0;
            right: 20px;
            text-align: right;
        }

        .header-text p {
            margin: 0;
        }

        .center-image {
            position: fixed;
            top: 15%;
            left: 50%;
            transform: translate(-65%, -50%);
            width: 200px;
            height: auto;
        }

        .titulo-center-text {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            height: auto;
            text-align: center;
            width: 90%;
        }

        .title-center-text {
            position: fixed;
            top: 70%;
            left: 50%;
            transform: translate(-56%, -50%);
            height: auto;
            text-align: center;
            border-collapse: collapse;
            width: 90%;
        }

        .titulo-center-text-h2 {
            font-family: 'Aptos', sans-serif;
            color: black !important;
            background-color: #fff;
            font-size: 28px;
            width: 100% !important;
            font-style: italic;
            margin: 0;
            padding: 0;
        }


        .titulo-center-text-h1 {
            font-family: 'Aptos', sans-serif;
            font-style: italic;
            font-size: 28px;
            color: black !important;
            text-transform: uppercase;
            margin: 0;
            padding: 0;
        }


        .title-center-text th,
        td {
            padding: 10px;
            text-align: center;
            width: 33.33%;
        }

        .logo_sence {
            width: 100px;
        }

        h2 {
            background-color: #001642;
            color: white;
            padding: 8px;
            border-top-right-radius: 15px;
            border-bottom-right-radius: 15px;
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
            margin-top: 1rem;
            margin-bottom: 0;
            /* Esto crea un espacio antes del h2 sin aumentar el fondo */
            font-size: 15px;
            text-transform: uppercase;
            width: 50%;
            page-break-before: auto;
            page-break-after: auto;
        }


        .content-section {
            margin-top: 850px;
            page-break-inside: avoid;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 16px;
            text-align: left;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        thead {
            background-color: #f4f4f9;
            color: #333;
        }

        th,
        td {
            padding: 12px 15px;
            border-bottom: 1px solid #ddd;
        }

        thead th {
            background-color: #0056b3;
            color: white;
            font-weight: bold;
        }

        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tbody tr:hover {
            background-color: #e0e0e0;
        }

        tbody td {
            vertical-align: middle;
        }

        tfoot {
            background-color: #f4f4f9;
            font-weight: bold;
        }

        .page-break-before {
            page-break-before: always;
        }

        .page-break-inside {
            page-break-inside: avoid;
        }

        .cod_sence {
            color: red !important;
            font-family: 'Aptos', sans-serif;
            font-size: 24px;
            background-color: #fff;
            font-style: oblique;
            font-size: 25px;
            width: 100% !important;
        }

        .description-curso p {
            font-family: 'Calibri', sans-serif;
            font-size: 16px;
            font-style: italic;
            text-align: justify;
        }

        .text-add {
            font-family: 'Calibri', sans-serif;
            font-size: 16px;
            font-style: italic;
        }

        .contact-info {
            background-color: #f8f8f8;
            /* Fondo ligeramente gris para hacer resaltar el contenido */
            text-align: center;
            border-radius: 10px;
            /* Bordes redondeados */
            margin: 20px 0;
        }

        .contact-info h1 {
            font-family: 'Arial', sans-serif;
            font-size: 24px;
            /* Ajuste del tamaño de la fuente */
            font-weight: bold;
            letter-spacing: 1px;
            margin-bottom: 15px;
        }

        .contact-info p {
            font-family: 'Arial', sans-serif;
            font-size: 14px;
            color: #333;
            /* Texto en un gris oscuro */
            margin-bottom: 5px;
        }

        .contact-info p strong {
            font-size: 16px;
            /* Nombre más grande que el resto del texto */
            font-weight: bold;
        }

        .contact-info a {
            color: #007bff;
            /* Color de enlace azul */
            text-decoration: none;
            /* Quita el subrayado de los enlaces */
        }

        .contact-info a:hover {
            text-decoration: underline;
            /* Agrega el subrayado en hover */
        }

        .details_sence {
            font-style: italic;
            text-align: justify;
        }
    </style>
</head>

<body>
    <div class="container">
        <img src="{{ public_path('img/pdf/header_left.png') }}" alt="" class="fixed-image">
        <img src="{{ public_path('img/pdf/footer_right.png') }}" alt="" class="fixed-image-bottom-right">
        <img src="{{ public_path('img/pdf/logo_proyecta.png') }}" alt="" class="center-image">

        <div class="header-text">
            <p>Cotización: {{ $cotizacion->name }}</p>
            <p>Fecha: {{ $cotizacion->created_at->format('d/m/Y') }}</p>
        </div>

        <div class="titulo-center-text">
            <h2 class="titulo-center-text-h2">{{ $course->name }}</h2>
            <br>
            <h4 class="titulo-center-text-h2">Curso modalidad {{ $course->modality }}</h4>

            @if($cotizacion->type === 'Con Franquicia')
            <h2 class="cod_sence">Código Sence Nº {{ $course->cod_sence }}</h2>
            @endif
        </div>

        <div class="">
            <table class="title-center-text">
                <tbody>
                    <tr>
                        <td>
                            <p>ORGANISMO TÉCNICO DE CAPACITACIÓN ACREDITADO</p>
                        </td>
                        <td>
                            <p>NCh 2728:2015</p>
                        </td>
                        <td>
                            <img src="{{ public_path('img/pdf/logo_sence.png') }}" alt="" class="logo_sence">
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="content-section description-curso">
            {!! $cotizacion->content !!}
        </div>

        <div class="investment-curso page-break-before">
            <h2>INVERSION</h2>
            @php
            $hasGrup = collect($costs)->contains(function($cost) {
            return !is_null($cost['grup']);
            });

            function formatNumber($number) {
            return number_format($number, 0, ',', '.');
            }
            @endphp

            <table>
                <thead>
                    <tr>
                        @if($hasGrup)
                        <th>Grupos</th>
                        @endif
                        <th>Total Horas</th>
                        <th>Total Participantes</th>
                        <th>Valor Unitario</th>
                        <th>Costo Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($costs as $cost)
                    <tr>
                        @if($hasGrup && !is_null($cost['grup']))
                        <td>{{ $cost['grup'] }}</td>
                        @endif
                        <td>{{ $cost['thour'] }}</td>
                        <td>{{ $cost['tpart'] }}</td>
                        <td>{{ formatNumber($cost['vunit']) }}</td>
                        <td>{{ formatNumber($cost['costs']) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <p class="details_sence">* para mantener valores ofertados se debe considerar el mínimo de participantes informados en esta cotización</p>
        </div>

        <div class="payments page-break-inside">
            <h2>MEDIOS DE PAGO</h2>
            <h4 class="text-add">Pago a 30 días mediante depósito o transferencia bancaria a:</h4>
            <table>
                <thead>
                    <tr>
                        <th>Descripción</th>
                        <th>Detalle</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Razón Social</td>
                        <td>OTEC Proyecta Spa</td>
                    </tr>
                    <tr>
                        <td>RUT</td>
                        <td>77.495.502-K</td>
                    </tr>
                    <tr>
                        <td>Número de cuenta corriente</td>
                        <td>87424197</td>
                    </tr>
                    <tr>
                        <td>Entidad Bancaria</td>
                        <td>Banco Santander</td>
                    </tr>
                </tbody>
            </table>
        </div>

        @if($cotizacion->type === 'Con Franquicia')
        <div>
            <h2>FRANQUICIA SENCE</h2>
            <p class="details_sence">La empresa debe realizar la inscripción del curso ante SENCE, a más tardar 2 días hábiles antes del inicio del curso.</p>
        </div>

        <!-- <div class="contact-info">
            <h1>COMUNÍCATE CON NOSOTROS</h1>
            <p><strong>Yasna Carreño Cortés</strong></p>
            <p>Email: <a href="mailto:contacto@otecproyecta.cl">contacto@otecproyecta.cl</a></p>
            <p>Directora - (+56) 9 3397 4153</p>
            <p>Te invitamos a revisar todas nuestras novedades en <a href="http://www.otecproyecta.cl" target="_blank">www.otecproyecta.cl</a></p>
        </div> -->
        @endif



        <div class="contact-info page-break-inside">
            <h1>COMUNÍCATE CON NOSOTROS</h1>
            <p><strong>Yasna Carreño Cortés</strong></p>
            <p>Email: <a href="mailto:contacto@otecproyecta.cl">contacto@otecproyecta.cl</a></p>
            <p>Directora - (+56) 9 3397 4153</p>
            <p>Te invitamos a revisar todas nuestras novedades en <a href="http://www.otecproyecta.cl" target="_blank">www.otecproyecta.cl</a></p>
        </div>



</body>

</html>