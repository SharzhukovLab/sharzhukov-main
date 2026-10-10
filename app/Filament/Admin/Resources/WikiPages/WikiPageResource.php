<?php

namespace App\Filament\Admin\Resources\WikiPages;

use App\Filament\Admin\Resources\WikiPages\Pages\CreateWikiPage;
use App\Filament\Admin\Resources\WikiPages\Pages\EditWikiPage;
use App\Filament\Admin\Resources\WikiPages\Pages\ListWikiPages;
use App\Filament\Admin\Resources\WikiPages\Schemas\WikiPageForm;
use App\Filament\Admin\Resources\WikiPages\Tables\WikiPagesTable;
use App\Models\WikiPage;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class WikiPageResource extends Resource
{
    protected static ?string $model = WikiPage::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    protected static ?string $navigationLabel = 'Pages';
    protected static ?string $modelLabel = 'Page';
    protected static ?string $pluralModelLabel = 'Pages';
    protected static string|\UnitEnum|null $navigationGroup = 'Wiki';
    protected static ?int $navigationSort = 20;

    public static function getNavigationBadge(): ?string
    {
        $count = WikiPage::where('status', 'draft')->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function form(Schema $schema): Schema
    {
        return WikiPageForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return WikiPagesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListWikiPages::route('/'),
            'create' => CreateWikiPage::route('/create'),
            'edit' => EditWikiPage::route('/{record}/edit'),
        ];
    }
}