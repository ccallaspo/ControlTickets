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
        // Maestro: "E-learning Asincrónico" (también textos que contengan "asincron").
        if (str_contains($modalityNorm, 'asincron')) {
            return self::VIEW_ASINCRONICA;
        }

        if (str_contains($modalityNorm, 'presencial')) {
            return self::VIEW_PRESENCIAL;
        }

        // Sincrónica / remota en vivo.
        if (
            str_contains($modalityNorm, 'sincron')
            || str_contains($modalityNorm, 'a-distancia')
            || str_contains($modalityNorm, 'distancia')
        ) {
            return self::VIEW_SINCRONICA;
        }

        return null;
    }

    /**
     * Quita width/height fijos de <img> del editor para que DomPDF respete max-width.
     * No altera el resto del HTML.
     */
    public static function constrainContentImages(?string $html): string
    {
        $html = (string) $html;

        if ($html === '') {
            return $html;
        }

        $constrained = preg_replace_callback('/<img\b([^>]*)>/i', function (array $matches): string {
            $attrs = $matches[1];
            $attrs = preg_replace('/\s(?:width|height)\s*=\s*(["\'])[^"\']*\1/i', '', $attrs) ?? $attrs;
            $attrs = preg_replace_callback(
                '/\sstyle\s*=\s*(["\'])(.*?)\1/is',
                function (array $styleMatch): string {
                    $style = preg_replace('/\b(?:max-)?width\s*:\s*[^;]+;?/i', '', $styleMatch[2]) ?? $styleMatch[2];
                    $style = preg_replace('/\bheight\s*:\s*[^;]+;?/i', '', $style) ?? $style;
                    $style = trim(preg_replace('/\s+/', ' ', $style) ?? $style, " \t;");

                    return $style === ''
                        ? ''
                        : ' style="' . htmlspecialchars($style, ENT_QUOTES, 'UTF-8') . '"';
                },
                $attrs
            ) ?? $attrs;

            $attrs = preg_replace_callback(
                '/\ssrc\s*=\s*(["\'])(.*?)\1/i',
                function (array $srcMatch): string {
                    $src = html_entity_decode($srcMatch[2], ENT_QUOTES, 'UTF-8');
                    $path = parse_url($src, PHP_URL_PATH) ?: $src;
                    $relative = ltrim((string) $path, '/');

                    if (str_starts_with($relative, 'storage/')) {
                        $full = public_path($relative);
                        if (is_file($full)) {
                            return ' src="' . htmlspecialchars($full, ENT_QUOTES, 'UTF-8') . '"';
                        }
                    }

                    return $srcMatch[0];
                },
                $attrs
            ) ?? $attrs;

            return '<img' . $attrs . '>';
        }, $html);

        return is_string($constrained) ? $constrained : $html;
    }
}
