{{-- Plantilla PDF cotización v2.0 — modalidad presencial --}}
<div class="cotizacion-v2-presencial">
    <h2>Metodología</h2>
    <p>La capacitación se desarrollará en modalidad presencial, en las dependencias acordadas con la empresa, conforme al calendario, horarios y número de participantes definidos en esta cotización.</p>
    <p>Las sesiones presenciales combinan exposición guiada, ejercicios prácticos y talleres, favoreciendo la participación activa y el aprendizaje aplicado en el aula.</p>

    <h2>Evaluación</h2>
    <ol>
        <li>Evaluación diagnóstica</li>
        <li>Evaluación final al término del curso</li>
    </ol>
    <p>La nota mínima de aprobación es 4, en la escala de 1 a 7 y con un 75% de participación como mínimo.</p>

    <h2>Entregables</h2>
    <p>Una vez finalizada la capacitación, se le enviará al cliente:</p>
    <ul>
        <li>Informe de capacitación</li>
        <li>Resultados encuesta de satisfacción del participante</li>
        <li>Certificados de aprobación, los que podrán ser validados a través de nuestra página WEB: https://otecproyecta.cl/</li>
    </ul>
</div>

<style>
    /* Presencial: sin header interior (logo / COTIZACIÓN) — márgenes vía @page en cotizacion_v2_0 */
    .pdf-body-content-presencial .inner-page-main {
        padding-top: 0;
    }

    .pdf-body-content-presencial .inner-page-main > .description-curso {
        padding-top: 0;
    }

    .pdf-body-content-presencial .inner-page-main > .investment-curso.page-break-before {
        padding-top: 0;
    }

    /* Contenedor: franjas al borde izquierdo de la hoja; texto con margen 58px */
    .pdf-body-content-presencial {
        padding-left: 0 !important;
    }

    .pdf-body-content-presencial .inner-page-main > .investment-curso,
    .pdf-body-content-presencial .inner-page-main > .payments,
    .pdf-body-content-presencial .inner-page-main > .franquicia-sence {
        padding-left: 58px;
        box-sizing: border-box;
    }

    /*
     * Franjas título (referencia PDF presencial): ~48% ancho, borde izq. recto, azul #14284b.
     */
    .description-curso-presencial h1:not(.pdf-section-title),
    .description-curso-presencial h2,
    .description-curso-presencial h3,
    .description-curso-presencial h4 {
        background-color: #14284b !important;
        color: #ffffff !important;
        font-family: 'Times New Roman', Times, serif;
        font-size: 16px;
        font-weight: bold;
        font-style: normal;
        text-transform: uppercase;
        text-align: left;
        letter-spacing: 0.01em;
        display: block;
        box-sizing: border-box;
        width: 48%;
        margin: 22px 0 12px 0;
        padding: 7px 16px 7px 14px;
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
        border-top-right-radius: 6px;
        border-bottom-right-radius: 6px;
        page-break-inside: avoid;
        page-break-after: avoid;
    }

    .description-curso-presencial p {
        font-family: Arial, Helvetica, sans-serif;
        font-size: 16px;
        font-weight: normal;
        font-style: italic;
        color: #000000 !important;
        text-align: justify;
        line-height: 1.5;
        margin: 0 0 12px 0;
        padding-left: 14px;
        padding-right: 58px;
        box-sizing: border-box;
    }

    .description-curso-presencial ol,
    .description-curso-presencial ul {
        margin: 8px 0 16px 0;
        padding-left: 38px;
        padding-right: 58px;
        color: #000000;
        box-sizing: border-box;
        page-break-inside: avoid;
    }

    .description-curso-presencial li {
        font-family: Arial, Helvetica, sans-serif;
        font-size: 16px;
        font-weight: normal;
        font-style: italic;
        color: #000000 !important;
        text-align: justify;
        line-height: 1.5;
        margin-bottom: 10px;
    }
</style>
