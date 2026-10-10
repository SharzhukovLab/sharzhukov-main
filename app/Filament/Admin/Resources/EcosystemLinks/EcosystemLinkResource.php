<?php

namespace App\Filament\Admin\Resources\EcosystemLinks;

use App\Filament\Admin\Resources\EcosystemLinks\Pages\CreateEcosystemLink;
use App\Filament\Admin\Resources\EcosystemLinks\Pages\EditEcosystemLink;
use App\Filament\Admin\Resources\EcosystemLinks\Pages\ListEcosystemLinks;
use App\Filament\Admin\Resources\EcosystemLinks\Schemas\EcosystemLinkForm;
use App\Filament\Admin\Resources\EcosystemLinks\Tables\EcosystemLinksTable;
use App\Models\EcosystemLink;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class EcosystemLinkResource extends Resource
{
    protected static ?string $model = EcosystemLink::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedLink;
    protected static ?string $navigationLabel = 'Footer links';
    protected static ?string $modelLabel = 'Footer link';
    protected static ?string $pluralModelLabel = 'Footer links';
    protected static string|\UnitEnum|null $navigationGroup = 'Content';
    protected static ?int $navigationSort = 7;

    public static function form(Schema $schema): Schema
    {
        return EcosystemLinkForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EcosystemLinksTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListEcosystemLinks::route('/'),
            'create' => CreateEcosystemLink::route('/create'),
            'edit'   => EditEcosystemLink::route('/{record}/edit'),
        ];
    }
}