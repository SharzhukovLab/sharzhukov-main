<?php

namespace App\Filament\Admin\Resources\WikiPages\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class WikiPageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Content')->schema([
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
                    ->helperText('URL: /p/{slug}'),

                Textarea::make('excerpt')
                    ->rows(2)
                    ->maxLength(500)
                    ->columnSpanFull(),

                RichEditor::make('content')
                    ->columnSpanFull()
                    ->toolbarButtons([
                        'bold', 'italic', 'underline', 'strike',
                        'h2', 'h3',
                        'bulletList', 'orderedList',
                        'blockquote', 'codeBlock',
                        'link', 'attachFiles',
                        'undo', 'redo',
                    ]),
            ])->columns(2),

            Section::make('Organization')->schema([
                Select::make('category_id')
                    ->label('Category')
                    ->relationship('category', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),

                Select::make('author_id')
                    ->label('Author')
                    ->relationship('author', 'name')
                    ->searchable()
                    ->preload(),

                Select::make('tags')
                    ->relationship('tags', 'name')
                    ->multiple()
                    ->searchable()
                    ->preload(),

                TextInput::make('sort_order')->numeric()->default(0),

                Toggle::make('is_pinned')->default(false),

                DateTimePicker::make('published_at')->default(now()),
            ])->columns(3),

            Section::make('SEO')->schema([
                TextInput::make('meta_title')->maxLength(255),
                Textarea::make('meta_description')->rows(2)->maxLength(500),
            ])->columns(2),

            Section::make('Status')->schema([
                Select::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'published' => 'Published',
                        'archived' => 'Archived',
                    ])
                    ->default('draft')
                    ->required(),
            ]),
        ]);
    }
}