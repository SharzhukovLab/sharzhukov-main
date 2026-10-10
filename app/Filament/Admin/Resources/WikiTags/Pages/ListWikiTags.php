<?php

namespace App\Filament\Admin\Resources\WikiTags\Pages;

use App\Filament\Admin\Resources\WikiTags\WikiTagResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListWikiTags extends ListRecords
{
    protected static string $resource = WikiTagResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
