<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RecordResource\Pages;
use App\Filament\Resources\RecordResource\RelationManagers;
use App\Models\Record;
use App\Models\Event;
use App\Models\Task;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\DB;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class RecordResource extends Resource
{
    protected static ?string $model = Record::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Registros';
    protected static ?string $navigationGroup = 'Operaciones';
    protected static bool $shouldRegisterNavigation = false;

    public static function form(Form $form): Form
    {
        $myuser = auth()->user()->name;

        return $form
            ->schema([
                Forms\Components\Select::make('task_id')
                ->label('Tipo de Proceso')
                ->options(Task::all()->pluck('name', 'id'))
                ->reactive()
                ->afterStateUpdated(fn ($state, callable $set) => $set('event_id', null)),  

                Forms\Components\Select::make('event_id')
                ->label('Estado')
                ->options(function (callable $get) {
                    $taskId = $get('task_id');
                    if ($taskId) {
                        return event::where('task_id', $taskId)
                        ->orderBy('order')
                        ->pluck('name', 'id');
                    }
                    return [];
                })
                ->reactive()
                ->disabled(fn (callable $get) => !$get('task_id')),


                Forms\Components\TextInput::make('name')
                    ->label('Nombre')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('description')
                    ->label('Descripción')
                    ->maxLength(255),

                Forms\Components\TextInput::make('referent')
                    ->label('N° Referencia')
                    ->maxLength(255),                
                Forms\Components\Hidden::make('author')
                ->default($myuser),             

                Forms\Components\TextInput::make('rut_client')
                    ->label('Cliente')
                    ->maxLength(255),

                Forms\Components\Hidden::make('active')
                    ->label('Anular')
                    ->default(false),   
            ]);
    }

    public static function table(Table $table): Table
    {


        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable(),
                Tables\Columns\TextColumn::make('description')
                    ->label('Descripción')
                    ->searchable(),
                Tables\Columns\TextColumn::make('author')
                    ->label('Creador')
                    ->searchable(),
                Tables\Columns\TextColumn::make('referent')
                    ->label('Referencia')
                    ->searchable(),
                    Tables\Columns\TextColumn::make('task.name')
                    ->label('Proceso')
                    ->sortable(),                    
                Tables\Columns\TextColumn::make('event.name')
                    ->label('Estado')
                    ->searchable(),
                Tables\Columns\TextColumn::make('client')
                    ->label('Cliente')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('F. Creación')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                // Tables\Columns\TextColumn::make('updated_at')
                //     ->dateTime()
                //     ->sortable()
                //     ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    ExportBulkAction::make()
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRecords::route('/'),
            'create' => Pages\CreateRecord::route('/create'),
            'edit' => Pages\EditRecord::route('/{record}/edit'),
        ];
    }
}
