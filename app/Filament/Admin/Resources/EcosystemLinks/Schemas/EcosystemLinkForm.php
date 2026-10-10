<?php

namespace App\Filament\Admin\Resources\EcosystemLinks\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class EcosystemLinkForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Link')->schema([
                Select::make('group')
                    ->options([
                        'products'  => 'Products',
                        'community' => 'Community',
                        'resources' => 'Resources',
                        'legal'     => 'Legal',
                    ])
                    ->required(),

                TextInput::make('label')->required()->maxLength(255),
                TextInput::make('url')->required()->maxLength(500),
                TextInput::make('sort_order')->numeric()->default(0),
                Toggle::make('is_visible')->default(true),
                Toggle::make('is_external')->default(false)->helperText('Открывать в новой вкладке'),
            ])->columns(2),
        ]);
    }
}