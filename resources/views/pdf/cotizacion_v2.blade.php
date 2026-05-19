<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Aptos:wght@400&display=swap" rel="stylesheet">

    <title>OTEC PROYECTA</title>
    <style>
        @page {
            margin: 0;
        }

        html,
        body {
            width: 100%;
            height: 100%;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            position: relative;
            height: 100vh;
        }

        .container {
            position: relative;
            padding: 0;
            box-sizing: border-box;
            z-index: 1;
        }

        .first-page-background {
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            overflow: hidden;
            z-index: 0;
            background-color: #132d5b;
            background-image: url("{{ public_path('img/nueva_fortada/fondo.jpg') }}");
            background-repeat: no-repeat;
            background-position: center top;
            background-size: 100% 100%;
        }

        .fixed-image {
            position: fixed;
            top: 0;
            left: 0;
            width: 200px;
            margin: -35;
            z-index: 10;
        }

        .fixed-image-bottom-right {
            position: fixed;
            bottom: 0;
            right: 0;
            width: 200px;
            margin: -35;
            z-index: 10;
        }

        .header-text {
            position: fixed;
            top: 0;
            right: 20px;
            text-align: right;
            color: #ffffff;
            z-index: 10;
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
            z-index: 10;
        }

        .titulo-center-text {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            height: auto;
            text-align: center;
            width: 90%;
            z-index: 10;
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
            z-index: 10;
        }

        .accreditation-row {
            position: fixed;
            top: 80%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 90%;
            display: table;
            table-layout: fixed;
            z-index: 20;
            color: #ffffff;
            text-align: center;
        }

        .accreditation-cell {
            display: table-cell;
            vertical-align: middle;
            width: 33.33%;
            padding: 10px;
            color: #ffffff !important;
        }

        .accreditation-cell p {
            margin: 0;
            color: #ffffff !important;
        }

        .titulo-center-text-h2 {
            font-family: 'Aptos', sans-serif;
            color: #ffffff !important;
            background-color: transparent;
            font-size: 28px;
            width: 100% !important;
            font-style: italic;
            margin: 0;
            padding: 0;
            position: relative;
            z-index: 20;
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

        .title-center-text,
        .title-center-text p,
        .title-center-text td {
            color: #ffffff !important;
        }

        .title-center-text td {
            border-bottom: none;
        }

        .title-center-text .logo_sence {
            filter: brightness(0) invert(1);
        }

        h2 {
            background-color: #fc4c02;
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
            page-break-after: avoid;
            page-break-inside: avoid;
            break-inside: avoid;
            break-after: avoid-page;
        }


        .content-section {
            margin-top: 850px;
            page-break-inside: avoid;
            padding-left: 34px;
            padding-right: 34px;
            box-sizing: border-box;
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
            background-color: #9bcbeb;
            color: #000000;
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
            color: #ffffff !important;
            font-family: 'Aptos', sans-serif;
            font-size: 24px;
            background-color: transparent;
            font-style: oblique;
            font-size: 25px;
            width: 100% !important;
            position: relative;
            z-index: 20;
        }

        .description-curso p {
            font-family: 'Calibri', sans-serif;
            font-size: 16px;
            font-style: italic;
            text-align: justify;
            margin-left: 0;
            margin-right: 0;
        }

        .description-curso h1,
        .description-curso h2,
        .description-curso h3,
        .description-curso h4,
        .description-curso h5,
        .description-curso h6 {
            page-break-inside: avoid;
            page-break-after: avoid;
            break-inside: avoid;
            break-after: avoid-page;
        }

        .description-curso .heading-with-next {
            page-break-inside: avoid;
            page-break-after: auto;
            break-inside: avoid;
        }

        .description-curso .heading-with-next h1,
        .description-curso .heading-with-next h2,
        .description-curso .heading-with-next h3,
        .description-curso .heading-with-next h4,
        .description-curso .heading-with-next h5,
        .description-curso .heading-with-next h6 {
            margin-bottom: 10px;
        }

        .description-curso h1+*,
        .description-curso h2+*,
        .description-curso h3+*,
        .description-curso h4+*,
        .description-curso h5+*,
        .description-curso h6+* {
            page-break-before: avoid;
            break-before: avoid-page;
        }

        .objetivo-especifico-text {
            font-family: 'Calibri', sans-serif;
            font-size: 16px;
            font-style: italic;
            text-align: justify;
            margin-top: 12px;
            margin-bottom: 12px;
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
            border-radius: 22px;
            /* Bordes redondeados */
            margin: 20px 0;
            padding: 18px 24px;
            overflow: hidden;
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

        /* Margenes solo para paginas posteriores a la portada */
        .inner-pages-margin {
            padding-left: 34px;
            padding-right: 34px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="first-page-background"></div>

        <!-- <img src="{{ public_path('img/pdf/footer_right.png') }}" alt="" class="fixed-image-bottom-right"> -->
        <!-- <img src="{{ public_path('img/pdf/logo_proyecta.png') }}" alt="" class="center-image"> -->

        <div class="header-text">
            <p>Cotización: {{ $cotizacion->name }}</p>
            <p>Fecha: {{ $cotizacion->created_at->format('d/m/Y') }}</p>
        </div>

        <div class="titulo-center-text">
            <h2 class="titulo-center-text-h2">{{ $course->name }}</h2>
            <br>
            <h4 class="titulo-center-text-h2">
                @if($cotizacion->grup === true || $cotizacion->grup === 'true')
                Modalidad {{ $course->modality }}
                @else
                {{ $cotizacion->activity->name }} modalidad {{ $course->modality }}
                @endif
            </h4>

            @if($cotizacion->type === 'Con Franquicia')
            <h2 class="cod_sence">Código Sence Nº {{ $course->cod_sence }}</h2>
            @endif
        </div>

        <div class="accreditation-row">
            <div class="accreditation-cell">
                <p>ORGANISMO TÉCNICO DE CAPACITACIÓN ACREDITADO</p>
            </div>
            <div class="accreditation-cell">
                <p>NCh 2728:2015</p>
            </div>
            <div class="accreditation-cell">
                <img src="{{ public_path('img/pdf/logo_sence.png') }}" alt="" class="logo_sence">
            </div>
        </div>

        <div class="content-section description-curso">
            @php
            $content = $cotizacion->content;
            $objetivoTexto = 'Al finalizar el curso los participantes estarán en condiciones de manejar en forma segura los distintos tipos de herramientas manuales y eléctricas.';
            $objetivoHtml = '<p class="objetivo-especifico-text">' . $objetivoTexto . '</p>';

            if (stripos($content, $objetivoTexto) === false) {
                $pattern = '/(<h[1-6][^>]*>\s*OBJETIVO\s+ESPEC[ÍI]FICO\s*<\/h[1-6]>)/iu';
                $content = preg_replace($pattern, '$1' . $objetivoHtml, $content, 1);
            }

            // Evita cortes entre paginas: agrupa cada titulo
            // solo con su primer parrafo inmediato.
            $content = preg_replace_callback(
                '/(<h[1-6][^>]*>.*?<\/h[1-6]>)(\s*<p[^>]*>.*?<\/p>)/isu',
                function ($matches) {
                    return '<div class="heading-with-next">' . $matches[1] . $matches[2] . '</div>';
                },
                $content
            );
            @endphp

            {!! $content !!}
        </div>

        <div class="investment-curso page-break-before inner-pages-margin">
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

        <div class="payments page-break-inside inner-pages-margin">
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
        <div class="inner-pages-margin">
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



        <div class="inner-pages-margin">
            <div class="contact-info page-break-inside">
                <h1>COMUNÍCATE CON NOSOTROS</h1>
                <p><strong>Yasna Carreño Cortés</strong></p>
                <p>Email: <a href="mailto:contacto@otecproyecta.cl">contacto@otecproyecta.cl</a></p>
                <p>Directora - (+56) 9 3397 4153</p>
                <p>Te invitamos a revisar todas nuestras novedades en <a href="http://www.otecproyecta.cl" target="_blank">www.otecproyecta.cl</a></p>
            </div>
        </div>



</body>

</html>
