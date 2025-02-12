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
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Repeater;

use function Laravel\Prompts\select;

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
                            ->afterStateUpdated(fn($state, callable $set) => $set('event_id', null)),

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
                            ->disabled(fn(callable $get) => !$get('task_id')),

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
                    ])->columns(4),



                Section::make('Agendar Curso')
                    ->description('Información del curso y participantes.')
                    ->schema([
                        Forms\Components\TextInput::make('cod_sence_course')
                            ->label('Código Sence')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('name_course')
                            ->label('Nombre de curso')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('id_sence')
                            ->label('ID Sence')
                            ->maxLength(255),
                        Forms\Components\Select::make('modalily')
                            ->label('Modalidad')
                            ->options(function () {
                                return \App\Models\Modalidades::orderBy('name', 'asc')->pluck('name', 'name');
                            }),

                        /*                         Forms\Components\TimePicker::make('h_star')
                            ->label('Horario de Inicio')
                            ->seconds(false),
                        Forms\Components\TimePicker::make('h_end')
                            ->label('Horario de Termino')
                            ->seconds(false), */
                        Forms\Components\DatePicker::make('f_star')
                            ->label('Fecha Inicio'),
                        Forms\Components\DatePicker::make('f_end')
                            ->label('Fecha Termino'),

                        Forms\Components\Fieldset::make('Subir Participantes y Orden de compra')
                            ->schema([
                                Forms\Components\FileUpload::make('doc_participant')
                                    ->label('Cargar Participantes')
                                    ->downloadable()
                                    ->directory('agenda/participantes')
                                    ->disk('public')
                                    ->visibility('public'),
                                Forms\Components\TextInput::make('n_hours')
                                    ->label('Número de horas')
                                    ->maxLength(255),

                                Forms\Components\FileUpload::make('doc_oc')
                                    ->label('Cargar OC')
                                    ->downloadable()
                                    ->directory('agenda/oc')
                                    ->disk('public')
                                    ->visibility('public')
                                    ->visible(fn($get) => Auth::user()->email !== 'soporte@otecproyecta.cl'),

                            ])
                            ->columns(3)

                    ])->columns(),

                    Section::make('Detalle de horarios (Opcional)')
                    ->description('Utilizado para desglosar los horarios de capacitación de manera detallada.')
                    ->schema([
                
                        Repeater::make('week')
                            ->schema([
                                Forms\Components\DatePicker::make('day')
                                    ->label('Día')
                                    ->native(false),
                                Forms\Components\TimePicker::make('start_time')
                                    ->label('Hora Inicio')
                                    ->seconds(false),
                                Forms\Components\TimePicker::make('end_time')
                                    ->label('Hora Termino')
                                    ->seconds(false),
                            ])
                            ->columns(3)  
                            ->label('Horario.')
                            ->columnSpan(2),  
                
                    ])
                    ->columns(1), 
                

            ])->columns(2);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->query(Followup::query()->restrictedForSupportUser())
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->sortable()
                    ->hidden(),
                Tables\Columns\TextColumn::make('name')
                    ->label('SYC')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),

                Tables\Columns\TextColumn::make('referent')
                    ->label('Ref. Cotización')
                    ->sortable()
                    ->size('sm')
                    ->extraAttributes(['style' => 'width: 1px;'])
                    ->searchable(),

                Tables\Columns\TextColumn::make('event.name')
                    ->label('Estado')
                    ->size('sm')
                    ->badge()
                    ->searchable()
                    ->sortable()
                    ->icon(
                        fn(Followup $record): ?string =>
                        !empty($record->event->icono) ? $record->event->icono : null
                    )
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'Cotización enviada' => 'Cotización enviada',
                        'Cotización aprobada' => 'Cotización aprobada',
                        'Curso agendado' => 'Curso agendado',
                        'Curso matriculado' => 'Curso matriculado',
                        'Curso en proceso' => 'Curso en proceso',
                        'Curso finalizado' => 'Curso finalizado',
                        'DJ OTEC generada' => 'DJ OTEC generada',
                        'DJs generadas' => 'DJs generadas',
                        'Por facturar' => 'Por facturar',
                        default => $state,
                    })
                    ->color(fn(string $state): string => match ($state) {
                        'Cotización enviada' => 'danger',
                        'Cotización aprobada' => 'success',
                        'Curso agendado' => 'primary',
                        'Curso matriculado' => 'info',
                        'Curso en proceso' => 'primary',
                        'Curso finalizado' => 'success',
                        'DJ OTEC generada' => 'success',
                        'DJs generadas' => 'success',
                        'Por facturar' => 'warning',
                        default => 'warning',
                    }),

                Tables\Columns\TextColumn::make('name_course')
                    ->label('Curso')
                    ->sortable()
                    ->wrap()
                    ->size('sm')
                    ->searchable(),

                Tables\Columns\TextColumn::make('customer.name')
                    ->label('Cliente')
                    ->sortable()
                    ->size('sm')
                    ->wrap()
                    ->searchable(),
                Tables\Columns\TextColumn::make('author')
                    ->label('Creado por')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('F. Creac.')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('F. Ult. Act.')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])->defaultSort('id', 'desc')
            ->filters([
                SelectFilter::make('event.name')
                    ->label('Estados')
                    ->indicator('Estado')
                    ->relationship('event', 'name')
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
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
