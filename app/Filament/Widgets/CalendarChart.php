<?php

namespace App\Filament\Widgets;

use App\Models\Calendar;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class CalendarChart extends ChartWidget
{
    protected static ?string $heading = 'Grafica de agendas';
    protected static ?int $sort = 3;

    protected function getData(): array
    {
        $completedTasks = Calendar::where('status', 'completado')->count();
        $pendingTasks = Calendar::where('status', 'pendiente')->count();
        $inProgressTasks = Calendar::where('status', 'en proceso')->count();

        return [
            'datasets' => [
                [
                    'label' => 'Total',
                    'data' => [ $pendingTasks,$inProgressTasks,$completedTasks],
                    'backgroundColor' => [                                              
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                    ],
                    'borderColor' => [                             
                        'rgba(255, 99, 132, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                    ],
                    'borderWidth' => 1,
                ],
            ],
            'labels' => ['Pendiente', 'En Proceso', 'Completado'],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
