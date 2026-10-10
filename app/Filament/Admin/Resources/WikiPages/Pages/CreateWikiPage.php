<?php

namespace App\Filament\Admin\Resources\WikiPages\Pages;

use App\Filament\Admin\Resources\WikiPages\WikiPageResource;
use Filament\Resources\Pages\CreateRecord;

class CreateWikiPage extends CreateRecord
{
    protected static string $resource = WikiPageResource::class;
}
