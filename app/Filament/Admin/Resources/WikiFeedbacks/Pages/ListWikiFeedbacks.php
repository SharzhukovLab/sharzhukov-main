<?php

namespace App\Filament\Admin\Resources\WikiFeedbacks\Pages;

use App\Filament\Admin\Resources\WikiFeedbacks\WikiFeedbackResource;
use Filament\Resources\Pages\ListRecords;

class ListWikiFeedbacks extends ListRecords
{
    protected static string $resource = WikiFeedbackResource::class;
}
