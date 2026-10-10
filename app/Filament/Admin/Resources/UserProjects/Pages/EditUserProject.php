<?php

namespace App\Filament\Admin\Resources\UserProjects\Pages;

use App\Filament\Admin\Resources\UserProjects\UserProjectResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditUserProject extends EditRecord
{
    protected static string $resource = UserProjectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
