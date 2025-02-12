<?php

namespace App\Filament\Resources\FollowupResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class NoteRelationManager extends RelationManager
{
    protected static string $relationship = 'note';
    protected static ?string $title = 'Notas';
    public function form(Form $form): Form
    {
        $myuser = auth()->user()->name;

        return $form
            ->schema([
                Forms\Components\RichEditor::make('note')
                ->fileAttachmentsDisk('public') 
                ->fileAttachmentsDirectory('agenda/images') 
                ->fileAttachmentsVisibility('public')
                ->label('Comentarios')
                ->required()
                ->validationMessages([
                    'required' => 'El campo es obligatorio', 
                ])
                ->columnSpan('full'),                    

                Forms\Components\Hidden::make('author')
                    ->default($myuser),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('note')
            ->columns([
                Tables\Columns\TextColumn::make('note')
                    ->label('Comentarios')
                    ->html(),
                Tables\Columns\TextColumn::make('author')
                    ->searchable()
                    ->label('Creado por'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->label('F. Creacion'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                ->label('Crear Nota')
                ->modalHeading('Nueva nota'),
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
