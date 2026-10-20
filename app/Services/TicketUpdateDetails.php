<?php

namespace App\Services;

use App\Models\Followup;
use Carbon\Carbon;
use Illuminate\Support\Str;

class TicketUpdateDetails
{
    private const LABELS = [
        'id_sence' => 'Código ID', 'exec_id_sence' => 'Código ID (Ejecución)',
        'name' => 'Ticket', 'description' => 'Descripción', 'referent' => 'Cotización',
        'event_id' => 'Estado', 'ejecutivo_id' => 'Coordinadora', 'customer_id' => 'Cliente',
        'cotizacion_id' => 'Cotización vinculada', 'task_id' => 'Proceso',
        'name_course' => 'Curso', 'cod_sence_course' => 'Código SENCE',
        'modalily' => 'Modalidad', 'week' => 'Días de clases',
        'h_star' => 'Hora de inicio', 'h_end' => 'Hora de término',
        'f_star' => 'Fecha de inicio', 'f_end' => 'Fecha de término', 'n_hours' => 'N.º de horas',
        'exec_name_course' => 'Curso (Ejecución)', 'exec_cod_sence_course' => 'Código SENCE (Ejecución)',
        'exec_modalily' => 'Modalidad (Ejecución)', 'exec_f_star' => 'Fecha de inicio (Ejecución)',
        'exec_f_end' => 'Fecha de término (Ejecución)', 'exec_n_hours' => 'N.º de horas (Ejecución)',
        'has_execution_data' => 'Datos de ejecución', 'active' => 'Activo',
        'doc_participant' => 'Nómina de participantes', 'doc_oc' => 'Orden de compra',
        'author' => 'Creado por', 'documento' => 'Documento',
    ];

    public static function rows(Followup $ticket, array $changes, array $originals = [], array $documents = []): array
    {
        $rows = [];
        foreach ($changes as $field => $value) {
            if (in_array($field, ['updated_at', 'created_at', 'changes'], true)) {
                continue;
            }

            $before = array_key_exists($field, $originals) ? $originals[$field] : $ticket->getOriginal($field);
            if ($field === 'financiamientos') {
                $oldItems = self::decode($before) ?? [];
                $newItems = self::decode($value) ?? [];
                foreach (array_unique([...array_keys($oldItems), ...array_keys($newItems)]) as $index) {
                    $old = $oldItems[$index] ?? [];
                    $new = $newItems[$index] ?? [];
                    foreach (array_unique([...array_keys($old), ...array_keys($new)]) as $key) {
                        if (($old[$key] ?? null) === ($new[$key] ?? null)) {
                            continue;
                        }
                        // El primer financiamiento también se guarda en los campos principales.
                        if ($index == 0 && array_key_exists($key, $changes)) {
                            continue;
                        }
                        $rows[] = self::row($key, $old[$key] ?? null, $new[$key] ?? null, 'Financiamiento '.($index + 1).' · ');
                    }
                }
                continue;
            }
            $rows[] = self::row($field, $before, $value);
        }

        foreach ($documents as $document) {
            $rows[] = ['label' => $document['label'], 'type' => $document['type'], 'file' => $document['file']];
        }
        return $rows;
    }

    private static function row(string $field, mixed $before, mixed $after, string $prefix = ''): array
    {
        return [
            'label' => $prefix.(self::LABELS[$field] ?? Str::headline($field)),
            'before' => self::format($field, $before),
            'after' => self::format($field, $after),
        ];
    }

    private static function decode(mixed $value): mixed
    {
        if (is_string($value)) {
            $decoded = json_decode($value, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                return $decoded;
            }
        }
        return $value;
    }

    private static function format(string $field, mixed $value): string
    {
        if (in_array($field, ['id_sence', 'exec_id_sence'], true)) {
            return Followup::formatCodeList($value) ?: 'Sin valor';
        }
        $value = self::decode($value);
        if ($value === null || $value === '' || $value === []) {
            return 'Sin valor';
        }
        if (in_array($field, ['active', 'has_execution_data'], true)) {
            return $value ? 'Sí' : 'No';
        }
        if (is_array($value)) {
            return implode(', ', array_map(fn ($item) => self::format('', $item), $value));
        }
        if (in_array($field, ['doc_participant', 'doc_oc'], true)) {
            return basename((string) $value);
        }
        if (in_array($field, ['f_star', 'f_end', 'exec_f_star', 'exec_f_end'], true)) {
            try {
                return Carbon::parse($value)->format('d/m/Y');
            } catch (\Throwable) {
                return (string) $value;
            }
        }
        $model = match ($field) {
            'event_id' => \App\Models\Event::class,
            'ejecutivo_id' => \App\Models\User::class,
            'customer_id' => \App\Models\Customer::class,
            'task_id' => \App\Models\Task::class,
            'cotizacion_id' => \App\Models\Cotizacion::class,
            default => null,
        };
        return $model ? (string) ($model::find($value)?->name ?? $value) : (string) $value;
    }
}
