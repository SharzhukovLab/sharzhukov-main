<?php

namespace App\Filament\Admin\Resources\UserProjects\Pages;

use App\Filament\Admin\Resources\UserProjects\UserProjectResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListUserProjects extends ListRecords
{
    protected static string $resource = UserProjectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
