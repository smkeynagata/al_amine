<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class UserInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Identité')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('prenom')->label('Prénom'),
                                TextEntry::make('name')->label('Nom'),
                                TextEntry::make('sexe')->label('Sexe'),
                                TextEntry::make('date_naissance')
                                    ->date()
                                    ->label('Date de naissance'),
                                TextEntry::make('numero_cni')->label('Numéro CNI'),
                            ]),
                    ]),
                Section::make('Coordonnées')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('email')->label('Email'),
                                TextEntry::make('telephone')->label('Téléphone'),
                                TextEntry::make('adresse')
                                    ->columnSpanFull()
                                    ->placeholder('-'),
                                TextEntry::make('quartier')->placeholder('-'),
                                TextEntry::make('ville'),
                            ]),
                    ]),
                Section::make('Compte')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('role')
                                    ->badge()
                                    ->label('Rôle'),
                                TextEntry::make('statut_compte')
                                    ->badge()
                                    ->label('Statut'),
                                TextEntry::make('created_at')
                                    ->since()
                                    ->label('Créé'),
                                TextEntry::make('updated_at')
                                    ->since()
                                    ->label('Modifié'),
                            ]),
                    ]),
            ]);
    }
}
