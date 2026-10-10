<?php

namespace App\Filament\Admin\Resources\WikiPages\Pages;

use App\Filament\Admin\Resources\WikiPages\WikiPageResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListWikiPages extends ListRecords
{
    protected static string $resource = WikiPageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
