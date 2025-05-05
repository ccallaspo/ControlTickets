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
use Illuminate\Support\Facades\Storage;

use function Laravel\Prompts\select;

class FollowupResource extends Resource
{
    protected static ?string $model = Followup::class;

    protected static ?string $navigationIcon = 'heroicon-m-shield-check';
    
    // protected static ?string $slug = 'tickets'; traduce las rutas

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

                    Section::make('Carga de Documentos')
                    ->description('Gestión de documentos del curso')
                    ->schema([
                        Forms\Components\Repeater::make('documents')
                            ->label('Documentos')
                            ->relationship()
                            ->schema([
                                Forms\Components\Select::make('typedocument_id')
                                    ->label('Tipo de Documento')
                                    ->options(\App\Models\Typedocument::pluck('name', 'id'))
                                    ->required()
                                    ->columnSpan(1),
                                
                                Forms\Components\FileUpload::make('document_archive')
                                    ->label('Archivo')
                                    ->downloadable()
                                    ->directory('documentos')
                                    ->disk('digitalocean')
                                    ->visibility('public')
                                    ->preserveFilenames()
                                    ->getUploadedFileNameForStorageUsing(
                                        fn (\Illuminate\Http\UploadedFile $file, $get): string => 
                                            ($get('../../id') ?? '') . '_' . $file->getClientOriginalName()
                                    )
                                    ->acceptedFileTypes([
                                        'application/pdf',
                                        'application/msword',
                                        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                                        'application/vnd.ms-excel',
                                        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                                        'image/jpeg',
                                        'image/png'
                                    ])
                                    ->maxSize(10240) // 10MB
                                    ->helperText('Formatos permitidos: PDF, Word, Excel, JPG, PNG. Tamaño máximo: 10MB')
                                    ->required()
                                    ->columnSpan(1)
                                    ->moveFiles()
                                    ->storeFileNamesIn('original_filename')
                                    ->afterStateUpdated(function ($state, $record) {
                                        if ($state) {
                                            try {
                                                // Verificar que el archivo existe y es accesible
                                                if (!$state->isValid()) {
                                                    throw new \Exception('El archivo no es válido o está corrupto');
                                                }

                                                // Obtener el ID del followup
                                                $followupId = $record ? $record->id : 'temp_' . time();
                                                $followupFolder = 'documentos/' . $followupId;

                                                // Verificar y crear el directorio específico del followup
                                                if (!Storage::disk('digitalocean')->exists($followupFolder)) {
                                                    Storage::disk('digitalocean')->makeDirectory($followupFolder);
                                                }

                                                // Obtener el contenido del archivo
                                                $fileContent = file_get_contents($state->getRealPath());
                                                if ($fileContent === false) {
                                                    throw new \Exception('No se pudo leer el contenido del archivo');
                                                }

                                                // Generar un nombre de archivo único
                                                $fileName = $state->getClientOriginalName();

                                                // Intentar subir el archivo directamente en la carpeta del followup
                                                $path = Storage::disk('digitalocean')->put($followupFolder . '/' . $fileName, $fileContent);
                                                
                                                if (!$path) {
                                                    throw new \Exception('La operación de subida falló sin error específico');
                                                }
                                                
                                                \Illuminate\Support\Facades\Log::info('File uploaded successfully', [
                                                    'path' => $path,
                                                    'followup_id' => $followupId,
                                                    'file_name' => $fileName,
                                                    'file_size' => $state->getSize(),
                                                    'mime_type' => $state->getMimeType()
                                                ]);
                                            } catch (\Exception $e) {
                                                \Illuminate\Support\Facades\Log::error('File upload failed', [
                                                    'error' => $e->getMessage(),
                                                    'file_name' => $state->getClientOriginalName(),
                                                    'file_size' => $state->getSize(),
                                                    'mime_type' => $state->getMimeType(),
                                                    'followup_id' => $record ? $record->id : 'temp',
                                                    'disk_config' => config('filesystems.disks.digitalocean'),
                                                    'trace' => $e->getTraceAsString()
                                                ]);
                                                
                                                // Mostrar notificación de error con más detalles
                                                \Filament\Notifications\Notification::make()
                                                    ->title('Error al subir archivo')
                                                    ->body('Error: ' . $e->getMessage())
                                                    ->danger()
                                                    ->duration(10000) // 10 segundos
                                                    ->persistent() // Permite cerrar manualmente
                                                    ->actions([
                                                        \Filament\Notifications\Actions\Action::make('Ver detalles')
                                                            ->button()
                                                            ->color('danger')
                                                            ->action(function () use ($e) {
                                                                // Crear un modal para mostrar el error completo
                                                                \Filament\Notifications\Notification::make()
                                                                    ->title('Detalles del error')
                                                                    ->body(function () use ($e) {
                                                                        $errorDetails = [
                                                                            'Mensaje' => $e->getMessage(),
                                                                            'Archivo' => $e->getFile(),
                                                                            'Línea' => $e->getLine(),
                                                                            'Stack Trace' => $e->getTraceAsString()
                                                                        ];
                                                                        
                                                                        $html = '<div style="max-height: 400px; overflow-y: auto; font-family: monospace; white-space: pre-wrap;">';
                                                                        foreach ($errorDetails as $key => $value) {
                                                                            $html .= "<strong>{$key}:</strong>\n{$value}\n\n";
                                                                        }
                                                                        $html .= '</div>';
                                                                        
                                                                        return $html;
                                                                    })
                                                                    ->danger()
                                                                    ->persistent()
                                                                    ->send();
                                                            }),
                                                    ])
                                                    ->send();
                                                
                                                // Limpiar el estado del archivo
                                                $state = null;
                                            }
                                        }
                                    })
                            ])
                            ->columns(2)
                            ->columnSpanFull()
                            ->defaultItems(1)
                            ->addActionLabel('Agregar Documento')
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => 
                                \App\Models\Typedocument::find($state['typedocument_id'])?->name ?? null
                            )
                            ->deleteAction(
                                fn (Forms\Components\Actions\Action $action) => $action
                                    ->requiresConfirmation()
                                    ->modalHeading('Eliminar documento')
                                    ->modalDescription('¿Estás seguro de que deseas eliminar este documento? Esta acción no se puede deshacer.')
                                    ->modalSubmitActionLabel('Sí, eliminar')
                                    ->modalCancelActionLabel('Cancelar')
                            )
                            ->reorderable(false)
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
