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
        }

        .first-page-background img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
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

        .cover-header-meta {
            position: absolute;
            top: 0;
            right: 48px;
            z-index: 10;
            padding-top: 130px;
            text-align: right;
            color: #ffffff;
            font-family: Arial, Helvetica, sans-serif;
            text-transform: uppercase;
        }

        .cover-header-meta .cotizacion-codigo {
            margin: 0 0 6px 0;
            font-size: 15px;
            font-weight: bold;
            letter-spacing: 0.04em;
            line-height: 1.2;
        }

        .cover-header-meta .cotizacion-fecha {
            margin: 0;
            font-size: 13px;
            font-weight: normal;
            letter-spacing: 0.03em;
            line-height: 1.2;
        }

        .cover-course-info {
            position: absolute;
            left: 52px;
            top: 42%;
            width: 58%;
            z-index: 10;
            text-align: left;
            padding: 0;
            box-sizing: border-box;
        }

        .cover-course-label {
            color: #99caea;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 14px;
            font-weight: normal;
            font-style: normal;
            margin: 0 0 18px 0;
            line-height: 1.35;
            letter-spacing: 0.01em;
        }

        .cover-course-title {
            color: #ffffff !important;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 34px;
            font-weight: bold;
            font-style: normal;
            margin: 0 0 18px 0;
            line-height: 1.15;
            padding: 0;
            width: 100%;
            background: none;
            text-transform: none;
        }

        .cover-course-sence {
            color: #99caea !important;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 16px;
            font-weight: normal;
            font-style: normal;
            margin: 0;
            line-height: 1.35;
            padding: 0;
            width: 100%;
            background: none;
            text-transform: none;
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

        .cover-footer {
            position: absolute;
            left: 0;
            bottom: 0;
            width: 100%;
            height: 140px;
            padding-bottom: 28px;
            box-sizing: border-box;
            z-index: 10;
        }

        .cover-footer-bg {
            position: absolute;
            left: 0;
            bottom: 0;
            width: 100%;
            height: 100%;
        }

        .cover-footer-content {
            position: absolute;
            left: 52px;
            bottom: 150px;
            z-index: 11;
            text-align: left;
        }

        .cover-footer-logo {
            width: 108px;
            height: auto;
            display: block;
            margin: 0 0 12px 0;
        }

        .cover-footer-line {
            color: #ffffff !important;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
            font-weight: bold;
            font-style: normal;
            text-transform: uppercase;
            margin: 0 0 6px 0;
            line-height: 1.35;
            letter-spacing: 0.04em;
        }

        .cover-footer-line:last-child {
            margin-bottom: 0;
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

        .pdf-page-cover {
            position: relative;
            height: 11in;
            min-height: 11in;
            page-break-after: always;
            break-after: page;
            overflow: hidden;
        }

        .pdf-page-cover.pdf-page-closing {
            page-break-before: always;
            break-before: page;
            page-break-after: auto;
            break-after: auto;
        }

        .closing-page-contact {
            position: absolute;
            left: 52px;
            bottom: 200px;
            z-index: 10;
            width: 58%;
            text-align: left;
            color: #ffffff;
            font-family: Arial, Helvetica, sans-serif;
        }

        .closing-page-contact .closing-contact-heading {
            font-size: 22px;
            font-weight: bold;
            font-style: normal;
            margin: 0 0 18px 0;
            line-height: 1.25;
            color: #ffffff !important;
        }

        .closing-page-contact .closing-contact-name {
            font-size: 15px;
            font-weight: bold;
            font-style: normal;
            margin: 0 0 8px 0;
            line-height: 1.35;
            color: #ffffff !important;
        }

        .closing-page-contact p {
            font-size: 13px;
            font-weight: normal;
            font-style: normal;
            margin: 0 0 6px 0;
            line-height: 1.4;
            color: #ffffff !important;
        }

        .closing-page-contact .closing-contact-invite {
            margin-top: 18px;
            margin-bottom: 6px;
        }

        .closing-page-contact .closing-contact-website {
            font-size: 14px;
            font-weight: bold;
            margin: 0;
            color: #ffffff !important;
        }

        .pdf-body-content {
            padding-top: 0;
            padding-left: 58px;
            padding-right: 58px;
            box-sizing: border-box;
        }

        .inner-page-header {
            width: 100%;
            margin-bottom: 8px;
            padding: 0;
            box-sizing: border-box;
        }

        .inner-page-header-bar {
            width: 100%;
            max-width: 100%;
            height: auto;
            display: block;
            margin: 0 0 22px 0;
        }

        table.inner-page-header-row {
            width: 100%;
            max-width: 100%;
            table-layout: fixed;
            border-collapse: collapse;
            margin: 0 0 28px 0 !important;
            border-radius: 0 !important;
            box-shadow: none !important;
            overflow: visible !important;
            font-size: inherit;
        }

        table.inner-page-header-row td {
            vertical-align: middle;
            border: none !important;
            border-bottom: none !important;
            padding: 0 !important;
            background: transparent !important;
        }

        .inner-page-header-logo-cell {
            width: 80px;
            text-align: left;
            vertical-align: top;
            padding-top: 0 !important;
        }

        .inner-page-header-cotizacion-cell {
            text-align: right;
            padding-left: 10px;
            padding-top: 0 !important;
            padding-right: 0 !important;
            vertical-align: middle;
        }

        .inner-page-header-logo {
            width: 68px;
            max-width: 68px;
            height: auto;
            display: block;
            padding-top: 20px;
        }

        .inner-page-header-cotizacion {
            color: #001642;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
            font-weight: bold;
            font-style: normal;
            text-transform: uppercase;
            letter-spacing: 0.02em;
            margin: 0;
            padding: 0;
            line-height: 1.3;
            text-align: right;
            display: block;
        }

        .pdf-body-content .content-section {
            margin-top: 0;
            padding-left: 0;
            padding-right: 0;
            box-sizing: border-box;
        }

        h1.pdf-section-title,
        h2.pdf-section-title {
            color: #000000 !important;
            background: none !important;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 26px !important;
            font-weight: bold;
            font-style: normal;
            text-transform: none;
            text-align: left;
            margin: 0 0 20px 0;
            padding: 0;
            width: 100%;
            line-height: 1.2;
            letter-spacing: normal;
            page-break-after: avoid;
            break-after: avoid-page;
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

        .description-curso {
            font-family: Arial, Helvetica, sans-serif;
            color: #2C3E50;
            font-size: 16px;
            line-height: 1.5;
        }

        .description-curso h1:not(.pdf-section-title) {
            color: #000000 !important;
            font-size: 26px !important;
            font-weight: bold;
            font-style: normal;
            text-transform: none;
            background: none !important;
            padding: 0;
            margin: 0 0 20px 0;
            width: 100%;
            line-height: 1.2;
        }

        .description-curso h2,
        .description-curso h3,
        .description-curso h4,
        .description-curso h5,
        .description-curso h6 {
            background: none !important;
            background-color: transparent !important;
            color: #ea6d42 !important;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 18px;
            font-weight: bold;
            font-style: normal;
            text-transform: uppercase;
            text-align: left;
            padding: 0;
            margin: 28px 0 12px 0;
            width: 100%;
            border-radius: 0;
            letter-spacing: 0.02em;
            page-break-inside: avoid;
            page-break-after: avoid;
            break-inside: avoid;
            break-after: avoid-page;
        }

        .description-curso h1 + h2,
        .description-curso h1 + h3,
        .pdf-section-title + h2,
        .pdf-section-title + h3 {
            margin-top: 20px;
        }

        .description-curso p {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 16px;
            font-weight: normal;
            font-style: normal !important;
            color: #2C3E50 !important;
            text-align: justify;
            line-height: 1.5;
            margin: 0 0 12px 0;
        }

        .description-curso ul,
        .description-curso ol {
            margin: 8px 0 16px 0;
            padding-left: 24px;
            color: #2C3E50;
        }

        .description-curso ul li,
        .description-curso ol li {
            font-size: 16px;
            color: #2C3E50 !important;
            text-align: justify;
            line-height: 1.5;
            margin-bottom: 10px;
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
            font-family: Arial, Helvetica, sans-serif;
            font-size: 16px;
            font-style: normal !important;
            color: #2C3E50 !important;
            text-align: justify;
            line-height: 1.5;
            margin-top: 12px;
            margin-bottom: 12px;
        }

        .text-add {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 16px;
            font-style: normal;
            color: #2C3E50;
            line-height: 1.5;
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

        .investment-curso {
            margin-top: 8px;
        }

        .investment-curso table.investment-table {
            width: 100%;
            border-collapse: collapse;
            margin: 0 0 14px 0;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 14px;
            text-align: center;
            border-radius: 0;
            overflow: visible;
            box-shadow: none;
            border: 1px solid #d1d5db;
        }

        .investment-curso table.investment-table thead {
            background-color: #fa4c02;
        }

        .investment-curso table.investment-table thead th {
            background-color: #fa4c02 !important;
            color: #ffffff !important;
            font-weight: bold;
            font-style: normal;
            text-transform: uppercase;
            text-align: center;
            padding: 14px 10px;
            border: none !important;
            font-size: 13px;
            letter-spacing: 0.02em;
        }

        .investment-curso table.investment-table tbody tr,
        .investment-curso table.investment-table tbody tr:nth-child(even) {
            background-color: #ffffff !important;
        }

        .investment-curso table.investment-table tbody td {
            color: #1a202c !important;
            font-weight: bold;
            font-style: normal;
            text-align: center;
            padding: 14px 10px;
            border-top: none !important;
            border-bottom: none !important;
            border-left: 1px solid #d1d5db !important;
            border-right: 1px solid #d1d5db !important;
            vertical-align: middle;
            font-size: 14px;
        }

        .investment-curso table.investment-table tbody td:first-child {
            border-left: none !important;
        }

        .investment-curso table.investment-table tbody td:last-child {
            border-right: none !important;
        }

        .investment-curso .investment-footnote {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            font-style: normal;
            font-weight: normal;
            color: #1a202c;
            text-align: left;
            margin: 12px 0 0 0;
            line-height: 1.45;
        }

        .investment-curso .investment-footnote-asterisk {
            color: #fa4c02;
            font-weight: bold;
        }

        .payments {
            margin-top: 32px;
        }

        .payments .payments-intro {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 14px;
            font-style: normal;
            font-weight: normal;
            color: #1a202c;
            line-height: 1.5;
            margin: 0 0 16px 0;
            text-align: left;
        }

        .payments .payments-table-wrap {
            width: 65%;
            margin: 0 auto;
        }

        .payments table.payments-table {
            width: 100%;
            border-collapse: collapse;
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 14px;
            border-radius: 0;
            overflow: visible;
            box-shadow: none;
            border: none;
            table-layout: fixed;
        }

        .payments table.payments-table thead {
            background-color: #fa4c02;
        }

        .payments table.payments-table thead th {
            background-color: #fa4c02 !important;
            color: #ffffff !important;
            font-weight: bold;
            font-style: normal;
            text-transform: uppercase;
            text-align: center;
            padding: 14px 10px;
            border: none !important;
            font-size: 13px;
            letter-spacing: 0.02em;
        }

        .payments table.payments-table tbody tr:nth-child(odd) {
            background-color: #ffffff !important;
        }

        .payments table.payments-table tbody tr:nth-child(even) {
            background-color: #f2f2f2 !important;
        }

        .payments table.payments-table tbody td {
            color: #1a202c !important;
            font-style: normal;
            padding: 12px 14px;
            border: none !important;
            vertical-align: middle;
            font-size: 14px;
        }

        .payments table.payments-table thead th,
        .payments table.payments-table tbody td.payments-label,
        .payments table.payments-table tbody td.payments-value {
            text-align: center;
        }

        .payments table.payments-table thead th {
            width: 50%;
        }

        .payments table.payments-table tbody td.payments-label {
            font-weight: bold;
            width: 50%;
        }

        .payments table.payments-table tbody td.payments-value {
            font-weight: normal;
            width: 50%;
        }

        .franquicia-sence {
            margin-top: 32px;
        }

        .franquicia-sence .franquicia-text {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 14px;
            font-style: normal;
            font-weight: normal;
            color: #1a202c;
            text-align: left;
            line-height: 1.5;
            margin: 0;
        }

        /* Margen lateral heredado de .pdf-body-content (portada sin cambios) */
        .inner-pages-margin {
            padding-left: 0;
            padding-right: 0;
        }
    </style>
</head>

<body>
    <div class="pdf-page-cover">
        <div class="first-page-background">
            <img src="{{ public_path('img/cotizacion_v2/portada_v2.jpg') }}" alt="">
        </div>

        @php
            $fechaPortada = $cotizacion->created_at->copy()->locale('es');
            $fechaPortadaTexto = strtoupper($fechaPortada->translatedFormat('D j'))
                . ' DE '
                . strtoupper($fechaPortada->translatedFormat('F Y'));
        @endphp

        <div class="cover-header-meta">
            <p class="cotizacion-codigo">COTIZACIÓN {{ $cotizacion->name }}</p>
            <p class="cotizacion-fecha">{{ $fechaPortadaTexto }}</p>
        </div>

        <div class="cover-course-info">
            <p class="cover-course-label">
                @if($cotizacion->grup === true || $cotizacion->grup === 'true')
                    Curso Modalidad {{ $course->modality }}
                @else
                    {{ $cotizacion->activity?->name ?? 'Curso' }} modalidad {{ $course->modality }}
                @endif
            </p>

            <h2 class="cover-course-title">{{ $course->name }}</h2>

            @if($cotizacion->type === 'Con Franquicia')
                <p class="cover-course-sence">Código SENCE N°{{ $course->cod_sence }}</p>
            @endif
        </div>

        <div class="cover-footer">
            @if(file_exists(public_path('img/cotizacion_v2/pie_portada_v2.png')))
                <img src="{{ public_path('img/cotizacion_v2/pie_portada_v2.png') }}" alt="" class="cover-footer-bg">
            @endif

            <div class="cover-footer-content">
                <img src="{{ public_path('img/cotizacion_v2/logo_sence_blanco.png') }}" alt="" class="cover-footer-logo">
                <p class="cover-footer-line">ORGANISMO TÉCNICO DE CAPACITACIÓN ACREDITADO</p>
                <p class="cover-footer-line">NCh 2728:2015</p>
            </div>
        </div>
    </div>

    <div class="pdf-body-content">
        @php
            $barraSuperiorPath = public_path('img/cotizacion_v2/barra_superior_v2.png');
            $logoInnerPath = public_path('img/cotizacion_v2/icono_logo.png');
        @endphp

        <header class="inner-page-header">
            @if(file_exists($barraSuperiorPath))
                <img src="{{ $barraSuperiorPath }}" alt="" class="inner-page-header-bar">
            @endif

            <table class="inner-page-header-row">
                <tr>
                    <td class="inner-page-header-logo-cell">
                        @if(file_exists($logoInnerPath))
                            <img src="{{ $logoInnerPath }}" alt="" class="inner-page-header-logo">
                        @endif
                    </td>
                    <td class="inner-page-header-cotizacion-cell">
                        <span class="inner-page-header-cotizacion">COTIZACIÓN {{ $cotizacion->name }}</span>
                    </td>
                </tr>
            </table>
        </header>

        <div class="content-section description-curso">
            @php
            $content = $cotizacion->content;

            if (trim(strip_tags((string) $content)) === '') {
                $content = $addCourse->description ?? '';
            }
            $objetivoTexto = 'Al finalizar el curso los participantes estarán en condiciones de manejar en forma segura los distintos tipos de herramientas manuales y eléctricas.';
            $objetivoHtml = '<p class="objetivo-especifico-text">' . $objetivoTexto . '</p>';

            if (stripos($content, $objetivoTexto) === false) {
                $pattern = '/(<h[1-6][^>]*>\s*OBJETIVO\s+ESPEC[ÍI]FICO\s*<\/h[1-6]>)/iu';
                $content = preg_replace($pattern, '$1' . $objetivoHtml, $content, 1);
            }

            // Evita duplicar el título fijo "Alcances del Curso"
            $content = preg_replace('/<h1[^>]*>\s*Alcances\s+del\s+Curso\s*<\/h1>\s*/iu', '', $content, 1);

            @endphp

            <h1 class="pdf-section-title">Alcances del Curso</h1>

            {!! $content !!}
        </div>

        <div class="investment-curso page-break-before inner-pages-margin">
            <h2 class="pdf-section-title">Inversión</h2>
            @php
            $hasGrup = collect($costs)->contains(function($cost) {
            return !is_null($cost['grup']);
            });

            function formatNumber($number) {
            return number_format($number, 0, ',', '.');
            }

            function formatInvestmentHoras($value) {
            $v = trim((string) $value);
            if ($v === '') {
            return $v;
            }
            if (preg_match('/horas?/iu', $v)) {
            return $v;
            }
            return $v . ' horas';
            }

            function formatInvestmentParticipantes($value) {
            $v = trim((string) $value);
            if ($v === '') {
            return $v;
            }
            if (preg_match('/personas?/iu', $v)) {
            return $v;
            }
            return $v . ' personas';
            }

            function formatInvestmentMoney($number) {
            return '$' . formatNumber($number);
            }
            @endphp

            <table class="investment-table">
                <thead>
                    <tr>
                        @if($hasGrup)
                        <th>Grupos</th>
                        @endif
                        <th>Total Horas</th>
                        <th>Participantes</th>
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
                        <td>{{ formatInvestmentHoras($cost['thour']) }}</td>
                        <td>{{ formatInvestmentParticipantes($cost['tpart']) }}</td>
                        <td>{{ formatInvestmentMoney($cost['vunit']) }}</td>
                        <td>{{ formatInvestmentMoney($cost['costs']) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <p class="investment-footnote"><span class="investment-footnote-asterisk">*</span> Para mantener valores ofertados se debe considerar el mínimo de participantes informados en esta cotización.</p>
        </div>

        <div class="payments inner-pages-margin">
            <h2 class="pdf-section-title">Medios de Pago</h2>
            <p class="payments-intro">Pago a 30 días mediante depósito o transferencia bancaria a:</p>
            <div class="payments-table-wrap">
            <table class="payments-table">
                <thead>
                    <tr>
                        <th>Descripción</th>
                        <th>Detalle</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="payments-label">Razón Social</td>
                        <td class="payments-value">OTEC Proyecta SpA</td>
                    </tr>
                    <tr>
                        <td class="payments-label">RUT</td>
                        <td class="payments-value">77.495.502-K</td>
                    </tr>
                    <tr>
                        <td class="payments-label">Cuenta Corriente</td>
                        <td class="payments-value">87424197</td>
                    </tr>
                    <tr>
                        <td class="payments-label">Entidad Bancaria</td>
                        <td class="payments-value">Banco Santander</td>
                    </tr>
                </tbody>
            </table>
            </div>
        </div>

        @if($cotizacion->type === 'Con Franquicia')
        <div class="franquicia-sence inner-pages-margin">
            <h2 class="pdf-section-title">Franquicia Sence</h2>
            <p class="franquicia-text">La empresa debe realizar la inscripción del curso ante SENCE, a más tardar 2 días hábiles antes del inicio del curso.</p>
        </div>

        @endif

    </div>

    <div class="pdf-page-cover pdf-page-closing">
        <div class="first-page-background">
            <img src="{{ public_path('img/cotizacion_v2/portada_v2.jpg') }}" alt="">
        </div>

        <div class="closing-page-contact">
            <p class="closing-contact-heading">¡Comunícate con Nosotros!</p>
            <p class="closing-contact-name">Yasna Carreño Cortés</p>
            <p>Directora - (+56) 9 3397 4153</p>
            <p>Email: contacto@otecproyecta.cl</p>
            <p class="closing-contact-invite">Te invitamos a revisar todas nuestras novedades en</p>
            <p class="closing-contact-website">otecproyecta.cl</p>
        </div>
    </div>

</body>

</html>
