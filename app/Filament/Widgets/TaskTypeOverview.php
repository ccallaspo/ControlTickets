<?php

namespace App\Filament\Widgets;

use App\Models\Record;
use App\Models\Task;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class TaskTypeOverview extends BaseWidget
{
    protected static ?int $sort = 1;
    

    protected function getStats(): array
    {
        $count_all = DB::table('followups')->count();

        $enviado_count = DB::table('followups')
        ->join('tasks', 'followups.task_id', '=', 'tasks.id')
        ->join('events', 'followups.event_id', '=', 'events.id')
        ->select(DB::raw('COUNT(*) as count'))
        ->where('events.name', 'Cotización enviada')
        ->first();

        $count_send = $enviado_count->count;
        
        $terminado_count = DB::table('followups')
        ->join('tasks', 'followups.task_id', '=', 'tasks.id')
        ->join('events', 'followups.event_id', '=', 'events.id')
        ->select(DB::raw('COUNT(*) as count'))
        ->where('events.name', 'Cotización aprobada')
        ->first();
    
        $count_end = $terminado_count->count;

        $facturado_count = DB::table('followups')
        ->join('tasks', 'followups.task_id', '=', 'tasks.id')
        ->join('events', 'followups.event_id', '=', 'events.id')
        ->select(DB::raw('COUNT(*) as count'))
        ->where('events.name', 'Cotización aprobada')
        ->first();
    
        $facturado_end = $facturado_count->count;

        //dd($count);

        return [
            //$allRecord = Record::all(),
            stat::make('', $count_all)
            ->description('Total Cotizaciones')
            ->descriptionIcon('heroicon-o-newspaper', IconPosition::Before)
            ->color('primary')
            ,

            stat::make('', $count_send)
            ->description('Cotizaciones Enviadas')
            ->descriptionIcon('heroicon-o-rocket-launch', IconPosition::Before)
            ->color('danger'),

            stat::make('', $count_end)
            ->description('Cotizaciones Aprobadas')
            ->descriptionIcon('heroicon-o-check-badge', IconPosition::Before)
            ->color('success'),         
            
            stat::make('', $facturado_end)
            ->description('Cotizaciones Facturadas')
            ->descriptionIcon('heroicon-o-currency-dollar', IconPosition::Before)
            ->color('warning'),        

        ];
    }
    
}
