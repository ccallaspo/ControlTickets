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

    <div class="presencial-block-keep">
        <h2>Entregables</h2>
        <p>Una vez finalizada la capacitación, se le enviará al cliente:</p>
        <ul>
            <li>Informe de capacitación</li>
            <li>Resultados encuesta de satisfacción del participante</li>
            <li>Certificados de aprobación, los que podrán ser validados a través de nuestra página WEB: https://otecproyecta.cl/</li>
        </ul>
    </div>
</div>

<style>
    /* Presencial: sin header interior (logo / COTIZACIÓN); aire superior vía márgenes de hoja */
    .pdf-body-content-presencial .inner-page-main {
        padding-top: 0;
    }

    .pdf-body-content-presencial .inner-page-main > .description-curso {
        padding-top: 0;
    }

    .pdf-body-content-presencial .inner-page-main > .investment-curso.page-break-before {
        padding-top: 0;
    }

    /* Evita dejar la lista de entregables huérfana al inicio de página */
    .description-curso-presencial .presencial-block-keep {
        page-break-inside: avoid;
    }

    /* Contenedor: franjas al borde izquierdo; márgenes laterales simétricos en el texto */
    .pdf-body-content-presencial {
        padding-left: 0 !important;
        padding-right: 0 !important;
    }

    .pdf-body-content-presencial .inner-page-main > .investment-curso,
    .pdf-body-content-presencial .inner-page-main > .payments {
        padding-left: 0;
        padding-right: 0;
    }

    .pdf-body-content-presencial .inner-page-main > .franquicia-sence {
        padding-left: 58px;
        padding-right: 58px;
        box-sizing: border-box;
    }

    .pdf-body-content-presencial .investment-curso > .investment-table,
    .pdf-body-content-presencial .investment-curso > .investment-footnote,
    .pdf-body-content-presencial .payments > .payments-intro,
    .pdf-body-content-presencial .payments > .payments-table-wrap {
        padding-left: 58px;
        padding-right: 58px;
        box-sizing: border-box;
    }

    /*
     * Franjas título (referencia PDF presencial): ~48% ancho, borde izq. recto, azul #14284b.
     */
    .description-curso-presencial h1:not(.pdf-section-title),
    .description-curso-presencial h2,
    .description-curso-presencial h3,
    .description-curso-presencial h4,
    .pdf-body-content-presencial .investment-curso > h2.pdf-section-title,
    .pdf-body-content-presencial .payments > h2.pdf-section-title {
        background-color: #14284b !important;
        color: #ffffff !important;
        font-family: Arial, Helvetica, sans-serif;
        font-size: 16px !important;
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
        font-family: Arial, Helvetica, sans-serif !important;
        font-size: 16px !important;
        font-weight: normal !important;
        font-style: italic !important;
        color: #000000 !important;
        text-align: justify !important;
        line-height: 1.4;
        margin: 0 0 14px 0;
        padding-left: 40px;
        padding-right: 40px;
        box-sizing: border-box;
    }

    .description-curso-presencial ol,
    .description-curso-presencial ul {
        margin: 8px 0 16px 0;
        padding-left: 64px;
        padding-right: 40px;
        color: #000000;
        box-sizing: border-box;
        page-break-inside: avoid;
    }

    .description-curso-presencial li {
        font-family: Arial, Helvetica, sans-serif !important;
        font-size: 16px !important;
        font-weight: normal !important;
        font-style: italic !important;
        color: #000000 !important;
        text-align: justify !important;
        line-height: 1.4;
        margin-bottom: 10px;
    }

    /* Párrafos de Inversión / Medios de pago / Franquicia (mismo estilo) */
    .pdf-body-content-presencial .investment-curso .investment-footnote,
    .pdf-body-content-presencial .payments .payments-intro,
    .pdf-body-content-presencial .franquicia-sence .franquicia-text {
        font-family: Arial, Helvetica, sans-serif !important;
        font-size: 16px !important;
        font-weight: normal !important;
        font-style: italic !important;
        color: #000000 !important;
        text-align: justify !important;
        line-height: 1.4;
    }
</style>
