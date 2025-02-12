<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InvitacionResource\Pages;
use App\Filament\Resources\InvitacionResource\RelationManagers;
use App\Models\Followup;
use App\Models\Invitacion;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Validation\Rule;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Facades\Http;

class InvitacionResource extends Resource
{
    protected static ?string $model = Invitacion::class;

    protected static ?string $navigationIcon = 'heroicon-s-envelope-open';
    protected static ?string $navigationGroup = 'Operaciones';
    protected static ?string $navigationLabel = 'Invitaciones';
    public static ?string $pluralModelLabel = 'Invitaciones';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('follow_id')
                    ->label('Ref. Cotización')
                    ->relationship(
                        name: 'followup',
                        modifyQueryUsing: function (Builder $query) {

                            $query->whereNotIn('id', function ($subquery) {
                                $subquery->select('follow_id')
                                    ->from('invitacions')
                                    ->whereNotNull('follow_id');
                            });
                        }
                    )
                    ->getOptionLabelFromRecordUsing(fn($record) => $record?->referent ?? 'Sin referencia')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->afterStateUpdated(function ($state, Forms\Set $set, ?string $operation) {
                        $referent = \App\Models\Followup::find($state)?->referent;
                        $set('n_cotizacion', $referent);
                    }),

                Forms\Components\Hidden::make('n_cotizacion')
                    ->required(),
                Forms\Components\TextInput::make('link_clases')
                    ->label('Link clases (Microsoft Teams / Google Meet)')
                    ->maxLength(255),
                Forms\Components\TextInput::make('link_moodle')
                    ->maxLength(255),
                Forms\Components\TextInput::make('password')
                    ->default('Proyecta-2024 (La primera letra en mayúscula).')
                    ->label('Contraseña')
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('n_cotizacion')
                    ->label('Ref. Cotización')
                    ->searchable(),
                Tables\Columns\TextColumn::make('link_clases')
                    ->label('Clases')
                    ->formatStateUsing(fn(string $state): string => strlen($state) > 20 ? substr($state, 0, 20) . '...' : $state) // Mostrar solo los primeros 20 caracteres
                    ->searchable()
                    ->copyable()
                    ->icon('heroicon-o-link')
                    ->iconColor('primary'),
                Tables\Columns\TextColumn::make('link_moodle')
                    ->label('Moodle')
                    ->formatStateUsing(fn(string $state): string => strlen($state) > 20 ? substr($state, 0, 20) . '...' : $state) // Mostrar solo los primeros 30 caracteres
                    ->searchable()
                    ->copyable()
                    ->icon('heroicon-o-link')
                    ->iconColor('primary'),
                Tables\Columns\TextColumn::make('date_execution')
                    ->label('Últ. Ejec.')
                    ->dateTime()
                    ->sortable(),
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
                Tables\Actions\EditAction::make(),
                Action::make('enviar')
                    ->label('Enviar')
                    ->icon('heroicon-o-paper-airplane')
                    ->color('success')
                    ->tooltip('Envío manual')
                    ->url(fn($record) => route('enviar.invitaciones', $record->id))
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
            RelationManagers\LogInvitacionRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInvitacions::route('/'),
            'create' => Pages\CreateInvitacion::route('/create'),
            'edit' => Pages\EditInvitacion::route('/{record}/edit'),
        ];
    }
}
