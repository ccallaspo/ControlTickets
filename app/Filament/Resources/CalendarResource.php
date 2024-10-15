<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CalendarResource\Pages;
use App\Filament\Resources\CalendarResource\RelationManagers;
use App\Models\Calendar;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class CalendarResource extends Resource
{
    protected static ?string $model = Calendar::class;

    protected static ?string $navigationIcon = 'heroicon-c-calendar-days';

    protected static ?string $navigationLabel = 'Agenda';
    public static ?string $pluralModelLabel = 'Agenda';
    protected static ?string $navigationGroup = 'Operaciones';
    ///Ocultar
    protected static bool $shouldRegisterNavigation = false;

    public static function form(Form $form): Form
    {
        $myuser = auth()->user()->name;
        $users = User::where('id', '!=', Auth::id())->get();
        $options = $users->pluck('name', 'id')->toArray();


        return $form
            ->schema([
                Section::make('Recordatorio')
                    ->description('')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('Asunto')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('comment')
                            ->label('Comentario')
                            ->maxLength(255),
                        Forms\Components\Select::make('status')
                            ->label('Estado')
                            ->required()
                            ->options([
                                'Pendiente' => 'Pendiente',
                                'En Proceso' => 'En Proceso',
                                'Completado' => 'Completado',
                            ])
                            ->default('Pendiente'),
                        Forms\Components\CheckboxList::make('participants')
                        ->options($options)
                        ->label('Seleccione usuarios'),
                        Forms\Components\DateTimePicker::make('date_star')
                            ->label('Fecha de Inicio')
                            ->required(),

                        Forms\Components\DateTimePicker::make('date_end')
                            ->label('Fecha de Cierre')
                            ->required(),

                        Forms\Components\Hidden::make('author')
                            ->default($myuser),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Asunto')
                    ->searchable(),
                Tables\Columns\TextColumn::make('comment')
                    ->label('Comentario')
                    ->searchable(),
                    Tables\Columns\TextColumn::make('participants')
                    ->label('Participantes')
                    ->searchable()
                    ->getStateUsing(function ($record) {
                        // Asegúrate de que $record->participants sea una cadena
                        $participants = is_array($record->participants) ? implode(',', $record->participants) : $record->participants;
                
                        // Ahora explota la cadena de participantes
                        $participantIds = explode(',', $participants);
                        $names = User::whereIn('id', $participantIds)->pluck('name')->toArray();
                
                        return implode(', ', $names);
                    }),
                Tables\Columns\TextColumn::make('status')
                    ->label('Estado')
                    ->sortable()
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Pendiente' => 'danger',
                        'En Proceso' => 'warning',
                        'Completado' => 'success',
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('date_star')
                    ->label('Fecha de Inicio')
                    ->searchable(),
                Tables\Columns\TextColumn::make('date_end')
                    ->label('Fecha de Cierre')
                    ->searchable(),
                Tables\Columns\TextColumn::make('author')
                    ->label('Creado por')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListCalendars::route('/'),
            'create' => Pages\CreateCalendar::route('/create'),
            'view' => Pages\ViewCalendar::route('/{record}'),
            'edit' => Pages\EditCalendar::route('/{record}/edit'),
        ];
    }
}
