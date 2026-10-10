<?php

namespace App\Filament\Admin\Resources\WikiCategories\Pages;

use App\Filament\Admin\Resources\WikiCategories\WikiCategoryResource;
use Filament\Resources\Pages\CreateRecord;

class CreateWikiCategory extends CreateRecord
{
    protected static string $resource = WikiCategoryResource::class;
}
