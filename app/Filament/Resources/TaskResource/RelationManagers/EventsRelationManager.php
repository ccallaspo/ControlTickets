<?php

namespace App\Filament\Resources\TaskResource\RelationManagers;

use App\Models\Event;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;


use PhpParser\Node\Stmt\Label;

class EventsRelationManager extends RelationManager
{
    
    protected static string $relationship = 'events';
    protected static ?string $title = 'Eventos';
        
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nombre')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('order')
                    ->Label('orden')
                    ->integer(),
                Forms\Components\TextInput::make('icono'),
                Forms\Components\ColorPicker::make('description')
                    ->Label('Color de estado'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nombre'),
                Tables\Columns\IconColumn::make('icono')
                    ->icon(fn ($record): string => $record->icono),
                Tables\Columns\TextColumn::make('order')
                    ->label('Orden'),
                Tables\Columns\TextColumn::make('color')
                    ->label('Color')
                    ->formatStateUsing(fn (Event $record): string => '')
                    ->extraAttributes(fn (Event $record): array => [
                        'style' => "background-color: {$record->description}; color: #fff; padding: 5px; border-radius: 20px;",
                    ]),


            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                ->label('Crear Evento'),
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
