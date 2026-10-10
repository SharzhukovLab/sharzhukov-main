<?php

namespace App\Filament\Admin\Resources\WikiCategories\Pages;

use App\Filament\Admin\Resources\WikiCategories\WikiCategoryResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditWikiCategory extends EditRecord
{
    protected static string $resource = WikiCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
