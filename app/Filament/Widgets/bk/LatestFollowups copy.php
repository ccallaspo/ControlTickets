<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\FollowupResource;
use App\Models\Followup;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class LatestFollowups extends BaseWidget
{
    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(FollowupResource::getEloquentQuery())
            ->defaultPaginationPageOption(5)
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                ->label('SYC')
                ->searchable(),
            Tables\Columns\TextColumn::make('referent')
                ->label('Ref. Cotización')
                ->searchable(),
            Tables\Columns\TextColumn::make('author')
                ->label('Creado por')
                ->searchable(),
                TextColumn::make('event.name')
                ->label('Estado')
                ->badge()
                ->searchable()
                ->sortable()
                ->icon(
                    fn (Followup $record): ?string =>
                    !empty($record->event->icono) ? $record->event->icono : null
                )
                ->formatStateUsing(fn (string $state): string => match ($state) {
                    'Cotización Aprobada' => 'Cotización Aprobada',
                    'Cotización enviada' => 'Cotización enviada',
                    'Terminado' => 'Terminado',
                    'rejected' => 'Rejected',
                    default => $state,
                })
                ->color(fn (string $state): string => match ($state) {
                    'draft' => 'gray',
                    'Cotización enviada' => 'warning',
                    'Terminado' => 'success',
                    'Cotización Aprobada' => 'success',
                    default => 'gray',
                }),
                Tables\Columns\TextColumn::make('customer.name')
                    ->label('Cliente')
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
            ->actions([
                Tables\Actions\Action::make('Modificar')
                ->label('')
                ->icon('heroicon-s-pencil-square') 
                ->url(fn (Followup $record): string => url('admin/followups/' . $record->id . '/edit')),
            ]);
    }
}
