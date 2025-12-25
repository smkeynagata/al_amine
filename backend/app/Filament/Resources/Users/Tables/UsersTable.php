<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('nom_complet')
                    ->label('Utilisateur')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),
                TextColumn::make('role')
                    ->label('Rôle')
                    ->badge()
                    ->sortable()
                    ->formatStateUsing(fn (string $state) => ucfirst(strtolower($state)))
                    ->color(fn (string $state) => match ($state) {
                        'ADMIN' => 'danger',
                        'PRATICIEN' => 'success',
                        'SECRETAIRE' => 'warning',
                        default => 'gray',
                    }),
                BadgeColumn::make('statut_compte')
                    ->label('Statut')
                    ->colors([
                        'success' => 'ACTIF',
                        'warning' => 'SUSPENDU',
                        'danger' => 'DESACTIVE',
                    ])
                    ->sortable(),
                TextColumn::make('telephone')
                    ->label('Téléphone')
                    ->toggleable(),
                TextColumn::make('ville')
                    ->label('Ville')
                    ->toggleable(),
                TextColumn::make('created_at')
                    ->label('Créé le')
                    ->since()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('role')
                    ->label('Rôle')
                    ->options([
                        'ADMIN' => 'Administrateur',
                        'SECRETAIRE' => 'Secrétaire',
                        'PRATICIEN' => 'Praticien',
                        'PATIENT' => 'Patient',
                    ]),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
