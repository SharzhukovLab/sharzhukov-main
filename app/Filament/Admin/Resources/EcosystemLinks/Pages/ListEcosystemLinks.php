<?php

namespace App\Filament\Admin\Resources\EcosystemLinks\Pages;

use App\Filament\Admin\Resources\EcosystemLinks\EcosystemLinkResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListEcosystemLinks extends ListRecords
{
    protected static string $resource = EcosystemLinkResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
