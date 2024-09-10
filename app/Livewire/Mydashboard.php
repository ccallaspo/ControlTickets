<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Record;
use App\Models\Task;
use App\Models\Event;
use Illuminate\Support\Facades\DB;
use Filament\Widgets\StatsOverviewWidget\Stat;

class Mydashboard extends Component
{
    public $records;
    public $tasks;
    public $events;
    public $records_canceled;
    public $records_description;

    public $records_description_all;
    


   
    public function mount()
    {
        $this->records = Record::all()->count();  
        $this->records_canceled = Record::where('active', 1)->count(); 
        $this->tasks = Task::all(); 
        $this->events = Event::all(); 
//
        $records_description = DB::table('records')
        ->join('tasks', 'records.task_id', '=', 'tasks.id')
        ->join('events', 'records.status', '=', 'events.id')
        ->select('records.*', 'tasks.name as task_name', 'events.name as event_name')
        ->get();

        $this->records_description = $records_description;
//

    }
        protected function getStats(): array
        {
            return [
                //$allRecord = Record::all(),
                stat::make('Cotización SENCE', Record::query()->where('task_id', 1)->count()),
                stat::make('Cotización Sin SENCE', Record::query()->where('task_id', 2)->count()),
                stat::make('Cotización', Record::query()->where('task_id', 3)->count())
            ];
        }

        public function render()
        {
            $stats = $this->getStats();
           // dd($stats);
            return view('livewire.mydashboard', [
                'stats' => $stats,
            ]);
      //      return view('livewire.mydashboard');
        }
}
