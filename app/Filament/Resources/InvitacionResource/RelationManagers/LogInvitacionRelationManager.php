<?php

namespace App\Filament\Resources\InvitacionResource\RelationManagers;

use Filament\Tables\Actions\Action;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LogInvitacionRelationManager extends RelationManager
{
    protected static string $relationship = 'LogInvitacion';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Textarea::make('emails')
                ->label('E-Mails')
                ->rows(3) 
                ->extraAttributes(['style' => 'resize: none; white-space: pre-wrap; overflow-wrap: break-word;']) 
                ->disabled() 
                ->dehydrated(false), 
        ])
        ->columns(1);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('Tarea')
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->label('Envío'),
                Tables\Columns\TextColumn::make('count')
                    ->label('# Dtrio')
                    ->tooltip('Número destinatarios'),
                Tables\Columns\TextColumn::make('emails')
                    ->label('E-Mails')
                    ->getStateUsing(function ($record) {
                        return substr(json_encode($record->emails), 0, 20) . '...';
                    }),
                    Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->label('Estado')
                    ->color(fn(string $state): string => match ($state) {
                        'Completado' => 'success', // Verde
                        'Pendiente' => 'warning',  // Amarillo
                        'Cancelado' => 'danger',   // Rojo
                        default => 'secondary',   // Gris para cualquier otro estado
                    }),
                    Tables\Columns\TextColumn::make('created_at')
                    ->label('F. Ejec')

            ])->defaultSort('id', 'desc')
            ->filters([
                //
            ])
            ->headerActions([
                //  Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                // Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),

            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ]);
    }
}
