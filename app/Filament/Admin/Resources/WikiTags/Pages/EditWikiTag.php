<?php

namespace App\Filament\Admin\Resources\WikiTags\Pages;

use App\Filament\Admin\Resources\WikiTags\WikiTagResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditWikiTag extends EditRecord
{
    protected static string $resource = WikiTagResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
