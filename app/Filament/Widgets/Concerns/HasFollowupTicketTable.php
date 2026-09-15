<?php

namespace App\Filament\Widgets\Concerns;

use App\Models\Followup;
use Carbon\Carbon;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\HtmlString;

trait HasFollowupTicketTable
{
    protected function followupTicketColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('referent')
                ->label('Cotización')
                ->size('sm')
                ->sortable()
                ->searchable()
                ->placeholder('—'),

            Tables\Columns\TextColumn::make('ejecutivo.name')
                ->label('Coordinadora')
                ->size('sm')
                ->sortable()
                ->alignCenter()
                ->placeholder('—')
                ->getStateUsing(function (Followup $record): ?string {
                    $name = $record->ejecutivo?->name;

                    if (! $name) {
                        return null;
                    }

                    return strtok($name, ' ') ?: $name;
                })
                ->searchable(query: function (Builder $query, string $search): Builder {
                    return $query->whereHas('ejecutivo', function (Builder $query) use ($search) {
                        $query->where('name', 'like', "%{$search}%");
                    });
                }),

            Tables\Columns\TextColumn::make('event.name')
                ->label('Estado')
                ->searchable()
                ->sortable()
                ->getStateUsing(fn (Followup $record): HtmlString => new HtmlString(
                    $record->eventStatusBadgeHtml()
                )),

            Tables\Columns\TextColumn::make('schedule')
                ->label('Programación')
                ->getStateUsing(fn (Followup $record): HtmlString => new HtmlString(
                    self::formatScheduleCell($record)
                ))
                ->sortable(query: function (Builder $query, string $direction): Builder {
                    return $query->orderByRaw(
                        "COALESCE(CASE WHEN has_execution_data = 1 THEN exec_f_star END, f_star) {$direction}"
                    );
                }),

            Tables\Columns\TextColumn::make('name_course')
                ->label('Curso')
                ->size('sm')
                ->sortable()
                ->searchable()
                ->limit(40)
                ->tooltip(fn (Followup $record): ?string => $record->name_course)
                ->wrap(),

            Tables\Columns\TextColumn::make('cliente')
                ->label('Cliente')
                ->size('sm')
                ->wrap()
                ->placeholder('—')
                ->getStateUsing(
                    fn (Followup $record): ?string =>
                    $record->cotizacion?->customer?->name
                        ?? $record->customer?->name
                )
                ->searchable(query: function (Builder $query, string $search): Builder {
                    return $query->where(function (Builder $query) use ($search) {
                        $query->whereHas('cotizacion.customer', function (Builder $query) use ($search) {
                            $query->where('name', 'like', "%{$search}%");
                        })->orWhereHas('customer', function (Builder $query) use ($search) {
                            $query->where('name', 'like', "%{$search}%");
                        });
                    });
                }),

            Tables\Columns\TextColumn::make('name')
                ->label('SYC')
                ->toggleable(isToggledHiddenByDefault: true)
                ->sortable()
                ->searchable(),

            Tables\Columns\TextColumn::make('author')
                ->toggleable(isToggledHiddenByDefault: true)
                ->label('Creado por')
                ->sortable()
                ->searchable(),

