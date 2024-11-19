<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CotizacionResource\Pages;
use App\Filament\Resources\CotizacionResource\RelationManagers;
use App\Models\AddCourse;
use App\Models\Cotizacion;
use App\Models\Course;
use App\Models\Customer;
use Filament\Forms;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Select;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Forms\Components\Section;
use Filament\Forms\Get;
use Filament\Forms\Set;

class CotizacionResource extends Resource
{
    protected static ?string $model = Cotizacion::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-currency-dollar';

    protected static ?string $navigationLabel = 'Cotizaciones';
    public static ?string $pluralModelLabel = 'Cotizaciones';
    protected static ?string $navigationGroup = 'Operaciones';
    public $costs = [];

    public static function form(Form $form): Form
    {

        $myuser = auth()->user()->name;

        $prefix = 'OT/';

        // Consulta el último registro cuyo nombre sigue el formato OT/
        $latestName = Cotizacion::where('name', 'like', $prefix . '%')
            ->orderBy('id', 'desc')
            ->value('name');

        // Inicializa el nuevo número
        $newNumber = 1;

        if ($latestName) {
            // Extrae la parte numérica del último nombre
            preg_match('/' . preg_quote($prefix, '/') . '(\d+)$/', $latestName, $matches);
            if (!empty($matches[1])) {
                // Incrementa el número del último registro
                $newNumber = intval($matches[1]) + 1;
            }
        }

        // Genera el nuevo nombre
        $newName = $prefix . $newNumber;

        return $form
            ->schema([
                Section::make('Cotización')
                    ->description('')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nombre')
                            ->default(fn() => $newName),
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


                        Forms\Components\Select::make('type')
                            ->label('Tipo de Curso')
                            ->options([
                                'Con Franquicia' => 'Con Franquicia',
                                'Sin Franquicia' => 'Sin Franquicia',
                            ])->required(),
                        Forms\Components\Hidden::make('author')
                            ->default($myuser),
                    ])->columns(3),


                Repeater::make('costs')
                    ->label('Costos de Curso')
                    ->schema([
                        Forms\Components\TextInput::make('grup')
                            ->label('Grupos'),
                        Forms\Components\TextInput::make('thour')
                            ->label('Total Horas')
                            ->required(),

                        Forms\Components\TextInput::make('tpart')
                            ->label('Total Participantes')
                            ->numeric()
                            ->lazy()
                            ->placeholder(0)
                            ->required()
                            ->afterStateUpdated(function (Get $get, Set $set) {
                                self::updateTotals($get, $set);
                            }),

                        Forms\Components\TextInput::make('vunit')
                            ->label('Valor Unitario')
                            ->numeric()
                            ->prefix('$')
                            ->required()
                            ->lazy()
                            ->placeholder(0)
                            ->afterStateUpdated(function (Get $get, Set $set) {
                                self::updateTotals($get, $set);
                            }),

                        Forms\Components\TextInput::make('costs')
                            ->label('Costo Total')
                            ->readOnly()
                            ->prefix('$'),
                    ])
                    ->reactive()
                    ->columnSpan(2)
                    ->columns(5),


                Section::make('Detalles del Curso')
                    ->description('')
                    ->schema([


                        Forms\Components\Select::make('course_id')
                            ->label('Curso')
                            ->options(Course::all()->pluck('name', 'id'))
                            ->reactive()
                            ->searchable()
                            ->preload()
                            ->required()
                            ->afterStateUpdated(fn($state, callable $set) => $set('add_course_id', null)),

                        Forms\Components\Select::make('add_course_id')
                            ->label('Módulos del Curso')
                            ->required()
                            ->options(function (callable $get) {
                                $courseId = $get('course_id');
                                if ($courseId) {
                                    return AddCourse::where('course_id', $courseId)
                                        ->orderBy('order')
                                        ->pluck('title', 'id');
                                }
                                return [];
                            })
                            ->reactive()
                            ->disabled(fn(callable $get) => !$get('course_id'))
                            ->afterStateUpdated(function ($state, callable $set) {
                                $content = AddCourse::find($state)?->description ?? '';
                                $set('content', $content);
                            }),

                        Forms\Components\RichEditor::make('content')
                            ->label('Contenido')
                            ->required()
                            ->default(function (callable $get) {
                                $addCourseId = $get('add_course_id');
                                return AddCourse::find($addCourseId)?->description ?? '';
                            })->columnSpan(2),

                        Forms\Components\Hidden::make('author')
                            ->default($myuser),


                    ])->columns(2),
            ]);
    }


    public static function updateTotals(Get $get, Set $set): void
    {
        $selectedCosts = collect($get('costs'))->filter(fn($item) => !empty($item['tpart']) && !empty($item['vunit']));


        $vunit = (float) $get("vunit");
        $tpart = (float) $get("tpart");

        if ($vunit && $tpart) {
            // Calcular el costo
            $cost = $vunit * $tpart;

            //$set('costs', number_format($cost, 2, ',', '.'));
            $set('costs', $cost);
        } else {

            $set('costs', null);
        }
    }




    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Cotización')
                    ->size('sm')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('course.cod_sence')
                    ->label('Código')
                    ->size('sm')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('course.name')
                    ->label('Curso')
                    ->wrap()
                    ->size('sm')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('customer.name')
                    ->label('Cliente')
                    ->wrap()
                    ->size('sm')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('course.modality')
                    ->label('Modalidad')
                    ->wrap()
                    ->size('sm')
                    ->searchable()
                    ->sortable()
                    ->color(fn(string $state): string => match ($state) {
                           default => 'secondary',
                    }),
                Tables\Columns\TextColumn::make('author')
                    ->size('sm')
                    ->label('Creado por')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fch. de creac')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Ult. act')
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
                Tables\Actions\Action::make('download')
                    ->label('Descargar')
                    ->url(fn($record) => route('pdf.download', $record->id))
                    ->openUrlInNewTab(true)

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
            'index' => Pages\ListCotizacions::route('/'),
            'create' => Pages\CreateCotizacion::route('/create'),
            'view' => Pages\ViewCotizacion::route('/{record}'),
            'edit' => Pages\EditCotizacion::route('/{record}/edit'),
        ];
    }
}
