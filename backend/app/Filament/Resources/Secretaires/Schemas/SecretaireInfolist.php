<?php

namespace App\Filament\Resources\Secretaires\Schemas;

use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class SecretaireInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Profil')
                    ->schema([
                        Grid::make(2)->schema([
                            TextEntry::make('user.nom_complet')
                                ->label('Nom complet'),
                            TextEntry::make('user.email')
                                ->label('Email'),
                            TextEntry::make('user.telephone')
                                ->label('Téléphone'),
                            TextEntry::make('matricule')
                                ->badge(),
                        ]),
                    ]),
                Section::make('Métadonnées')
                    ->schema([
                        Grid::make(2)->schema([
                            TextEntry::make('created_at')
                                ->since()
                                ->label('Créé'),
                            TextEntry::make('updated_at')
                                ->since()
                                ->label('Mis à jour'),
                        ]),
                    ]),
            ]);
    }
}
