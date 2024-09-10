<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FollowupResource\Pages;
use App\Filament\Resources\FollowupResource\RelationManagers;
use App\Models\Event;
use App\Models\Followup;
use App\Models\Task;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class FollowupResource extends Resource
{
    protected static ?string $model = Followup::class;

    protected static ?string $navigationIcon = 'heroicon-m-shield-check';

    protected static ?string $navigationLabel = 'Tickets';
    public static ?string $pluralModelLabel = 'Tickets';
    protected static ?string $navigationGroup = 'Operaciones';

    public static function form(Form $form): Form
    {
        $myuser = auth()->user()->name;
        $prefix = 'SYC-';
        $latestName = Followup::where('name', 'like', $prefix . '%')
            ->orderBy('id', 'desc')
            ->value('name');
        $newNumber = 1;

        if ($latestName) {
            // Extract the number part from the latest name
            preg_match('/' . preg_quote($prefix, '/') . '(\d+)$/', $latestName, $matches);
            if (!empty($matches[1])) {
                // Increment the number from the latest record
                $newNumber = intval($matches[1]) + 1;
            }
        }

        $newName = $prefix . $newNumber;
        return $form
            ->schema([
                Section::make('Seguimiento y control')
                    ->description('')
                    ->schema([
                        Forms\Components\Hidden::make('active')
                            ->label('Anular')
                            ->default('Si'),

                        Forms\Components\TextInput::make('name')
                            ->label('SYC')
                            ->readonly()
                            ->default($newName),

                        Forms\Components\TextInput::make('referent')
                            ->label('Ref. Cotizacion')
                            ->maxLength(255),


                        Forms\Components\Hidden::make('author')
                            ->default($myuser),

                        Forms\Components\Select::make('task_id')
                            ->label('Tipo de Proceso')
                            ->options(Task::all()->pluck('name', 'id'))
                            ->reactive()
                            ->required()
                            ->afterStateUpdated(fn ($state, callable $set) => $set('event_id', null)),

                        Forms\Components\Select::make('event_id')
                            ->label('Estado')
                            ->required()
                            ->options(function (callable $get) {
                                $taskId = $get('task_id');
                                if ($taskId) {
                                    return Event::where('task_id', $taskId)
                                        ->orderBy('order')
                                        ->pluck('name', 'id');
                                }
                                return [];
                            })
                            ->reactive()
                            ->disabled(fn (callable $get) => !$get('task_id')),

                        Forms\Components\Select::make('customer_id')
                            ->relationship('customer', 'name')
                            ->searchable()
                            ->preload()
                            ->label('Cliente')
                            ->createOptionForm([

                                Forms\Components\Hidden::make('active')
                                    ->label('Anular')
                                    ->default('Si'),
                                Forms\Components\TextInput::make('rut')
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('name')
                                    ->label('Empresa')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('represent')
                                    ->label('Contacto')
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('phone')
                                    ->label('Telefono')
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('email')
                                    ->label('Correo Electronico')
                                    ->maxLength(255),
                                Forms\Components\Hidden::make('author')
                                    ->default($myuser),
                            ]),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('SYC')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('referent')
                    ->label('Ref. Cotización')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('event.name')
                    ->label('Estado')
                    ->badge()
                    ->searchable()
                    ->sortable()
                    ->icon(
                        fn (Followup $record): ?string =>
                        !empty($record->event->icono) ? $record->event->icono : null
                    )
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'Cotización Aprobada' => 'Cotización aprobada',
                        'Cotización enviada' => 'Cotización enviada',
                        'Terminado' => 'Terminado',
                        'rejected' => 'Rejected',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'Cotización enviada' => 'danger',
                        'Crear agenda' => 'danger',
                        'Cotización aprobada' => 'success',
                        default => 'warning',
                    }),

                Tables\Columns\TextColumn::make('customer.name')
                    ->label('Cliente')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('author')
                    ->label('Creado por')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])->defaultSort('name', 'desc')
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
            RelationManagers\NoteRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFollowups::route('/'),
            'create' => Pages\CreateFollowup::route('/create'),
            'view' => Pages\ViewFollowup::route('/{record}'),
            'edit' => Pages\EditFollowup::route('/{record}/edit'),
        ];
    }
}
