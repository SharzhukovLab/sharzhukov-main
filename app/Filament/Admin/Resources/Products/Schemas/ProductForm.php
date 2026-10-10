<?php

namespace App\Filament\Admin\Resources\Products\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Product')->schema([
                TextInput::make('name')->required()->maxLength(255),
                TextInput::make('tagline')->maxLength(255),
                Textarea::make('description')->rows(3)->columnSpanFull(),
                TextInput::make('url')->url()->maxLength(255)->placeholder('https://...'),
                TextInput::make('icon')->maxLength(64)->default('box')->helperText('Lucide icon name'),
                TextInput::make('sort_order')->numeric()->default(0),
                Toggle::make('is_visible')->default(true),
            ])->columns(2),
        ]);
    }
}