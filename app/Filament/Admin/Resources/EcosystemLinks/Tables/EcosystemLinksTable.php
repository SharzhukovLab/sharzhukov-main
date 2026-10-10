<?php

namespace App\Filament\Admin\Resources\EcosystemLinks\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class EcosystemLinksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('group')
            ->columns([
                TextColumn::make('group')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'products' => 'primary',
                        'community' => 'success',
                        'resources' => 'warning',
                        'legal' => 'gray',
                        default => 'gray',
                    }),
                TextColumn::make('sort_order')->label('#')->sortable()->width(50),
                TextColumn::make('label')->searchable()->weight('bold'),
                TextColumn::make('url')->limit(50)->fontFamily('mono')->toggleable(),
                IconColumn::make('is_external')->boolean()->label('↗'),
                ToggleColumn::make('is_visible'),
            ])
            ->filters([
                SelectFilter::make('group')->options([
                    'products'  => 'Products',
                    'community' => 'Community',
                    'resources' => 'Resources',
                    'legal'     => 'Legal',
                ]),
            ])
            ->recordActions([EditAction::make(), DeleteAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }
}