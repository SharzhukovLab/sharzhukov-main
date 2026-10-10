<?php

namespace App\Filament\Admin\Resources\Pages\Schemas;

use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class PageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Meta')->schema([
                TextInput::make('title')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(function ($state, callable $set, string $operation) {
                        if ($operation === 'create') {
                            $set('slug', Str::slug((string) $state));
                        }
                    }),

                TextInput::make('slug')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true)
                    ->helperText('URL: /{slug}'),

                Textarea::make('meta_description')
                    ->rows(2)
                    ->maxLength(255)
                    ->columnSpanFull(),
            ])->columns(2),

            Section::make('Hero')->schema([
                TextInput::make('hero_eyebrow')->maxLength(255),
                TextInput::make('hero_title')->required()->maxLength(255),
                Textarea::make('hero_subtitle')->rows(2)->columnSpanFull(),
            ])->columns(2),

            Section::make('Content')->schema([
                RichEditor::make('content')
                    ->columnSpanFull()
                    ->toolbarButtons([
                        'bold', 'italic', 'underline', 'strike',
                        'h2', 'h3',
                        'bulletList', 'orderedList',
                        'blockquote', 'codeBlock',
                        'link',
                        'undo', 'redo',
                    ]),
            ]),

            Section::make('Status')->schema([
                Toggle::make('is_published')->default(true),
            ]),
        ]);
    }
}