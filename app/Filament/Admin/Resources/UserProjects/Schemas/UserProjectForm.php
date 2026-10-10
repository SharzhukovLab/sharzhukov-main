<?php

namespace App\Filament\Admin\Resources\UserProjects\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class UserProjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Project')
                    ->schema([
                        Select::make('user_id')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        TextInput::make('title')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),

                        TextInput::make('tagline')
                            ->maxLength(255),

                        RichEditor::make('description')
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

                Section::make('Links')
                    ->schema([
                        TextInput::make('github_url')->url()->maxLength(500),
                        TextInput::make('demo_url')->url()->maxLength(500),
                        TextInput::make('download_url')->url()->maxLength(500),
                        TextInput::make('external_url')->url()->maxLength(500),
                    ])
                    ->columns(2),

                Section::make('Media')
                    ->schema([
                        FileUpload::make('cover_image_path')
                            ->label('Cover')
                            ->image()
                            ->disk('public')
                            ->directory('projects/covers')
                            ->maxSize(4096),

                        FileUpload::make('gallery')
                            ->image()
                            ->multiple()
                            ->reorderable()
                            ->maxFiles(8)
                            ->disk('public')
                            ->directory('projects/galleries')
                            ->maxSize(4096)
                            ->columnSpanFull(),
                    ]),

                Section::make('Metadata')
                    ->schema([
                        TagsInput::make('tech_stack')
                            
                            ->placeholder('Type and press Enter'),

                        TextInput::make('license')
                            ->maxLength(80)
                            ->placeholder('MIT, GPL, Apache 2.0...'),

                        Select::make('status_label')
                            ->options([
                                'Stable' => 'Stable',
                                'Beta' => 'Beta',
                                'Alpha' => 'Alpha',
                                'WIP' => 'WIP',
                                'Deprecated' => 'Deprecated',
                            ]),
                    ])
                    ->columns(3),

                Section::make('Moderation')
                    ->schema([
                        Select::make('status')
                            ->options([
                                'draft' => 'Draft',
                                'pending' => 'Pending review',
                                'approved' => 'Approved',
                                'rejected' => 'Rejected',
                                'archived' => 'Archived',
                            ])
                            ->required()
                            ->default('pending'),

                        Textarea::make('moderation_note')
                            ->rows(3)
                            ->columnSpanFull(),

                        Toggle::make('is_featured'),
                        TextInput::make('order')->numeric()->default(0),
                    ])
                    ->columns(3),
            ]);
    }
}