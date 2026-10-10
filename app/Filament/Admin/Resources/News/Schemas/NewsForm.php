<?php

namespace App\Filament\Admin\Resources\News\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class NewsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Content')
                    ->schema([
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
                            ->helperText('Used in the URL: /news/{slug}'),

                        Select::make('category')
                            ->options([
                                'update' => 'Update',
                                'release' => 'Release',
                                'guide' => 'Guide',
                                'announcement' => 'Announcement',
                            ])
                            ->default('update')
                            ->required(),

                        Textarea::make('excerpt')
                            ->required()
                            ->rows(3)
                            ->maxLength(500)
                            ->columnSpanFull(),

                        RichEditor::make('body')
                            ->columnSpanFull()
                            ->toolbarButtons([
                                'bold', 'italic', 'underline', 'strike',
                                'h2', 'h3',
                                'bulletList', 'orderedList',
                                'blockquote', 'codeBlock',
                                'link', 'attachFiles',
                                'undo', 'redo',
                            ]),
                    ])
                    ->columns(2),

                Section::make('Appearance')
                    ->schema([
                        Select::make('gradient')
                            ->options([
                                'orange' => 'Orange',
                                'blue' => 'Blue',
                                'purple' => 'Purple',
                                'green' => 'Green',
                                'pink' => 'Pink',
                                'slate' => 'Slate',
                            ])
                            ->default('orange')
                            ->required(),

                        FileUpload::make('cover_image_path')
                            ->label('Cover image')
                            ->image()
                            ->disk('public')
                            ->directory('news/covers')
                            ->maxSize(4096)
                            ->imageEditor(),
                    ])
                    ->columns(2),

                Section::make('Links')
                    ->schema([
                        TextInput::make('related_repo')
                            ->label('Related repo')
                            ->maxLength(255)
                            ->placeholder('github.com/user/repo'),

                        TextInput::make('external_url')
                            ->label('External URL')
                            ->url()
                            ->maxLength(255)
                            ->placeholder('https://...'),
                    ])
                    ->columns(2),

                Section::make('Publishing')
                    ->schema([
                        Toggle::make('is_published')
                            ->default(true),

                        Toggle::make('is_pinned')
                            ->default(false),

                        DateTimePicker::make('published_at')
                            ->default(now()),
                    ])
                    ->columns(3),
            ]);
    }
}