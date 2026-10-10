<?php

namespace App\Filament\Admin\Resources\LinkClicks;

use App\Filament\Admin\Resources\LinkClicks\Pages\ListLinkClicks;
use App\Models\LinkClick;
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
    protected static ?string $pluralModelLabel = 'Clicks';
    protected static string|\UnitEnum|null $navigationGroup = 'Go';
    protected static ?int $navigationSort = 20;

    public static function canCreate(): bool { return false; }
    public static function canEdit($record): bool { return false; }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('#')->sortable()->width('60px'),
                TextColumn::make('short_link_id')->label('Link')->badge()->sortable(),
                TextColumn::make('ip_address')->limit(15)->toggleable(),
                TextColumn::make('referer')->limit(40)->toggleable(),
                TextColumn::make('user_agent')->limit(40)->toggleable()->wrap(),
                TextColumn::make('created_at')->since()->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('short_link_id')
                    ->label('Link')
                    ->options(fn () => \App\Models\ShortLink::pluck('slug', 'id')->toArray())
                    ->searchable(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListLinkClicks::route('/'),
        ];
    }
}
