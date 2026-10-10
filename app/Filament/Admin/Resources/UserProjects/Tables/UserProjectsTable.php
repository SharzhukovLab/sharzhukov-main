<?php

namespace App\Filament\Admin\Resources\UserProjects\Tables;

use App\Models\UserProject;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;

class UserProjectsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                ImageColumn::make('cover_image_path')
                    ->label('')
                    ->disk('public')
                    ->height(40)
                    ->width(60),

                TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->limit(50),

                TextColumn::make('user.name')
                    ->label('Author')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                        'archived' => 'gray',
                        'draft' => 'gray',
                        default => 'gray',
                    }),

                ToggleColumn::make('is_featured')
                    ->label('Featured'),

                TextColumn::make('views_count')
                    ->label('Views')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('created_at')
                    ->dateTime('M d, Y')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                        'archived' => 'Archived',
                    ])
                    ->default('pending'),

                TernaryFilter::make('is_featured')->label('Featured'),
            ])
            ->recordActions([
                Action::make('approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (UserProject $record): bool => $record->status !== 'approved')
                    ->requiresConfirmation()
                    ->action(function (UserProject $record): void {
                        $record->update([
                            'status' => 'approved',
                            'moderated_by' => auth()->id(),
                            'moderated_at' => now(),
                        ]);
                        Notification::make()->title('Project approved')->success()->send();
                    }),

                Action::make('reject')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn (UserProject $record): bool => $record->status !== 'rejected')
                    ->form([
                        \Filament\Forms\Components\Textarea::make('moderation_note')
                            ->required()
                            ->rows(3),
                    ])
                    ->action(function (UserProject $record, array $data): void {
                        $record->update([
                            'status' => 'rejected',
                            'moderation_note' => $data['moderation_note'],
                            'moderated_by' => auth()->id(),
                            'moderated_at' => now(),
                        ]);
                        Notification::make()->title('Project rejected')->danger()->send();
                    }),

                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make('approve_selected')
                        ->label('Approve selected')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(function (Collection $records): void {
                            $records->each->update([
                                'status' => 'approved',
                                'moderated_by' => auth()->id(),
                                'moderated_at' => now(),
                            ]);
                            Notification::make()->title('Projects approved')->success()->send();
                        }),

                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}