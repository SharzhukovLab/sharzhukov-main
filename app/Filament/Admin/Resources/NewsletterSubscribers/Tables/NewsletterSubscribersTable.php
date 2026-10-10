<?php

namespace App\Filament\Admin\Resources\NewsletterSubscribers\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class NewsletterSubscribersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('email')->searchable()->weight('bold')->copyable(),
                TextColumn::make('name')->searchable()->toggleable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'confirmed'    => 'success',
                        'pending'      => 'warning',
                        'unsubscribed' => 'gray',
                        default        => 'gray',
                    }),
                TextColumn::make('source')->toggleable(),
                TextColumn::make('confirmed_at')->dateTime('M d, Y')->toggleable(),
                TextColumn::make('created_at')->dateTime('M d, Y')->sortable()->toggleable(),
            ])
            ->filters([
                SelectFilter::make('status')->options([
                    'pending'      => 'Pending',
                    'confirmed'    => 'Confirmed',
                    'unsubscribed' => 'Unsubscribed',
                ]),
            ])
            ->recordActions([EditAction::make(), DeleteAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }
}