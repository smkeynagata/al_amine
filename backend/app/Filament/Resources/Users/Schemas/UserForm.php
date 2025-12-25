<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informations personnelles')
                    ->columns(2)
                    ->schema([
                        TextInput::make('prenom')
                            ->label('Prénom')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('name')
                            ->label('Nom')
                            ->required()
                            ->maxLength(255),
                        Select::make('sexe')
                            ->options([
                                'M' => 'Masculin',
                                'F' => 'Féminin',
                            ])
                            ->required()
                            ->native(false),
                        DatePicker::make('date_naissance')
                            ->label('Date de naissance')
                            ->required(),
                        TextInput::make('numero_cni')
                            ->label('Numéro CNI')
                            ->required()
                            ->maxLength(13)
                            ->unique(ignoreRecord: true),
                        TextInput::make('telephone')
                            ->tel()
                            ->label('Téléphone')
                            ->required()
                            ->maxLength(20),
                        TextInput::make('email')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true),
                        TextInput::make('password')
                            ->password()
                            ->label('Mot de passe')
                            ->maxLength(255)
                            ->revealable()
                            ->required(fn (string $operation): bool => $operation === 'create')
                            ->dehydrateStateUsing(fn (?string $state): ?string => filled($state) ? Hash::make($state) : null)
                            ->dehydrated(fn (?string $state): bool => filled($state)),
                    ]),
                Section::make('Adresse')
                    ->columns(2)
                    ->schema([
                        Textarea::make('adresse')
                            ->label('Adresse postale')
                            ->rows(2)
                            ->columnSpanFull(),
                        TextInput::make('quartier')
                            ->maxLength(255),
                        TextInput::make('ville')
                            ->maxLength(255)
                            ->default('Dakar')
                            ->required(),
                    ]),
                Section::make('Compte')
                    ->columns(2)
                    ->schema([
                        Select::make('role')
                            ->options([
                                'ADMIN' => 'Administrateur',
                                'SECRETAIRE' => 'Secrétaire',
                                'PRATICIEN' => 'Praticien',
                                'PATIENT' => 'Patient',
                            ])
                            ->native(false)
                            ->required(),
                        Select::make('statut_compte')
                            ->label('Statut du compte')
                            ->options([
                                'ACTIF' => 'Actif',
                                'SUSPENDU' => 'Suspendu',
                                'DESACTIVE' => 'Désactivé',
                            ])
                            ->native(false)
                            ->required(),
                    ]),
                Section::make('Informations patient')
                    ->relationship('patient')
                    ->columns(2)
                    ->schema([
                        TextInput::make('numero_securite_sociale')
                            ->label('Numéro de sécurité sociale')
                            ->maxLength(30),
                        TextInput::make('mutuelle')
                            ->maxLength(255),
                        Textarea::make('allergies')
                            ->rows(2)
                            ->columnSpanFull(),
                        Textarea::make('antecedents')
                            ->rows(2)
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->visible(fn (Get $get): bool => $get('role') === 'PATIENT'),
                Section::make('Informations praticien')
                    ->relationship('praticien')
                    ->columns(2)
                    ->schema([
                        Select::make('service_id')
                            ->label('Service')
                            ->relationship('service', 'nom')
                            ->searchable()
                            ->preload()
                            ->native(false)
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
                            ->label('Années d’expérience'),
                        Textarea::make('biographie')
                            ->rows(3)
                            ->columnSpanFull(),
                        Select::make('specialites')
                            ->label('Spécialités')
                            ->multiple()
                            ->relationship('specialites', 'nom')
                            ->preload()
                            ->searchable()
                            ->columnSpanFull(),
                    ])
                    ->visible(fn (Get $get): bool => $get('role') === 'PRATICIEN'),
                Section::make('Informations secrétaire')
                    ->relationship('secretaire')
                    ->schema([
                        TextInput::make('matricule')
                            ->maxLength(50)
                            ->helperText('Laissiez vide pour générer automatiquement.')
                            ->unique(ignoreRecord: true),
                    ])
                    ->visible(fn (Get $get): bool => $get('role') === 'SECRETAIRE'),
            ]);
    }
}
