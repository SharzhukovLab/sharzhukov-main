<?php

namespace App\Filament\Admin\Resources\Faqs\Schemas;

use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class FaqForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('FAQ item')->schema([
                TextInput::make('question')->required()->maxLength(255)->columnSpanFull(),
                RichEditor::make('answer')->required()->columnSpanFull()->toolbarButtons([
                    'bold', 'italic', 'link', 'bulletList', 'orderedList', 'undo', 'redo',
                ]),
                TextInput::make('sort_order')->numeric()->default(0),
                Toggle::make('is_visible')->default(true),
            ])->columns(2),
        ]);
    }
}