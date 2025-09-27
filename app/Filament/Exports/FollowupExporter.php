<?php

namespace App\Filament\Exports;

use App\Models\Followup;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Carbon\Carbon;

class FollowupExporter extends Exporter
{
    protected static ?string $model = Followup::class;

    public static function getExportName(): string
    {
        return 'seguimientos-' . now()->format('Y-m-d_H-i-s');
    }

    public static function getColumns(): array
    {
        return [
            // Campos directos de la tabla 'followups'
            ExportColumn::make('id')->label('ID Ticket'),
            ExportColumn::make('active')->label('Activo'), // Nuevo campo
            ExportColumn::make('name')->label('SYC'),
            ExportColumn::make('description')->label('Descripción')->formatStateUsing(fn ($state) => $state ?? ''), // Nuevo campo
            ExportColumn::make('author')->label('Autor'),
            ExportColumn::make('referent')->label('Cotización')->formatStateUsing(fn ($state) => $state ?? ''),
            ExportColumn::make('n_hours')->label('N° Horas')->formatStateUsing(fn ($state) => $state ?? ''),
            ExportColumn::make('doc_oc')->label('Doc. OC')->formatStateUsing(fn ($state) => $state ?? ''), // Nuevo campo
            ExportColumn::make('cod_sence_course')->label('Código Sence Curso')->formatStateUsing(fn ($state) => $state ?? ''),
            ExportColumn::make('name_course')->label('Nombre Curso')->formatStateUsing(fn ($state) => $state ?? ''),
            ExportColumn::make('id_sence')->label('Código ID')->formatStateUsing(fn ($state) => $state ?? ''),
            ExportColumn::make('modalily')->label('Modalidad')->formatStateUsing(fn ($state) => $state ?? ''),
            ExportColumn::make('doc_participant')->label('Doc. Participante')->formatStateUsing(fn ($state) => $state ?? ''), // Nuevo campo
            ExportColumn::make('h_star')->label('Hora Inicio')->formatStateUsing(fn ($state) => $state ?? ''), // Nuevo campo
            ExportColumn::make('h_end')->label('Hora Término')->formatStateUsing(fn ($state) => $state ?? ''), // Nuevo campo
            ExportColumn::make('f_star')->label('Fecha Inicio')->formatStateUsing(fn ($state) => $state ? Carbon::parse($state)->format('d/m/Y') : ''),
            ExportColumn::make('f_end')->label('Fecha Término')->formatStateUsing(fn ($state) => $state ? Carbon::parse($state)->format('d/m/Y') : ''),

            // Relaciones BelongsTo
            ExportColumn::make('task.name')->label('Tipo de Proceso')->formatStateUsing(fn ($state) => $state ?? ''),
            ExportColumn::make('event.name')->label('Estado')->formatStateUsing(fn ($state) => $state ?? ''),
            ExportColumn::make('customer.rut')->label('RUT Cliente')->formatStateUsing(fn ($state) => $state ?? ''),
            ExportColumn::make('customer.name')->label('Nombre Cliente')->formatStateUsing(fn ($state) => $state ?? ''),
            ExportColumn::make('customer.represent')->label('Contacto Cliente')->formatStateUsing(fn ($state) => $state ?? ''),
            ExportColumn::make('customer.phone')->label('Teléfono Cliente')->formatStateUsing(fn ($state) => $state ?? ''),
            ExportColumn::make('customer.email')->label('Email Cliente')->formatStateUsing(fn ($state) => $state ?? ''),

            // Campo JSON 'week' (adaptado para mostrar su contenido)
            ExportColumn::make('week')->label('Horario Semanal')
                ->formatStateUsing(function ($state) {
                    if (empty($state)) {
                        return '';
                    }
                    // Assuming 'week' is stored as a JSON array of objects
                    $schedules = is_string($state) ? json_decode($state, true) : $state;
                    if (!is_array($schedules)) {
                        return '';
                    }
                    return collect($schedules)->map(function ($item) {
                        $day = $item['day'] ?? 'N/A';
                        $start = $item['start_time'] ?? 'N/A';
                        $end = $item['end_time'] ?? 'N/A';
                        return "Día: {$day}, Inicio: {$start}, Fin: {$end}";
                    })->implode("\n");
                }),

            // Relaciones HasMany (documents, notes)
            ExportColumn::make('documents')->label('Documentos')
                ->formatStateUsing(function ($state) {
                    if (!$state || $state->isEmpty()) {
                        return '';
                    }
                    return $state->map(function ($doc) {
                        $typeName = $doc->typedocument->name ?? 'N/A';
                        $fileName = $doc->original_filename ?? 'Sin nombre';
                        return "{$typeName}: {$fileName}";
                    })->implode("\n");
                }),
            ExportColumn::make('notes')->label('Notas')
                ->formatStateUsing(function ($state) {
                    if (!$state || $state->isEmpty()) {
                        return '';
                    }
                    return $state->map(function ($note) {
                        $date = $note->created_at ? $note->created_at->format('d-m-Y H:i') : 'N/A';
                        $user = $note->user->name ?? 'Sistema';
                        return "[{$date} - {$user}]: {$note->content}";
                    })->implode("\n");
                }),

            // Metadatos
            ExportColumn::make('created_at')->label('Fecha Creación')->formatStateUsing(fn ($state) => $state?->format('d/m/Y H:i')),
            ExportColumn::make('updated_at')->label('Última Actualización')->formatStateUsing(fn ($state) => $state?->format('d/m/Y H:i')),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'La exportación de seguimientos ha sido completada.';

        if ($count = $export->successful_rows_count) {
            $body .= ' ' . number_format($count) . ' ' . str('fila')->plural($count) . ' exportadas.';
        }

        if ($failedRowsCount = $export->failed_rows_count) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('fila')->plural($failedRowsCount) . ' fallidas.';
        }

        return $body;
    }
}   