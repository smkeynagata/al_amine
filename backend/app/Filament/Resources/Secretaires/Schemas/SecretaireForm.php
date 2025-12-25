<?php

namespace App\Filament\Resources\Secretaires\Schemas;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SecretaireForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Secrétaire')
                    ->columns(2)
                    ->schema([
                        Select::make('user_id')
                            ->label('Utilisateur')
                            ->relationship('user', 'nom_complet')
                            ->searchable()
                            ->preload()
                            ->required(),
                        TextInput::make('matricule')
                            ->label('Matricule')
                            ->maxLength(50)
                            ->helperText('Laissé vide pour générer automatiquement.')
                            ->unique(ignoreRecord: true),
                    ]),
            ]);
    }
}
