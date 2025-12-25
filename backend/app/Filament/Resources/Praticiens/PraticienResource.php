<?php

namespace App\Filament\Resources\Praticiens;

use App\Filament\Resources\Praticiens\Pages\CreatePraticien;
use App\Filament\Resources\Praticiens\Pages\EditPraticien;
use App\Filament\Resources\Praticiens\Pages\ListPraticiens;
use App\Filament\Resources\Praticiens\Pages\ViewPraticien;
use App\Filament\Resources\Praticiens\Schemas\PraticienForm;
use App\Filament\Resources\Praticiens\Schemas\PraticienInfolist;
use App\Filament\Resources\Praticiens\Tables\PraticiensTable;
use App\Models\Praticien;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PraticienResource extends Resource
{
    protected static ?string $model = Praticien::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-user-group';

    protected static string|BackedEnum|null $navigationGroup = 'Gestion médicale';

    protected static ?string $navigationLabel = 'Praticiens';

    protected static ?string $pluralLabel = 'Praticiens';

    protected static ?string $recordTitleAttribute = 'user.nom_complet';

    public static function form(Schema $schema): Schema
    {
        return PraticienForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PraticienInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PraticiensTable::configure($table);
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
            'index' => ListPraticiens::route('/'),
            'create' => CreatePraticien::route('/create'),
            'view' => ViewPraticien::route('/{record}'),
            'edit' => EditPraticien::route('/{record}/edit'),
        ];
    }
}
