<?php

namespace App\Filament\Resources\CourseResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AddCourseRelationManager extends RelationManager
{
    protected static string $relationship = 'AddCourse';
    protected static ?string $title = 'Módulos';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                ->label('Módulo')
                    ->required()
                    ->maxLength(255),
              
                Forms\Components\RichEditor::make('description')
                ->label('Contenido')
                ->default('
                <h2>OBJETIVO ESPECIFICO</h2><br>
                <h2>METODOLOGIA</h2><br>
                <h2>EVALUACION</h2><br>
                <h2>INFORMACION DE RELATOR</h2><br>
                <h2>TEMARIO</h2><br>')
                    ->required()
                    ->columnSpan(2),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\TextColumn::make('title')->label('Módulo'),
                Tables\Columns\TextColumn::make('description')->label('Descripción')->html(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                ->label('Crear Módulo')
                ->modalHeading('Nuevo módulo'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
