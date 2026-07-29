<?php

namespace App\Services;

use App\Models\Course;
use Illuminate\Support\Str;

/**
 * Vinculación plantilla PDF v2.0 ↔ modalidad del curso (solo por código).
 * Usa el campo courses.modality (mismo texto que el maestro Modalidades).
 */
class CotizacionPdfV20TemplateResolver
{
    public const VIEW_PRESENCIAL = 'pdf.templates.cotizacion_v2_0_presencial';

    public const VIEW_ASINCRONICA = 'pdf.templates.cotizacion_v2_0_asincronica';

    public const VIEW_SINCRONICA = 'pdf.templates.cotizacion_v2_0_sincronica';

    public function resolveForCourse(Course $course): ?string
    {
        return $this->resolveFromModalityName($course->modality);
    }

    public function resolveFromModalityName(?string $modalityName): ?string
    {
        $modalityNorm = Str::lower(Str::ascii(trim((string) $modalityName)));

        if ($modalityNorm === '') {
            return null;
        }

        // Asincrónica antes que sincrónica: "asincronica" contiene "sincron".
        // Maestro actual: "E-Learning" = asincrónica (Word Cotizacion curso asincrónico).
        if (
            str_contains($modalityNorm, 'asincron')
            || str_contains($modalityNorm, 'e-learning')
            || str_contains($modalityNorm, 'elearning')
        ) {
            return self::VIEW_ASINCRONICA;
        }

        if (str_contains($modalityNorm, 'presencial')) {
            return self::VIEW_PRESENCIAL;
        }

        // Maestro actual: "A-Distancia" ≈ sincrónica remota.
        if (
            str_contains($modalityNorm, 'sincron')
            || str_contains($modalityNorm, 'a-distancia')
            || str_contains($modalityNorm, 'distancia')
        ) {
            return self::VIEW_SINCRONICA;
        }

        return null;
    }
}
