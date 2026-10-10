<?php

namespace App\Filament\Admin\Resources\LinkClicks;

use App\Filament\Admin\Resources\LinkClicks\Pages\ListLinkClicks;
use App\Models\LinkClick;
use App\Models\ShortLink;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class LinkClickResource extends Resource
{
    protected static ?string $model = LinkClick::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCursorArrowRays;
    protected static ?string $navigationLabel = 'Clicks';
    protected static ?string $modelLabel = 'Click';
    protected static ?string $pluralModelLabel = 'Clicks';
    protected static string|\UnitEnum|null $navigationGroup = 'Go';
    protected static ?int $navigationSort = 20;

    public static function canCreate(): bool { return false; }
    public static function canEdit($record): bool { return false; }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('#')
                    ->sortable()
                    ->width('70px'),

                TextColumn::make('shortLink.slug')
                    ->label('Link')
                    ->badge()
                    ->searchable()
                    ->sortable(),

                TextColumn::make('ip')
                    ->label('IP')
                    ->limit(15)
                    ->fontFamily('mono')
                    ->toggleable(),

                TextColumn::make('country')
                    ->badge()
                    ->color('gray')
                    ->sortable()
                    ->placeholder('—'),

                TextColumn::make('device')
                    ->badge()
                    ->color('info')
                    ->placeholder('—'),

                TextColumn::make('browser')
                    ->badge()
                    ->placeholder('—'),

                TextColumn::make('os')
                    ->badge()
                    ->placeholder('—'),

                TextColumn::make('referrer')
                    ->limit(40)
                    ->toggleable()
                    ->wrap()
                    ->placeholder('—'),

                TextColumn::make('user_agent')
                    ->limit(50)
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->wrap()
                    ->placeholder('—'),

                TextColumn::make('clicked_at')
                    ->label('Clicked')
                    ->since()
                    ->sortable(),
            ])
            ->defaultSort('clicked_at', 'desc')
            ->filters([
                SelectFilter::make('short_link_id')
                    ->label('Link')
                    ->options(fn () => ShortLink::query()
                        ->limit(100)
                        ->pluck('slug', 'id')
                        ->toArray()),
                SelectFilter::make('country')
                    ->options(fn () => LinkClick::query()
                        ->whereNotNull('country')
                        ->distinct()
                        ->pluck('country', 'country')
                        ->toArray()),
                SelectFilter::make('device')
                    ->options(fn () => LinkClick::query()
                        ->whereNotNull('device')
                        ->distinct()
                        ->pluck('device', 'device')
                        ->toArray()),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListLinkClicks::route('/'),
        ];
    }
}
