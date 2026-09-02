<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

/**
 * @property array $changes Array adjuntado por el Observer con los campos modificados.
 */

class Followup extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'author',
        'referent',
        'event_id',
        'ejecutivo_id',
        'cotizacion_id',
        'task_id',
        'customer_id',
        'active',
        'doc_participant',
        'cod_sence_course',
        'name_course',
        'id_sence',
        'modalily',
        'week',
        'h_star',
        'h_end',
        'f_star',
        'f_end',
        'n_hours',
        'doc_oc',
        'financiamientos',
//add execution data
        'has_execution_data',
        'exec_cod_sence_course',
        'exec_name_course',
        'exec_id_sence',
        'exec_modalily',
        'exec_f_star',
        'exec_f_end',
        'exec_n_hours',
    ];

    protected $casts = [
        'week' => 'json',
        'financiamientos' => 'array',
    ];

    protected function idSence(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value): array => self::normalizeCodeList($value),
            set: fn (mixed $value): ?string => self::encodeCodeList($value),
        );
    }

    protected function execIdSence(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value): array => self::normalizeCodeList($value),
            set: fn (mixed $value): ?string => self::encodeCodeList($value),
        );
    }

    public function note(): HasMany
    {
        return $this->HasMany(Note::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    public function Task(): BelongsTo
    {
        return $this->BelongsTo(Task::class);
    }

    public function Event(): BelongsTo
    {
        return $this->BelongsTo(Event::class);
    }

    public function customer(): BelongsTo
    {
        return $this->BelongsTo(Customer::class);
    }

    public function cotizacion(): BelongsTo
    {
        return $this->belongsTo(Cotizacion::class);
    }

    public function ejecutivo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'ejecutivo_id');
    }

    public function getFormattedIdSenceAttribute(): string
    {
        return self::formatCodeList($this->id_sence);
    }

    public function getFormattedExecIdSenceAttribute(): string
    {
        return self::formatCodeList($this->exec_id_sence);
    }

    public static function formatCodeList(mixed $value): string
    {
        return implode(', ', self::normalizeCodeList($value));
    }

    public static function normalizeCodeList(mixed $value): array
    {
        if ($value === null || $value === '' || $value === []) {
            return [];
        }

        if (is_int($value) || is_float($value)) {
            return [(string) $value];
        }

        if (is_string($value)) {
            $decoded = json_decode($value, true);

            if (json_last_error() === JSON_ERROR_NONE) {
                $value = is_array($decoded) ? $decoded : [(string) $decoded];
            } else {
                return array_values(array_filter(array_map('trim', explode(',', $value)), fn ($item) => $item !== ''));
            }
        }

        if (! is_array($value)) {
            return [(string) $value];
        }

        return collect($value)
            ->map(function ($item) {
                if (is_array($item)) {
                    $item = $item['id'] ?? $item['code'] ?? reset($item) ?: null;
                }

                if ($item === null || $item === '') {
                    return null;
                }

                return trim((string) $item);
            })
            ->filter(fn ($item) => $item !== null && $item !== '')
            ->values()
            ->all();
    }

    public static function encodeCodeList(mixed $value): ?string
    {
        $list = self::normalizeCodeList($value);

        return $list === [] ? null : json_encode(array_values($list));
    }

    protected static function booted(): void
    {
        static::saving(function (Followup $followup): void {
            $followup->syncPrimaryFinanciamiento();
        });
    }

    public function syncPrimaryFinanciamiento(): void
    {
        $items = $this->financiamientos;

        if (! is_array($items) || $items === []) {
            return;
        }

        $first = array_values($items)[0] ?? [];

        $this->cod_sence_course = $first['cod_sence_course'] ?? null;
        $this->name_course = $first['name_course'] ?? null;
        $this->id_sence = $first['id_sence'] ?? null;
        $this->modalily = $first['modalily'] ?? null;
        $this->f_star = $first['f_star'] ?? null;
        $this->f_end = $first['f_end'] ?? null;
        $this->n_hours = $first['n_hours'] ?? null;
    }

    public function primaryFinanciamiento(): array
    {
        $items = $this->financiamientos;

        if (is_array($items) && $items !== []) {
            return array_values($items)[0];
        }

        return [
            'cod_sence_course' => $this->cod_sence_course,
            'name_course' => $this->name_course,
            'id_sence' => $this->id_sence,
            'modalily' => $this->modalily,
            'f_star' => $this->f_star,
            'f_end' => $this->f_end,
            'n_hours' => $this->n_hours,
        ];
    }

    public function allFinanciamientos(): array
    {
        $items = $this->financiamientos;

        if (is_array($items) && $items !== []) {
            return array_values(array_filter($items, 'is_array'));
        }

        return [$this->primaryFinanciamiento()];
    }

    public function coursesStartingBetween(string $startDate, string $endDate): array
    {
        return $this->coursesWithDateBetween($startDate, $endDate, 'f_star', 'exec_f_star');
    }

    public function coursesEndingBetween(string $startDate, string $endDate): array
    {
        return $this->coursesWithDateBetween($startDate, $endDate, 'f_end', 'exec_f_end');
    }

    public function coursesWithDateBetween(string $startDate, string $endDate, string $dateField, string $execDateField): array
    {
        $inRange = function (mixed $date) use ($startDate, $endDate): bool {
            if (blank($date)) {
                return false;
            }

            try {
                $day = Carbon::parse($date)->toDateString();
            } catch (\Throwable) {
                return false;
            }

            return $day >= $startDate && $day <= $endDate;
        };

        $courses = [];

        foreach ($this->allFinanciamientos() as $item) {
            if (! $inRange($item[$dateField] ?? null)) {
                continue;
            }

            $courses[] = [
                'source' => 'Financiamiento',
                'name_course' => $item['name_course'] ?? null,
                'modalily' => $item['modalily'] ?? null,
                'f_star' => $item['f_star'] ?? null,
                'f_end' => $item['f_end'] ?? null,
            ];
        }

        if ($this->has_execution_data && $inRange($this->{$execDateField})) {
            $courses[] = [
                'source' => 'Ejecución',
                'name_course' => $this->exec_name_course,
                'modalily' => $this->exec_modalily,
                'f_star' => $this->exec_f_star,
                'f_end' => $this->exec_f_end,
            ];
        }

        return $courses;
    }

    public function coursesEndedBefore(string $beforeDate): array
    {
        $isBefore = function (mixed $date) use ($beforeDate): bool {
            if (blank($date)) {
                return false;
            }

            try {
                return Carbon::parse($date)->toDateString() < $beforeDate;
            } catch (\Throwable) {
                return false;
            }
        };

        $courses = [];

        foreach ($this->allFinanciamientos() as $item) {
            if (! $isBefore($item['f_end'] ?? null)) {
                continue;
            }

            $courses[] = [
                'source' => 'Financiamiento',
                'name_course' => $item['name_course'] ?? null,
                'modalily' => $item['modalily'] ?? null,
                'f_star' => $item['f_star'] ?? null,
                'f_end' => $item['f_end'] ?? null,
            ];
        }

        if ($this->has_execution_data && $isBefore($this->exec_f_end)) {
            $courses[] = [
                'source' => 'Ejecución',
                'name_course' => $this->exec_name_course,
                'modalily' => $this->exec_modalily,
                'f_star' => $this->exec_f_star,
                'f_end' => $this->exec_f_end,
            ];
        }

        return $courses;
    }

    public function displayCourses(): array
    {
        $courses = [];

        foreach ($this->allFinanciamientos() as $item) {
            $courses[] = [
                'source' => 'Financiamiento',
                'name_course' => $item['name_course'] ?? null,
                'modalily' => $item['modalily'] ?? null,
                'f_star' => $item['f_star'] ?? null,
                'f_end' => $item['f_end'] ?? null,
            ];
        }

        if ($this->has_execution_data) {
            $courses[] = [
                'source' => 'Ejecución',
                'name_course' => $this->exec_name_course,
                'modalily' => $this->exec_modalily,
                'f_star' => $this->exec_f_star,
                'f_end' => $this->exec_f_end,
            ];
        }

        return $courses;
    }

    public function eventStatusBadgeHtml(): string
    {
        $label = $this->event?->name ?: '—';
        $color = $this->event?->description;

        if (! is_string($color) || ! preg_match('/^#(?:[0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/', $color)) {
            $color = '#6b7280';
        }

        return '<span style="display:inline-flex;align-items:center;max-width:100%;background:'.$color.';color:#fff;font-size:11px;font-weight:600;line-height:1.2;padding:2px 8px;border-radius:9999px;white-space:nowrap;">'
            .e($label)
            .'</span>';
    }

    public function scopeStartingBetween(Builder $query, string $startDate, string $endDate): Builder
    {
        return $query->withCourseDateBetween($startDate, $endDate, 'f_star', 'exec_f_star');
    }

    public function scopeEndingBetween(Builder $query, string $startDate, string $endDate): Builder
    {
        return $query->withCourseDateBetween($startDate, $endDate, 'f_end', 'exec_f_end');
    }

    public function scopeWithCourseDateBetween(
        Builder $query,
        string $startDate,
        string $endDate,
        string $column,
        string $execColumn
    ): Builder {
        return $query->where(function (Builder $q) use ($startDate, $endDate, $column, $execColumn) {
            $q->whereRaw("DATE({$column}) BETWEEN ? AND ?", [$startDate, $endDate])
                ->orWhere(function (Builder $q) use ($startDate, $endDate, $execColumn) {
                    $q->where('has_execution_data', 1)
                        ->whereNotNull($execColumn)
                        ->whereRaw("DATE({$execColumn}) BETWEEN ? AND ?", [$startDate, $endDate]);
                });

            $cursor = Carbon::parse($startDate)->startOfDay();
            $end = Carbon::parse($endDate)->startOfDay();

            $q->orWhere(function (Builder $q) use ($cursor, $end, $column) {
                while ($cursor->lte($end)) {
                    $day = $cursor->toDateString();
                    $q->orWhere('financiamientos', 'like', '%"'.$column.'":"'.$day.'%')
                        ->orWhere('financiamientos', 'like', '%"'.$column.'": "'.$day.'%');
                    $cursor->addDay();
                }
            });
        });
    }

    public function scopeDjOverdue(Builder $query, string $beforeDate): Builder
    {
        return $query
            ->whereHas('event', function (Builder $q) {
                $q->whereIn('name', ['Curso Finalizado', 'Generar DJ']);
            })
            ->where(function (Builder $q) use ($beforeDate) {
                $q->whereRaw('DATE(f_end) < ?', [$beforeDate])
                    ->orWhere(function (Builder $q) use ($beforeDate) {
                        $q->where('has_execution_data', 1)
                            ->whereNotNull('exec_f_end')
                            ->whereRaw('DATE(exec_f_end) < ?', [$beforeDate]);
                    });
            });
    }

    public function scopeStalled(Builder $query, int $days = 7): Builder
    {
        return $query
            ->whereHas('event', function (Builder $q) {
                $q->whereIn('name', [
                    'Cotización Aprobada',
                    'Coordinar Curso',
                    'Matricular Curso',
                    'Curso en Proceso',
                    'Curso Finalizado',
                    'Generar DJ',
                    'Por Facturar',
                ]);
            })
            ->where('updated_at', '<=', now()->subDays($days));
    }

    public function scopeRestrictedForSupportUser($query)
    {
        if (Auth::check() && Auth::user()->email === 'soporte@otecproyecta.cl') {
            return $query->whereHas('event', function ($q) {
                $q->whereIn('name', [
                    'Curso agendado',
                    'Curso matriculado',
                    'Curso finalizado'
                ]);
            });
        }

        return $query;
    }
}
