<?php

namespace App\Filament\Admin\Resources\WikiCategories\Schemas;

use App\Models\WikiCategory;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class WikiCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Category')->schema([
                TextInput::make('name')
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
                    ->helperText('URL: /c/{slug}'),

                Select::make('parent_id')
                    ->label('Parent category')
                    ->options(fn (?WikiCategory $record) => WikiCategory::query()
                        ->when($record, fn ($q) => $q->where('id', '!=', $record->id))
                        ->orderBy('name')
                        ->pluck('name', 'id'))
                    ->searchable()
                    ->placeholder('— Root category —'),

                Textarea::make('description')
                    ->rows(3)
                    ->maxLength(500)
                    ->columnSpanFull(),

                TextInput::make('icon')
                    ->label('Lucide icon')
                    ->placeholder('book-open')
                    ->maxLength(64)
                    ->helperText('Lucide icon name, e.g. "book-open"'),

                TextInput::make('sort_order')
                    ->numeric()
                    ->default(0),

                Toggle::make('is_visible')->default(true),
            ])->columns(2),
        ]);
    }
}