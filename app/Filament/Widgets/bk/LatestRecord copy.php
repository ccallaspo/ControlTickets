<?php

namespace App\Filament\Widgets;

use App\Models\Event;
use App\Models\Record;
use App\Models\Task;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\DB;
use Filament\Tables\Actions\EditAction;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Forms\Components\Select;

class LatestRecord extends BaseWidget
{
    protected static ?int $sort = 7;
    protected static bool $shouldRegisterNavigation = false;

    protected int | string | array $columnSpan = 'full';
 
    protected function getTableQuery(): Builder
    {
        return Record::query();
    }

    

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('referent')
                    ->label('#Cotización')
                    ->searchable()
                    ->sortable(),
            TextColumn::make('name')
                    ->label('Cotización')
                    ->searchable()
                    ->sortable(),
            TextColumn::make('task.name')
                    ->label('Tipo')
                    ->searchable()
                    ->sortable(), 
            TextColumn::make('event.name')
                    ->label('Estado')
                    ->searchable()
                    ->sortable()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'Cotización Aprobada' => 'Cotización Aprobada',
                        'Cotización enviada' => 'Cotización enviada',
                        'Terminado' => 'Terminado',
                        'rejected' => 'Rejected',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'draft' => 'gray',
                        'Cotización enviada' => 'warning',
                        'Terminado' => 'success',
                        'Cotización Aprobada' => 'danger',
                        default => 'default-color',
                    }),
            // IconColumn::make('event.name')
            //         ->boolean(),
            TextColumn::make('created_at')
                ->label('Creado')
                ->dateTime(),
        ];
    }

    protected function getTableActions(): array
    {
    $data = Record::all('id','event_id');
    //dd($data);

    return [
        EditAction::make()
        // ->label('Cambiar')
        ->form([
            

                Section::make('Estado de Cotización')
                ->description('Seguimiento y control')
                ->schema([
                    Forms\Components\TextInput::make('name')
                ->label('Nombre')
                ->disabled()
                ->default('name'),
            Select::make('event_id')
                ->label('Estado')
                ->options(Event::query()->pluck('name', 'id'))
                ->required()
                ->default('event_id'),
                ])
                
        ])
        ->action(function (array $data, Record $record): void {
            //dd($record);
            $record->event_id=($data['event_id']);
            $record->save();
        }),
    ];
    }
    
}
