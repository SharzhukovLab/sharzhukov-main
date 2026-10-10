<?php

namespace App\Filament\Admin\Resources\WikiCategories;

use App\Filament\Admin\Resources\WikiCategories\Pages\CreateWikiCategory;
use App\Filament\Admin\Resources\WikiCategories\Pages\EditWikiCategory;
use App\Filament\Admin\Resources\WikiCategories\Pages\ListWikiCategories;
use App\Filament\Admin\Resources\WikiCategories\Schemas\WikiCategoryForm;
use App\Filament\Admin\Resources\WikiCategories\Tables\WikiCategoriesTable;
use App\Models\WikiCategory;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class WikiCategoryResource extends Resource
{
    protected static ?string $model = WikiCategory::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedFolderOpen;

    protected static ?string $navigationLabel = 'Categories';
    protected static ?string $modelLabel = 'Category';
    protected static ?string $pluralModelLabel = 'Categories';
    protected static string|\UnitEnum|null $navigationGroup = 'Wiki';
    protected static ?int $navigationSort = 10;

    public static function form(Schema $schema): Schema
    {
        return WikiCategoryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return WikiCategoriesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListWikiCategories::route('/'),
            'create' => CreateWikiCategory::route('/create'),
            'edit' => EditWikiCategory::route('/{record}/edit'),
        ];
    }
}