<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerResource\Pages;
use App\Filament\Resources\CustomerResource\RelationManagers;
use App\Models\Customer;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static ?string $navigationGroup = 'Configuración';
    protected static ?string $navigationIcon = 'heroicon-c-building-office';
    protected static ?string $navigationLabel = 'Clientes';
    public static ?string $pluralModelLabel = 'Clientes';
    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        $myuser = auth()->user()->name;

        return $form
        ->schema([
            Section::make('Cliente')
                ->description('Maestro de clientes.')
                ->schema([
                    Forms\Components\Hidden::make('active')
                        ->label('Anular')
                        ->default('Si'), 
                    Forms\Components\TextInput::make('rut')
                        ->maxLength(255)
                        ->columnSpan(1),
                    Forms\Components\TextInput::make('name')
                        ->label('Empresa')
                        ->required()
                        ->maxLength(255)
                        ->columnSpan(1),
                    Forms\Components\TextInput::make('represent')
                        ->label('Contacto')
                        ->maxLength(255)
                        ->columnSpan(1),
                    Forms\Components\TextInput::make('phone')
                        ->label('Telefono')
                        ->maxLength(255)
                        ->columnSpan(1),
                    Forms\Components\TextInput::make('email')
                        ->label('Correo Electronico')
                        ->maxLength(255)
                        ->email()
                        ->columnSpan(1),
                    Forms\Components\TextInput::make('address')
                        ->label('Dirección')
                        ->maxLength(255)
                        ->columnSpan(1),
                    Forms\Components\RichEditor::make('description')
                        ->label('Comentarios')
                        ->maxLength(255)
                        ->columnSpan('full'), // Esto hace que ocupe todo el ancho
                    Forms\Components\Hidden::make('author')
                        ->default($myuser),
                ])
                ->columns(2), // Esto establece que el formulario tendrá 2 columnas
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('rut')
                    ->searchable()
                    ->columnSpan(1),
                Tables\Columns\TextColumn::make('name')
                    ->label('Empresa')
                    ->searchable()
                    ->columnSpan(1),
                Tables\Columns\TextColumn::make('represent')
                    ->label('Contacto')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label('Telfono')
                    ->icon('heroicon-s-phone')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Correo')
                    ->icon('heroicon-m-envelope')
                    ->searchable(),
                Tables\Columns\TextColumn::make('author')
                    ->label('Creado por')
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCustomers::route('/'),
            'create' => Pages\CreateCustomer::route('/create'),
            'view' => Pages\ViewCustomer::route('/{record}'),
            'edit' => Pages\EditCustomer::route('/{record}/edit'),
        ];
    }
}
