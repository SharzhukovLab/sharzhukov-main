<?php

namespace App\Filament\Admin\Resources\WikiTags;

use App\Filament\Admin\Resources\WikiTags\Pages\CreateWikiTag;
use App\Filament\Admin\Resources\WikiTags\Pages\EditWikiTag;
use App\Filament\Admin\Resources\WikiTags\Pages\ListWikiTags;
use App\Filament\Admin\Resources\WikiTags\Schemas\WikiTagForm;
use App\Filament\Admin\Resources\WikiTags\Tables\WikiTagsTable;
use App\Models\WikiTag;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class WikiTagResource extends Resource
{
    protected static ?string $model = WikiTag::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTag;

    protected static ?string $navigationLabel = 'Tags';
    protected static ?string $modelLabel = 'Tag';
    protected static ?string $pluralModelLabel = 'Tags';
    protected static string|\UnitEnum|null $navigationGroup = 'Wiki';
    protected static ?int $navigationSort = 30;

    public static function form(Schema $schema): Schema
    {
        return WikiTagForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return WikiTagsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListWikiTags::route('/'),
            'create' => CreateWikiTag::route('/create'),
            'edit' => EditWikiTag::route('/{record}/edit'),
        ];
    }
}