<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ModalidadesResource\Pages;
use App\Filament\Resources\ModalidadesResource\RelationManagers;
use App\Models\Modalidades;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Section;


class ModalidadesResource extends Resource
{
    protected static ?string $model = Modalidades::class;
    protected static ?string $navigationGroup = 'Configuración';
    
    protected static ?int $navigationSort = 5;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Section::make('Modalidades')
                ->description('')
            ->schema([
                Forms\Components\TextInput::make('name')
                ->label('Modalidad')
                ->required()
                ->maxLength(255),
            ])
            ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                ->label('Modalidades')
                ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListModalidades::route('/'),
            'create' => Pages\CreateModalidades::route('/create'),
            'edit' => Pages\EditModalidades::route('/{record}/edit'),
        ];
    }
}
