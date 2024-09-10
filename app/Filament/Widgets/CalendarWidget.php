<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\CalendarResource;
use App\Models\Calendar;
use Filament\Widgets\Widget;
use Illuminate\Database\Eloquent\Model;
use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\DateTimePicker;


class CalendarWidget extends FullCalendarWidget
{
    protected static ?int $sort = 8;
    
    public Model | string | null $model = Calendar::class;
 
    public function config(): array
    {
        return [
            'height' => 60,
            'firstDay' => 1,
            'headerToolbar' => [
                'left' => 'dayGridWeek,dayGridDay',
                'center' => 'title',
                'right' => 'prev,next today',
            ],
        ];
    }

    public function getFormSchema(): array
    {
        return [
            TextInput::make('title'),
            TextInput::make('body'),
            Grid::make()
                ->schema([
                    DateTimePicker::make('start'),
 
                    DateTimePicker::make('end'),
                ]),
        ];
    }

    public function fetchEvents(array $fetchInfo): array
    {
        return Calendar::query()
            ->where('date_star', '>=', $fetchInfo['start'])
            ->where('date_end', '<=', $fetchInfo['end'])
            ->get()
            ->map(
                fn (Calendar $event) => [
                    'id'=>$event->id,
                    'body'=>$event->comment,
                    'title'=>$event->title,
                    'start' =>$event->date_star,
                    'end' => $event->date_end,
                    'url' =>CalendarResource::getUrl(name: 'view', parameters: ['record' => $event]),
                    'shouldOpenUrlInNewTab' => true,
                    
                    ]
            )
            ->toArray();
    }

    
}
