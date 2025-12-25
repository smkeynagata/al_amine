<?php

namespace App\Filament\Resources\Praticiens\Schemas;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PraticienForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Praticien')
                    ->columns(2)
                    ->schema([
                        Select::make('user_id')
                            ->label('Utilisateur')
                            ->relationship('user', 'nom_complet')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Select::make('service_id')
                            ->label('Service')
                            ->relationship('service', 'nom')
                            ->searchable()
                            ->preload()
                            ->required(),
                        TextInput::make('numero_ordre')
                            ->label('Numéro d’ordre')
                            ->maxLength(100)
                            ->required(),
                        TextInput::make('tarif_consultation')
                            ->numeric()
                            ->label('Tarif consultation (FCFA)')
                            ->required(),
                        TextInput::make('annees_experience')
                            ->numeric()
                            ->minValue(0)
                            ->label('Années d’expérience')
                            ->default(0),
                        Textarea::make('biographie')
                            ->rows(4)
                            ->columnSpanFull(),
                        Select::make('specialites')
                            ->label('Spécialités')
                            ->multiple()
                            ->relationship('specialites', 'nom')
                            ->preload()
                            ->searchable()
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
