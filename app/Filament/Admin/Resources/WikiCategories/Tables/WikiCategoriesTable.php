<?php

namespace App\Filament\Admin\Resources\WikiCategories\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class WikiCategoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('slug')
                    ->prefix('/c/')
                    ->fontFamily('mono')
                    ->toggleable(),

                TextColumn::make('parent.name')
                    ->label('Parent')
                    ->placeholder('— root —')
                    ->toggleable(),

                TextColumn::make('pages_count')
                    ->counts('pages')
                    ->label('Pages')
                    ->badge()
                    ->color('gray'),

                TextColumn::make('sort_order')
                    ->numeric()
                    ->sortable()
                    ->toggleable(),

                ToggleColumn::make('is_visible')
                    ->label('Visible'),
            ])
            ->filters([
                TernaryFilter::make('is_visible')->label('Visible'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}