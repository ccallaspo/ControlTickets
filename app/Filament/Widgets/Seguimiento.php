<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\FollowupResource;
use App\Models\Followup;
use Carbon\Carbon;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\HtmlString;

class Seguimiento extends BaseWidget
{
    protected static ?int $sort = 3;

    protected static ?string $heading = 'Últimos Tickets';

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                FollowupResource::getEloquentQuery()
                    ->with(['ejecutivo', 'event', 'cotizacion.customer', 'customer'])
            )
            ->defaultPaginationPageOption(10)
            ->defaultSort('created_at', 'desc')
            ->striped()
            ->columns([
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

                        if (!$name) {
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
                    ->size('sm')
                    ->searchable()
                    ->sortable()
                    ->icon(
                        fn (Followup $record): ?string =>
                        !empty($record->event->icono) ? $record->event->icono : null
                    )
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'Cotización Enviada' => 'Cotización Enviada',
                        'Coordinar Curso' => 'Coordinar Curso',
                        'Matricular Curso' => 'Matricular Curso',
                        'Curso en Proceso' => 'Curso en Proceso',
                        'Curso Finalizado' => 'Curso Finalizado',
                        'Generar DJ' => 'Generar DJ',
                        'Por Facturar' => 'Por Facturar',
                        default => $state,
                    })
                    ->color('white')
                    ->extraAttributes(function (Followup $record) {
                        $color = $record->event?->description;
                        if (!$color) {
                            return [];
                        }

                        return [
                            'style' => "
                                background-color: {$color} !important;
                                border-color: {$color} !important;
                                color: #ffffff !important;
                                padding: 0.375rem 0.75rem !important;
                                border-radius: 0.375rem !important;
                                font-weight: 500 !important;
                                display: inline-flex !important;
                                align-items: center !important;
                                justify-content: center !important;
                                gap: 0.375rem !important;
                                white-space: nowrap !important;
                                text-align: center !important;
                                min-width: fit-content !important;
                            ",
                            'class' => 'custom-badge-colored',
                        ];
                    }),

                Tables\Columns\TextColumn::make('schedule')
                    ->label('Programación')
                    ->html()
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
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('event.name')
                    ->label('Estados')
                    ->indicator('Estado')
                    ->relationship('event', 'name'),
            ])
            ->actions([
                Tables\Actions\Action::make('Modificar')
                    ->label('Editar')
                    ->icon('heroicon-s-pencil-square')
                    ->tooltip('Editar ticket')
                    ->url(fn (Followup $record): string => url('admin/followups/' . $record->id . '/edit')),
            ]);
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

        if (!$start && !$end && !$hours) {
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
