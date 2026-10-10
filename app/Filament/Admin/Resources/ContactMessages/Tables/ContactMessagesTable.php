<?php

namespace App\Filament\Admin\Resources\ContactMessages\Tables;

use App\Models\ContactMessage;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ContactMessagesTable
{
    public static function configure(Table $table): Table
    {
        $catLabels = [];
        foreach (ContactMessage::categories() as $k => $v) {
            $catLabels[$k] = $v['label'];
        }

        return $table
            ->columns([
                TextColumn::make('id')->label('#')->sortable()->width('60px'),
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('email')->searchable()->copyable()->limit(30),
                TextColumn::make('subject')->searchable()->limit(50),
                TextColumn::make('category')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $catLabels[$state] ?? $state)
                    ->sortable(),
                SelectColumn::make('status')
                    ->options([
                        'new'      => 'New',
                        'read'     => 'Read',
                        'replied'  => 'Replied',
                        'archived' => 'Archived',
                    ])
                    ->sortable(),
                TextColumn::make('created_at')->since()->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')->options([
                    'new'      => 'New',
                    'read'     => 'Read',
                    'replied'  => 'Replied',
                    'archived' => 'Archived',
                ])->default('new'),
                SelectFilter::make('category')->options($catLabels),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
