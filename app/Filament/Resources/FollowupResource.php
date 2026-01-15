<?php

namespace App\Filament\Resources;

use App\Exports\FollowupsBulkExport;
use App\Filament\Resources\FollowupResource\Pages;
use App\Filament\Resources\FollowupResource\RelationManagers;
use App\Models\Cotizacion;
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
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;


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
                Section::make(
                    fn($record) =>
                    'Seguimiento y control' . ($record && $record->cotizacion?->customer?->name
                        ? ' : ' . $record->cotizacion->customer->name
                        : '')
                )
                    ->description('')
                    ->schema([
                        Forms\Components\Hidden::make('active')
                            ->label('Anular')
                            ->default('Si'),

                        Forms\Components\TextInput::make('name')
                            ->label('SYC')
                            ->readonly()
                            ->default($newName),

                        // Forms\Components\TextInput::make('referent')
                        //     ->label('Cotizacion')
                        //     ->maxLength(255),

                        Forms\Components\Select::make('cotizacion_id')
                            ->label('Cotización')
                            ->relationship(
                                name: 'cotizacion',
                                titleAttribute: 'name',
                                modifyQueryUsing: fn($query) => $query->orderBy('id', 'desc')
                            )
                            ->searchable()
                            ->preload(),



                        Forms\Components\Hidden::make('author')
                            ->default($myuser),

                        Forms\Components\Select::make('task_id')
                            ->label('Proceso')
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
                                        ->orderBy('name', 'asc')
                                        ->pluck('name', 'id');
                                }
                                return [];
                            })
                            ->reactive()
                            ->disabled(fn(callable $get) => !$get('task_id')),

                        // Forms\Components\Select::make('customer_id')
                        //     ->relationship('customer', 'name')
                        //     ->searchable()
                        //     ->preload()
                        //     ->label('Cliente')
                        //     ->createOptionForm([

                        //         Forms\Components\Hidden::make('active')
                        //             ->label('Anular')
                        //             ->default('Si'),
                        //         Forms\Components\TextInput::make('rut')
                        //             ->maxLength(255),
                        //         Forms\Components\TextInput::make('name')
                        //             ->label('Empresa')
                        //             ->required()
                        //             ->maxLength(255),
                        //         Forms\Components\TextInput::make('represent')
                        //             ->label('Contacto')
                        //             ->maxLength(255),
                        //         Forms\Components\TextInput::make('phone')
                        //             ->label('Telefono')
                        //             ->maxLength(255),
                        //         Forms\Components\TextInput::make('email')
                        //             ->label('Correo Electronico')
                        //             ->maxLength(255),
                        //         Forms\Components\Hidden::make('author')
                        //             ->default($myuser),
                        //     ]),
                    ])->columns(4),



                // ...
                Section::make('Financiamiento')
                    ->description('Información del curso y participantes.')
                    ->schema([
                        Forms\Components\TextInput::make('cod_sence_course')
                            ->label('Código Sence')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('name_course')
                            ->label('Nombre de curso')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('id_sence')
                            ->label('Código ID')
                            ->maxLength(255),

                        Forms\Components\Select::make('modalily')
                            ->label('Modalidad')
                            ->options(function () {
                                return \App\Models\Modalidades::orderBy('name', 'asc')->pluck('name', 'name');
                            }),

                        // --- Grupo para Fechas y Horas (3 columnas forzadas) ---
                        Forms\Components\Group::make()
                            // Esto asegura que el grupo ocupe todo el ancho disponible si la sección padre tiene más de una columna
                            ->columnSpanFull()
                            ->schema([
                                Forms\Components\DatePicker::make('f_star')
                                    ->label('Fecha Inicio'),

                                Forms\Components\DatePicker::make('f_end')
                                    ->label('Fecha Termino'),

                                Forms\Components\TextInput::make('n_hours')
                                    ->label('N° Horas')
                                    ->numeric()
                                    ->maxLength(255),
                            ])
                            ->columns(3),

                    ])
                    // La sección padre sigue en 2 columnas (por ejemplo, Codigo Sence y Nombre de curso)
                    ->columns(2),

                Forms\Components\Group::make()
    ->schema([
        Forms\Components\Section::make('Datos de Ejecución (Operaciones)')
            ->description('Active esta opción para generar los datos operativos.')

            // --- AQUÍ AGREGAMOS EL BOTÓN DE LIMPIAR ---
            ->headerActions([
                Forms\Components\Actions\Action::make('limpiar_datos')
                    ->label('Limpiar formulario')
                    ->icon('heroicon-m-trash') // Icono de basura
                    ->color('danger') // Color rojo para indicar precaución
                    ->tooltip('Borrar todos los campos de ejecución')
                    ->requiresConfirmation()  // Pide confirmación para no borrar por error
                    ->modalHeading('¿Limpiar datos de ejecución?')
                    ->modalDescription('Se borrarán los datos de esta sección para que puedas escribirlos desde cero. Los datos cotizados no se verán afectados.')
                    ->visible(fn(Forms\Get $get) => $get('has_execution_data')) // Solo visible si el toggle está activo
                    ->action(function (Forms\Set $set) {
                        // Seteamos a null todos los campos 'exec_'
                        $set('exec_cod_sence_course', null);
                        $set('exec_name_course', null);
                        $set('exec_id_sence', null);
                        $set('exec_modalily', null);
                        $set('exec_f_star', null);
                        $set('exec_f_end', null);
                        $set('exec_n_hours', null); // Limpiamos el nuevo campo
                        
                        // Opcional: Notificar al usuario que se limpió
                        \Filament\Notifications\Notification::make()
                            ->title('Campos limpiados')
                            ->success()
                            ->send();
                    }),
            ])
            // -----------------------------------------

            ->schema([
                Forms\Components\Toggle::make('has_execution_data')
                    ->label('Habilitar Curso en Ejecución')
                    ->onColor('success')
                    ->offColor('gray')
                    ->default(false)
                    ->live()
                    ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                        if ($state) {
                            // Copia los datos si se activa
                            $set('exec_cod_sence_course', $get('cod_sence_course'));
                            $set('exec_name_course', $get('name_course'));
                            $set('exec_id_sence', $get('id_sence'));
                            $set('exec_modalily', $get('modalily'));
                            $set('exec_f_star', $get('f_star'));
                            $set('exec_f_end', $get('f_end'));
                            $set('exec_n_hours', $get('n_hours')); 
                        }
                    }),

                Forms\Components\Group::make()
                    ->visible(fn(Forms\Get $get) => $get('has_execution_data'))
                    ->schema([
                        
                        // GRUPO 1: CÓDIGOS Y MODALIDAD (Mantiene el Grid de 2 columnas)
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('exec_cod_sence_course')
                                    ->label('Código Sence (Ejecución)')
                                    ->maxLength(255),

                                Forms\Components\TextInput::make('exec_name_course')
                                    ->label('Nombre de curso (Ejecución)')
                                    ->required(fn(Forms\Get $get) => $get('has_execution_data'))
                                    ->maxLength(255),

                                Forms\Components\TextInput::make('exec_id_sence')
                                    ->label('Código ID (Ejecución)')
                                    ->maxLength(255),

                                Forms\Components\Select::make('exec_modalily')
                                    ->label('Modalidad (Ejecución)')
                                    ->options(function () {
                                        return \App\Models\Modalidades::orderBy('name', 'asc')->pluck('name', 'name');
                                    }),
                            ]),
                            
                        // GRUPO 2: FECHAS Y HORAS (NUEVO - Forzamos 3 columnas)
                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\DatePicker::make('exec_f_star')
                                    ->label('Fecha Inicio (Ejecución)')
                                    ->required(fn(Forms\Get $get) => $get('has_execution_data')),

                                Forms\Components\DatePicker::make('exec_f_end')
                                    ->label('Fecha Termino (Ejecución)')
                                    ->required(fn(Forms\Get $get) => $get('has_execution_data')),
                                    
                                Forms\Components\TextInput::make('exec_n_hours')
                                    ->label('N° Horas (Ejecución)')
                                    ->numeric()
                                    ->required(fn(Forms\Get $get) => $get('has_execution_data'))
                                    ->maxLength(255),

                            ])
                            ->columns(3) // Distribuye los 3 campos en una sola fila
                            ->columnSpanFull(), // Asegura que este grupo ocupe todo el ancho del contenedor
                            
                    ]),
            ])
    ])->columnSpanFull(),

                Section::make('Carga de Documentos')
                    ->description('Gestión de documentos del curso')
                    ->schema([
                        Forms\Components\Repeater::make('documents')
                            ->label('Documentos')
                            ->relationship()
                            ->mutateRelationshipDataBeforeFillUsing(function (array $data, $record): array {
                                // Asegurar que la ruta del archivo sea correcta cuando se carga desde la relación
                                if (isset($data['document_archive']) && !empty($data['document_archive'])) {
                                    $filePath = $data['document_archive'];
                                    // Si la ruta no incluye el directorio completo, construirla
                                    if ($record && !str_starts_with($filePath, 'documentos/')) {
                                        $followupId = $record->followup_id ?? 'temp';
                                        $data['document_archive'] = 'documentos/' . $followupId . '/' . $filePath;
                                    }
                                }
                                return $data;
                            })
                            ->schema([
                                Forms\Components\Select::make('typedocument_id')
                                    ->label('Tipo de Documento')
                                    ->options(\App\Models\Typedocument::pluck('name', 'id'))
                                    // ->required()
                                    ->columnSpan(1),

                                Forms\Components\FileUpload::make('document_archive')
                                    ->label('Archivo')
                                    ->openable()
                                    ->downloadable()
                                    ->directory(fn($get) => 'documentos/' . ($get('../../followup_id') ?? $get('../../id') ?? 'temp'))
                                    ->disk('digitalocean')
                                    ->visibility('public')
                                    ->preserveFilenames(false)
                                    ->getUploadedFileNameForStorageUsing(
                                        function (\Illuminate\Http\UploadedFile $file, $get, $record) {
                                            $followupId = $record?->id ?? $get('../../followup_id') ?? $get('../../id') ?? 'temp';
                                            $typedocumentId = $get('typedocument_id');
                                            $typedocumentName = '';
                                            if ($typedocumentId) {
                                                $typedocument = \App\Models\Typedocument::find($typedocumentId);
                                                $typedocumentName = $typedocument ? $typedocument->name : 'tipo';
                                            } else {
                                                $typedocumentName = 'tipo';
                                            }
                                            // Sanitizar nombre del tipo de documento
                                            $typedocumentName = preg_replace('/[^A-Za-z0-9_\-]/', '', str_replace(' ', '_', $typedocumentName));
                                            
                                            // Obtener y sanitizar el nombre original del archivo
                                            $originalName = $file->getClientOriginalName();
                                            $pathInfo = pathinfo($originalName);
                                            $fileName = $pathInfo['filename'] ?? 'archivo';
                                            $extension = $pathInfo['extension'] ?? '';
                                            
                                            // Sanitizar el nombre del archivo (remover espacios y caracteres especiales)
                                            $fileName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $fileName);
                                            $fileName = preg_replace('/_+/', '_', $fileName); // Reemplazar múltiples guiones bajos por uno
                                            $sanitizedFileName = $fileName . (!empty($extension) ? '.' . $extension : '');
                                            
                                            return $followupId . '_' . $typedocumentName . '_' . $sanitizedFileName;
                                        }
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
                                    ->maxSize(30720) // 30MB
                                    ->helperText('Formatos permitidos: PDF, Word, Excel, JPG, PNG. Tamaño máximo: 30MB')
                                    //->required()
                                    ->columnSpan(1)
                                    ->moveFiles()
                                    ->storeFileNamesIn('original_filename')
                            ])
                            ->columns(2)
                            ->columnSpanFull()
                            ->defaultItems(0)
                            ->addActionLabel('Agregar Documento')
                            ->collapsible()
                            ->itemLabel(
                                fn(array $state): ?string =>
                                \App\Models\Typedocument::find($state['typedocument_id'] ?? null)?->name ?? 'Sin tipo'
                            )
                            ->deleteAction(
                                fn(Forms\Components\Actions\Action $action) => $action
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
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('id_sence')
                    ->label('Código ID')
                    ->size('sm')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->searchable(),

                // Tables\Columns\TextColumn::make('cotizacion_id')
                //     ->label('Cotización')
                //     ->getStateUsing(fn($record) => $record->cotizacion?->name ?? $record->referent)
                //     ->sortable()
                //     ->size('sm')
                //     ->toggleable(isToggledHiddenByDefault: false)
                //     ->extraAttributes(['style' => 'width: 1px;'])
                //     ->searchable(),

                Tables\Columns\TextColumn::make('cotizacion_id')
                    ->label('Cotización')
                    ->getStateUsing(fn($record) => $record->cotizacion?->name ?? $record->referent)
                    ->sortable()
                    ->size('sm')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->extraAttributes(['style' => 'width: 1px;'])
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        // Limpia la búsqueda para obtener solo el número
                        $search = str_replace('OT/', '', $search);

                        // Revisa en la relación de cotizaciones Y en la columna 'referent'
                        return $query->whereHas('cotizacion', function (Builder $query) use ($search) {
                            $query->where('name', 'like', "%{$search}%");
                        })->orWhere('referent', 'like', "%{$search}%");
                    }),

                Tables\Columns\TextColumn::make('event.name')
                    ->label('Estado')
                    ->size('sm')
                    //    ->badge()
                    ->searchable()
                    ->sortable()
                    ->width('100px')
                    ->icon(
                        fn(Followup $record): ?string =>
                        !empty($record->event->icono) ? $record->event->icono : null
                    )
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'Cotización Enviada' => 'Cotización Enviada',
                        'Coordinar Curso' => 'Coordinar Curso',
                        'Matricular Curso' => 'Matricular Curso',
                        'Curso en Proceso' => 'Curso en Proceso',
                        'Curso Finalizado' => 'Curso Finalizado',
                        'Generar DJ' => 'Generar DJ',
                        'Por Facturar' => 'Por Facturar',
                        default => $state,
                    })
                    ->color('white')
                    ->extraAttributes(function (Followup $record) {
                        $color = $record->event?->description;
                        if (!$color) {
                            return [];
                        }
                        return [
                            'style' => "
                                background-color: {$color} !important;
                                border-color: {$color} !important;
                                color: #ffffff !important;
                                padding: 0.375rem 0.75rem !important;
                                border-radius: 0.375rem !important;
                                font-weight: 500 !important;
                                display: inline-flex !important;
                                align-items: center !important;
                                justify-content: center !important;
                                gap: 0.375rem !important;
                                white-space: nowrap !important;
                                text-align: center !important;
                                min-width: fit-content !important;
                            ",
                            'class' => 'custom-badge-colored'
                        ];
                    }),

                Tables\Columns\TextColumn::make('name_course')
                    ->label('Curso')
                    ->sortable()
                    ->wrap()
                    ->size('sm')
                    ->searchable(),

                // Tables\Columns\TextColumn::make('cotizacion.customer.name')
                //     ->label('Cliente')
                //     ->sortable()
                //     ->size('sm')
                //     ->wrap()
                //     ->toggleable(isToggledHiddenByDefault: false)
                //     ->searchable(),

                Tables\Columns\TextColumn::make('cliente')
                    ->label('Cliente')
                    ->getStateUsing(
                        fn(Followup $record) =>
                        $record->cotizacion?->customer?->name
                            ?? $record->customer?->name
                            ?? '-'
                    )
                    ->sortable(false)
                    ->size('sm')
                    ->wrap()
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->whereHas('cotizacion.customer', function (Builder $query) use ($search) {
                            $query->where('name', 'like', "%{$search}%");
                        })->orWhereHas('customer', function (Builder $query) use ($search) {
                            $query->where('name', 'like', "%{$search}%");
                        });
                    }),

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
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\Action::make('upload_documents')
                        ->label('Subir Documento')
                        ->tooltip('Subir Documento')
                        ->color('primary')
                        ->icon('heroicon-o-document-plus')
                        ->modalHeading('Cargar Documentos')
                        ->form(fn(\Filament\Forms\Form $form, \Illuminate\Database\Eloquent\Model $record): \Filament\Forms\Form => $form->schema(
                            static::getDocumentosFormSchemaForModal($record)
                        ))
                        ->action(function (array $data, \Illuminate\Database\Eloquent\Model $record): void {
                            // Procesar cada documento del repeater y agregarlo al followup
                            if (isset($data['documents']) && is_array($data['documents'])) {
                                $documentsCreated = 0;
                                foreach ($data['documents'] as $documentData) {
                                    // Normalizar el valor del archivo (puede ser array o string)
                                    $documentArchive = $documentData['document_archive'] ?? null;
                                    if (is_array($documentArchive)) {
                                        $documentArchive = !empty($documentArchive) ? $documentArchive[0] : null;
                                    }
                                    
                                    if (!empty($documentArchive) && !empty($documentData['typedocument_id'])) {
                                        \App\Models\Document::create([
                                            'followup_id' => $record->id,
                                            'typedocument_id' => $documentData['typedocument_id'],
                                            'document_archive' => $documentArchive,
                                        ]);
                                        $documentsCreated++;
                                    }
                                }
                                
                                if ($documentsCreated > 0) {
                                    \Filament\Notifications\Notification::make()
                                        ->title('Documentos agregados exitosamente')
                                        ->body($documentsCreated . ' documento(s) agregado(s).')
                                        ->success()
                                        ->send();
                                } else {
                                    \Filament\Notifications\Notification::make()
                                        ->title('No se agregaron documentos')
                                        ->body('Asegúrate de seleccionar el tipo de documento y subir un archivo.')
                                        ->warning()
                                        ->send();
                                }
                            }
                        }),
                ]),
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


    protected static function getDocumentosFormSchema(): array
    {
        return [
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
                        ->openable()
                        // Verificación si el registro existe o es nulo
                        ->directory(function ($record, Forms\Get $get) {
                            $followupId = $record?->id ?? $get('../../id') ?? 'temp';
                            return 'documentos/' . $followupId;
                        })
                        ->disk('digitalocean')
                        ->visibility('public')
                        ->preserveFilenames()
                        ->getUploadedFileNameForStorageUsing(
                            function (\Illuminate\Http\UploadedFile $file, $get, $livewire, $record) {
                                $followupId = $record?->id ?? $get('../../id') ?? 'temp';
                                $typedocumentId = $get('typedocument_id');
                                $typedocumentName = '';
                                if ($typedocumentId) {
                                    $typedocument = \App\Models\Typedocument::find($typedocumentId);
                                    $typedocumentName = $typedocument ? $typedocument->name : 'tipo';
                                } else {
                                    $typedocumentName = 'tipo';
                                }
                                $typedocumentName = preg_replace('/[^A-Za-z0-9_\-]/', '', str_replace(' ', '_', $typedocumentName));
                                return $followupId . '_' . $typedocumentName . '_' . $file->getClientOriginalName();
                            }
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
                        ->maxSize(30720)
                        ->helperText('Formatos permitidos: PDF, Word, Excel, JPG, PNG. Tamaño máximo: 30MB')
                        ->required()
                        ->columnSpan(1)
                        ->moveFiles()
                        ->storeFileNamesIn('original_filename'),
                ])
                ->columns(2)
                ->columnSpanFull()
                ->defaultItems(1)
                ->addActionLabel('Agregar Documento')
                ->collapsible()
                ->itemLabel(
                    fn(array $state): ?string => \App\Models\Typedocument::find($state['typedocument_id'])?->name ?? null
                )
                ->deleteAction(
                    fn(Forms\Components\Actions\Action $action) => $action
                        ->requiresConfirmation()
                        ->modalHeading('Eliminar documento')
                        ->modalDescription('¿Estás seguro de que deseas eliminar este documento? Esta acción no se puede deshacer.')
                        ->modalSubmitActionLabel('Sí, eliminar')
                        ->modalCancelActionLabel('Cancelar')
                )
                ->reorderable(false)
        ];
    }

    protected static function getDocumentosFormSchemaForModal($record): array
    {
        return [
            Forms\Components\Repeater::make('documents')
                ->label('Documentos')
                ->schema([
                    Forms\Components\Select::make('typedocument_id')
                        ->label('Tipo de Documento')
                        ->options(\App\Models\Typedocument::pluck('name', 'id'))
                        ->required()
                        ->columnSpan(1),
                    Forms\Components\FileUpload::make('document_archive')
                        ->label('Archivo')
                        ->openable()
                        ->directory('documentos/' . $record->id)
                        ->disk('digitalocean')
                        ->visibility('public')
                        ->preserveFilenames(false)
                        ->getUploadedFileNameForStorageUsing(
                            function (\Illuminate\Http\UploadedFile $file, Forms\Get $get) use ($record) {
                                $followupId = $record->id;
                                $typedocumentId = $get('typedocument_id');
                                $typedocumentName = '';
                                if ($typedocumentId) {
                                    $typedocument = \App\Models\Typedocument::find($typedocumentId);
                                    $typedocumentName = $typedocument ? $typedocument->name : 'tipo';
                                } else {
                                    $typedocumentName = 'tipo';
                                }
                                // Sanitizar nombre del tipo de documento
                                $typedocumentName = preg_replace('/[^A-Za-z0-9_\-]/', '', str_replace(' ', '_', $typedocumentName));
                                
                                // Obtener y sanitizar el nombre original del archivo
                                $originalName = $file->getClientOriginalName();
                                $pathInfo = pathinfo($originalName);
                                $fileName = $pathInfo['filename'] ?? 'archivo';
                                $extension = $pathInfo['extension'] ?? '';
                                
                                // Sanitizar el nombre del archivo (remover espacios y caracteres especiales)
                                $fileName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $fileName);
                                $fileName = preg_replace('/_+/', '_', $fileName); // Reemplazar múltiples guiones bajos por uno
                                $sanitizedFileName = $fileName . (!empty($extension) ? '.' . $extension : '');
                                
                                return $followupId . '_' . $typedocumentName . '_' . $sanitizedFileName;
                            }
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
                        ->maxSize(30720)
                        ->helperText('Formatos permitidos: PDF, Word, Excel, JPG, PNG. Tamaño máximo: 30MB')
                        ->required()
                        ->columnSpan(1)
                        ->moveFiles()
                        ->storeFileNamesIn('original_filename'),
                ])
                ->columns(2)
                ->columnSpanFull()
                ->defaultItems(1)
                ->addActionLabel('Agregar Documento')
                ->collapsible()
                ->itemLabel(
                    fn(array $state): ?string => \App\Models\Typedocument::find($state['typedocument_id'] ?? null)?->name ?? 'Nuevo documento'
                )
                ->reorderable(false),
        ];
    }
}
