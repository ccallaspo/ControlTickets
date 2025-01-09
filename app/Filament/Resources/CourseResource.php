<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CourseResource\Pages;
use App\Filament\Resources\CourseResource\RelationManagers;
use App\Models\Course;
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

class CourseResource extends Resource
{
    protected static ?string $model = Course::class;

    protected static ?string $navigationGroup = 'Configuración';
    protected static ?string $navigationLabel = 'Cursos';
    public static ?string $pluralModelLabel = 'Cursos';
    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {



        return $form
            ->schema([
                Section::make('Curso')
                    ->description('Maestro de cursos.')
                    ->schema([
                        Forms\Components\TextInput::make('cod_sence')
                            ->label('Código / Sence')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('name')
                        ->label('Curso')
                        ->required()
                        ->maxLength(255)
                        ->autocapitalize(),
                        Forms\Components\TextInput::make('description')
                            ->label('Descripción'),
                        Forms\Components\Select::make('modality')
                            ->label('Modalidad')
                            ->required()
                            ->options([
                                'E-learning' => 'E-learning',
                                'Presencial' => 'Presencial',
                                'A-Distancia' => 'A-Distancia',
                                'Mixta' => 'Mixta',
                                'Sincronica o Presencial' => 'Sincronica o Presencial',
                            ]),
                        Forms\Components\Select::make('category')
                            ->label('Categoria')
                            ->options([
                                'Administración' => 'Administración',
                                'Comercio' => 'Comercio',
                                'Idiomas' => 'Idiomas',
                            ]),
                        Forms\Components\TextInput::make('hour')
                            ->label('Horas')
                            ->maxLength(255)
                            ->suffix('Horas')
                            ->numeric(),
                        Forms\Components\TextInput::make('unit_value')
                            ->label('Valor Unitario')
                            ->numeric(),
                        // Forms\Components\Select::make('type')
                        //     ->label('Tipo de Curso')
                        //     ->options([
                        //         'Con Franquicia' => 'Con Franquicia',
                        //         'Sin Franquicia' => 'Sin Franquicia',
                        //     ]),
                    ])
                    ->columns(2), // Esto establece que el formulario tendrá 2 columnas
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('cod_sence')
                    ->label('Código')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Curso')
                    ->searchable(),
                Tables\Columns\TextColumn::make('modality')
                    ->label('Modalidad')
                    ->searchable(),
                Tables\Columns\TextColumn::make('category')
                    ->label('Categoria')
                    ->searchable(),
                Tables\Columns\TextColumn::make('hour')
                    ->label('Horas')
                    ->searchable(),
                Tables\Columns\TextColumn::make('unit_value')
                    ->label('Costo')
                    ->numeric(decimalPlaces: 0)
                    ->searchable(),
                Tables\Columns\TextColumn::make('type')
                    ->label('Tipo')
                    ->searchable(),
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
            RelationManagers\AddCourseRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCourses::route('/'),
            'create' => Pages\CreateCourse::route('/create'),
            'view' => Pages\ViewCourse::route('/{record}'),
            'edit' => Pages\EditCourse::route('/{record}/edit'),
        ];
    }
}
