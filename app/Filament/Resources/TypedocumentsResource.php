<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TypedocumentsResource\Pages;
use App\Filament\Resources\TypedocumentsResource\RelationManagers;
use App\Models\Typedocuments;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Section;

class TypedocumentsResource extends Resource
{
    protected static ?string $model = Typedocuments::class;

    protected static ?string $navigationIcon = 'heroicon-o-document';
    protected static ?string $navigationGroup = 'Configuración';
    protected static ?string $navigationLabel = 'Tipos de Documentos';
    public static ?string $pluralModelLabel = 'Tipos de Documentos';
    protected static ?int $navigationSort = 6;

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Section::make('Tipos de Documentos')
                ->description('')
            ->schema([
                Forms\Components\TextInput::make('name')
                ->label('Documento')
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
                ->label('Documento')
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
            'index' => Pages\ListTypedocuments::route('/'),
            'create' => Pages\CreateTypedocuments::route('/create'),
            'edit' => Pages\EditTypedocuments::route('/{record}/edit'),
        ];
    }
}
