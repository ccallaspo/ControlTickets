{{--
  Plantilla PDF cotización v2.0 — modalidad presencial.
  Solo estilos: el texto (Metodología, Evaluación, Entregables, etc.) viene del content de la plataforma.
--}}
<style>
    /*
     * Márgenes laterales simétricos (~10–12% por lado en carta).
     * Tipografía alineada al documento referencia presencial.
     */
    .pdf-body-content-presencial .inner-page-main {
        padding-top: 0;
    }

    .pdf-body-content-presencial .inner-page-main > .description-curso {
        padding-top: 0;
    }

    .pdf-body-content-presencial .inner-page-main > .investment-curso.page-break-before {
        padding-top: 0;
    }

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
        padding-left: 68px;
        padding-right: 68px;
        box-sizing: border-box;
    }

    .pdf-body-content-presencial .investment-curso > .investment-footnote,
    .pdf-body-content-presencial .payments > .payments-intro {
        padding-left: 68px;
        padding-right: 68px;
        box-sizing: border-box;
    }

    /*
     * Franjas título: aplican a los h1–h4 del content de plataforma
     * + Inversión / Medios de pago del shell.
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
        letter-spacing: 0.02em;
        display: block;
        box-sizing: border-box;
        width: 48%;
        margin: 20px 68px 10px 68px;
        padding: 6px 14px 6px 12px;
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
        border-top-right-radius: 16px;
        border-bottom-right-radius: 16px;
        page-break-inside: avoid;
        page-break-after: avoid;
    }

    .description-curso-presencial p {
        font-family: Arial, Helvetica, sans-serif !important;
        font-size: 15px !important;
        font-weight: normal !important;
        font-style: italic !important;
        color: #000000 !important;
        text-align: justify !important;
        line-height: 1.45;
        margin: 0 0 12px 0;
        padding-left: 80px;
        padding-right: 68px;
        box-sizing: border-box;
    }

    .description-curso-presencial ol,
    .description-curso-presencial ul {
        margin: 6px 0 14px 0;
        padding-left: 98px;
        padding-right: 68px;
        color: #000000;
        box-sizing: border-box;
        page-break-inside: avoid;
    }

    .description-curso-presencial li {
        font-family: Arial, Helvetica, sans-serif !important;
        font-size: 15px !important;
        font-weight: normal !important;
        font-style: italic !important;
        color: #000000 !important;
        text-align: justify !important;
        line-height: 1.45;
        margin-bottom: 8px;
    }

    .pdf-body-content-presencial .investment-curso .investment-footnote,
    .pdf-body-content-presencial .payments .payments-intro,
    .pdf-body-content-presencial .franquicia-sence .franquicia-text {
        font-family: Arial, Helvetica, sans-serif !important;
        font-size: 15px !important;
        font-weight: normal !important;
        font-style: italic !important;
        color: #000000 !important;
        text-align: justify !important;
        line-height: 1.45;
    }
</style>
