{{--
  Plantilla PDF cotización v2.0 — modalidad asincrónica.
  Solo estilos: el texto viene del content de la plataforma.
--}}
<style>
    .pdf-body-content-asincronica .inner-page-main {
        padding-top: 0;
    }

    .pdf-body-content-asincronica .inner-page-main > .description-curso {
        padding-top: 0;
    }

    .pdf-body-content-asincronica .inner-page-main > .investment-curso.page-break-before {
        padding-top: 0;
    }

    .pdf-body-content-asincronica {
        padding-left: 0 !important;
        padding-right: 0 !important;
    }

    .pdf-body-content-asincronica .inner-page-main > .investment-curso,
    .pdf-body-content-asincronica .inner-page-main > .payments {
        padding-left: 0;
        padding-right: 0;
    }

    .pdf-body-content-asincronica .inner-page-main > .franquicia-sence {
        padding-left: 58px;
        padding-right: 58px;
        box-sizing: border-box;
    }

    .pdf-body-content-asincronica .investment-curso > .investment-table-wrap,
    .pdf-body-content-asincronica .investment-curso > .investment-footnote,
    .pdf-body-content-asincronica .payments > .payments-intro,
    .pdf-body-content-asincronica .payments > .payments-table-wrap {
        padding-left: 58px;
        padding-right: 58px;
        box-sizing: border-box;
    }

    .description-curso-asincronica h1:not(.pdf-section-title),
    .description-curso-asincronica h2,
    .description-curso-asincronica h3,
    .description-curso-asincronica h4,
    .pdf-body-content-asincronica .investment-curso > h2.pdf-section-title,
    .pdf-body-content-asincronica .payments > h2.pdf-section-title {
        background-color: #13294b !important;
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

    .description-curso-asincronica p {
        font-family: Arial, Helvetica, sans-serif !important;
        font-size: 14px !important;
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

    .description-curso-asincronica ol,
    .description-curso-asincronica ul {
        margin: 8px 0 16px 0;
        padding-left: 64px;
        padding-right: 40px;
        color: #000000;
        box-sizing: border-box;
        page-break-inside: avoid;
    }

    .description-curso-asincronica li {
        font-family: Arial, Helvetica, sans-serif !important;
        font-size: 14px !important;
        font-weight: normal !important;
        font-style: italic !important;
        color: #000000 !important;
        text-align: justify !important;
        line-height: 1.4;
        margin-bottom: 10px;
    }

    .pdf-body-content-asincronica .investment-curso .investment-footnote,
    .pdf-body-content-asincronica .payments .payments-intro,
    .pdf-body-content-asincronica .franquicia-sence .franquicia-text {
        font-family: Arial, Helvetica, sans-serif !important;
        font-size: 14px !important;
        font-weight: normal !important;
        font-style: italic !important;
        color: #000000 !important;
        text-align: justify !important;
        line-height: 1.4;
    }
</style>
