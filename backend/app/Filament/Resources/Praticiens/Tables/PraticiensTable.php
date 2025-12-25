<?php

namespace App\Filament\Resources\Praticiens\Tables;

use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PraticiensTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('user.nom_complet')
                    ->label('Praticien')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('user.telephone')
                    ->label('Téléphone')
                    ->toggleable(),
                TextColumn::make('service.nom')
                    ->label('Service')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('specialites_count')
                    ->counts('specialites')
                    ->label('Spécialités')
                    ->sortable(),
                TextColumn::make('tarif_consultation')
                    ->label('Tarif (FCFA)')
                    ->numeric()
                    ->sortable()
                    ->formatStateUsing(fn ($state) => number_format($state, 0, ',', ' ')),
                TextColumn::make('annees_experience')
                    ->label('Expérience (ans)')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->since()
                    ->label('Enregistré'),
            ])
            ->filters([
                SelectFilter::make('service')
                    ->relationship('service', 'nom')
                    ->label('Service'),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
