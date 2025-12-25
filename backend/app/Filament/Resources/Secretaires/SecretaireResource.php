<?php

namespace App\Filament\Resources\Secretaires;

use App\Filament\Resources\Secretaires\Pages\CreateSecretaire;
use App\Filament\Resources\Secretaires\Pages\EditSecretaire;
use App\Filament\Resources\Secretaires\Pages\ListSecretaires;
use App\Filament\Resources\Secretaires\Pages\ViewSecretaire;
use App\Filament\Resources\Secretaires\Schemas\SecretaireForm;
use App\Filament\Resources\Secretaires\Schemas\SecretaireInfolist;
use App\Filament\Resources\Secretaires\Tables\SecretairesTable;
use App\Models\Secretaire;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SecretaireResource extends Resource
{
    protected static ?string $model = Secretaire::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-clipboard-document-check';

    protected static string|BackedEnum|null $navigationGroup = 'Gestion des utilisateurs';

    protected static ?string $navigationLabel = 'Secrétaires';

    protected static ?string $pluralLabel = 'Secrétaires';

    protected static ?string $recordTitleAttribute = 'user.nom_complet';

    public static function form(Schema $schema): Schema
    {
        return SecretaireForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return SecretaireInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SecretairesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSecretaires::route('/'),
            'create' => CreateSecretaire::route('/create'),
            'view' => ViewSecretaire::route('/{record}'),
            'edit' => EditSecretaire::route('/{record}/edit'),
        ];
    }
}
