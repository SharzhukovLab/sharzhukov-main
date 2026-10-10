<?php

namespace App\Filament\Admin\Resources\UserProjects\Pages;

use App\Filament\Admin\Resources\UserProjects\UserProjectResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUserProject extends CreateRecord
{
    protected static string $resource = UserProjectResource::class;
}