            Tables\Columns\TextColumn::make('created_at')
                ->label('F. Creac.')
                ->dateTime('d/m/Y H:i')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),

            Tables\Columns\TextColumn::make('updated_at')
                ->label('F. Ult. Act.')
                ->dateTime('d/m/Y H:i')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
        ];
    }

    protected function followupTicketFilters(): array
    {
        return [
            Tables\Filters\SelectFilter::make('event.name')
                ->label('Estados')
                ->indicator('Estado')
                ->relationship('event', 'name'),
            Tables\Filters\SelectFilter::make('ejecutivo_id')
                ->label('Coordinadora')
                ->indicator('Coordinadora')
                ->relationship('ejecutivo', 'name')
                ->searchable()
                ->preload(),
        ];
    }

    protected function followupTicketActions(): array
    {
        return [
            Tables\Actions\Action::make('Modificar')
                ->iconButton()
                ->icon('heroicon-s-pencil-square')
                ->tooltip('Editar ticket')
                ->url(fn (Followup $record): string => url('admin/followups/' . $record->id . '/edit')),
        ];
    }

    protected function followupUpcomingCoursesColumns(
        string $startDate,
        string $endDate,
        callable $coursesResolver,
        bool $showLastUpdate = false
    ): array {
        $columns = [];

        foreach ($this->followupTicketColumns() as $column) {
            $name = $column->getName();

            if ($name === 'event.name' && $showLastUpdate) {
                $columns[] = $column;
                $columns[] = Tables\Columns\TextColumn::make('updated_at')
                    ->label('Última act.')
                    ->since()
                    ->sortable()
                    ->tooltip(fn (Followup $record): ?string => $record->updated_at?->format('d/m/Y H:i'));

                continue;
            }

            if ($name === 'schedule') {
                $columns[] = Tables\Columns\TextColumn::make('upcoming_courses')
                    ->label('Cursos')
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->where(function (Builder $query) use ($search) {
                            $query->where('name_course', 'like', "%{$search}%")
                                ->orWhere('exec_name_course', 'like', "%{$search}%")
                                ->orWhere('financiamientos', 'like', "%{$search}%");
                        });
                    })
                    ->getStateUsing(fn (Followup $record): HtmlString => new HtmlString(
                        self::formatUpcomingCoursesGroup($record, $coursesResolver)
                    ));

                continue;
            }

            if ($name === 'name_course') {
                continue;
            }

            $columns[] = $column;
        }

        return $columns;
    }

    private static function formatUpcomingCoursesGroup(Followup $record, callable $coursesResolver): string
    {
        $courses = $coursesResolver($record);

        if ($courses === []) {
            $courses = [[
                'source' => $record->has_execution_data ? 'Ejecución' : 'Financiamiento',
                'name_course' => $record->has_execution_data ? $record->exec_name_course : $record->name_course,
                'modalily' => $record->has_execution_data ? $record->exec_modalily : $record->modalily,
                'f_star' => $record->has_execution_data ? $record->exec_f_star : $record->f_star,
                'f_end' => $record->has_execution_data ? $record->exec_f_end : $record->f_end,
            ]];
        }

        $cards = [];

        foreach ($courses as $course) {
            $name = e($course['name_course'] ?: 'Sin nombre');
            $start = self::formatScheduleDate($course['f_star']);
            $end = self::formatScheduleDate($course['f_end']);

            $range = match (true) {
                $start && $end => "{$start} → {$end}",
                (bool) $start => "Inicio {$start}",
                (bool) $end => "Término {$end}",
                default => 'Sin fecha',
            };

            $sourceBadge = $course['source'] === 'Ejecución'
                ? '<span class="inline-flex items-center rounded-md bg-sky-50 px-2 py-0.5 text-xs font-medium text-sky-700 ring-1 ring-inset ring-sky-200">Ejecución</span>'
                : '<span class="inline-flex items-center rounded-md bg-amber-50 px-2 py-0.5 text-xs font-medium text-amber-700 ring-1 ring-inset ring-amber-200">Financiamiento</span>';

            $modality = trim((string) ($course['modalily'] ?? ''));
            $modalityBadge = $modality !== ''
                ? '<span class="inline-flex items-center rounded-md bg-white px-2 py-0.5 text-xs font-medium text-gray-700 ring-1 ring-inset ring-gray-200">'
                    . e($modality)
                    . '</span>'
                : '';

            $meta = trim(implode(' ', array_filter([$sourceBadge, $modalityBadge])));

            $cards[] = <<<HTML
                <div class="rounded-lg border border-gray-200 bg-white px-3 py-2 shadow-sm">
                    <div class="text-sm font-semibold leading-snug text-gray-950">{$name}</div>
                    <div class="mt-1 text-xs text-gray-500">{$range}</div>
                    <div class="mt-1.5 flex flex-wrap items-center gap-1.5">{$meta}</div>
                </div>
            HTML;
        }

        return '<div class="flex min-w-[16rem] max-w-xl flex-col gap-2 py-1">'.implode('', $cards).'</div>';
    }

    private static function formatScheduleCell(Followup $record): string
    {
        $useExecution = (bool) $record->has_execution_data;

        $start = self::formatScheduleDate(
            $useExecution ? $record->exec_f_star : $record->f_star
        );
        $end = self::formatScheduleDate(
            $useExecution ? $record->exec_f_end : $record->f_end
        );
        $hours = self::formatScheduleHours(
            $useExecution ? $record->exec_n_hours : $record->n_hours
        );

        if (! $start && ! $end && ! $hours) {
            return '<span class="text-xs text-gray-400 italic">Sin programar</span>';
        }

        $range = match (true) {
            $start && $end => "{$start} <span class=\"text-gray-400\">→</span> {$end}",
            (bool) $start => "Inicio {$start}",
            (bool) $end => "Término {$end}",
            default => '—',
        };

        $hoursBadge = $hours
            ? '<span class="inline-flex items-center rounded-md bg-gray-50 px-2 py-0.5 text-xs font-medium text-gray-700 ring-1 ring-inset ring-gray-200">'
                . e($hours)
                . '</span>'
            : '';

        $executionBadge = $useExecution
            ? '<span class="inline-flex items-center rounded-md bg-sky-50 px-2 py-0.5 text-xs font-medium text-sky-700 ring-1 ring-inset ring-sky-200">Ejecución</span>'
            : '';

        $meta = trim(implode(' ', array_filter([$hoursBadge, $executionBadge])));

        return <<<HTML
            <div class="flex min-w-[10rem] flex-col gap-1 py-0.5">
                <span class="whitespace-nowrap text-sm font-medium text-gray-950">{$range}</span>
                <div class="flex flex-wrap items-center gap-1.5">{$meta}</div>
            </div>
        HTML;
    }

    private static function formatScheduleDate(mixed $value): ?string
    {
        if (blank($value)) {
            return null;
        }

        try {
            return Carbon::parse($value)->format('d/m/Y');
        } catch (\Throwable) {
            return null;
        }
    }

    private static function formatScheduleHours(mixed $value): ?string
    {
        if (blank($value)) {
            return null;
        }

        $hours = trim((string) $value);

        if ($hours === '') {
            return null;
        }

        if (preg_match('/horas?/iu', $hours)) {
            return $hours;
        }

        return "{$hours} hrs";
    }
}
