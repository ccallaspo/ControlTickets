<?php

namespace App\Filament\Widgets;

use App\Models\Event;
use App\Models\Record;
use Filament\Widgets\Widget;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;

class EventOverview extends Widget
{
    protected static ?int $sort = 4;
    
    protected static string $view = 'filament.widgets.event-overview';

    public $terminado_count_array;

    public function mount()
    {
            
        $terminado_count = DB::table('followups')
            ->join('events', 'followups.event_id', '=', 'events.id')
            ->select('events.name', DB::raw('COUNT(followups.id) as count'))
            ->groupBy('events.name')
            ->get();

        // Convierte el resultado a un array
        $this->terminado_count_array = $terminado_count->toArray();

        //dd($terminado_count_array);
    }

}
