<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class FollowupsBulkExport implements FromCollection, WithHeadings, WithMapping
{
    protected $records;

    public function __construct($records)
    {
        $this->records = $records;
    }

    public function collection()
    {
        return collect($this->records);
    }

    public function map($followup): array
    {
        return [
            $followup->id,
            $followup->name,
            $followup->referent,
            $followup->event->name ?? '',
            $followup->name_course,
            $followup->customer->name ?? '',
            $followup->author,
            $followup->created_at?->format('d/m/Y H:i'),
        ];
    }

    public function headings(): array
    {
        return [
            'ID',
            'SYC',
            'Referente',
            'Estado',
            'Curso',
            'Cliente',
            'Creado por',
            'Fecha de Creación',
        ];
    }
}
