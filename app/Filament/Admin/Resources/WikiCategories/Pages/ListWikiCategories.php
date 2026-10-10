<?php

namespace App\Filament\Admin\Resources\WikiCategories\Pages;

use App\Filament\Admin\Resources\WikiCategories\WikiCategoryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListWikiCategories extends ListRecords
{
    protected static string $resource = WikiCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
