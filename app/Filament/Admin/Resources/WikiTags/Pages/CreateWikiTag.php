<?php

namespace App\Filament\Admin\Resources\WikiTags\Pages;

use App\Filament\Admin\Resources\WikiTags\WikiTagResource;
use Filament\Resources\Pages\CreateRecord;

class CreateWikiTag extends CreateRecord
{
    protected static string $resource = WikiTagResource::class;
}
