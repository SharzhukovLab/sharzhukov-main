<?php

namespace App\Filament\Admin\Resources\EcosystemLinks\Pages;

use App\Filament\Admin\Resources\EcosystemLinks\EcosystemLinkResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditEcosystemLink extends EditRecord
{
    protected static string $resource = EcosystemLinkResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
