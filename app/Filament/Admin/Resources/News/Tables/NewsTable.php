<?php

namespace App\Filament\Admin\Resources\News\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class NewsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('published_at', 'desc')
            ->columns([
                ImageColumn::make('cover_image_path')
                    ->label('')
                    ->disk('public')
                    ->height(40)
                    ->width(60)
                    ->defaultImageUrl(fn () => null),

                TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->limit(60),

                TextColumn::make('category')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'release' => 'success',
                        'update' => 'info',
                        'guide' => 'warning',
                        'announcement' => 'primary',
                        default => 'gray',
                    }),

                ToggleColumn::make('is_published')
                    ->label('Live'),

                IconColumn::make('is_pinned')
                    ->label('Pinned')
                    ->boolean(),

                TextColumn::make('published_at')
                    ->dateTime('M d, Y H:i')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('category')
                    ->options([
                        'update' => 'Update',
                        'release' => 'Release',
                        'guide' => 'Guide',
                        'announcement' => 'Announcement',
                    ]),

                TernaryFilter::make('is_published')
                    ->label('Published'),

                TernaryFilter::make('is_pinned')
                    ->label('Pinned'),
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