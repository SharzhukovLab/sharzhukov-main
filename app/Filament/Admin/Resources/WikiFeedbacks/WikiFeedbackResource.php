<?php

namespace App\Filament\Admin\Resources\WikiFeedbacks;

use App\Filament\Admin\Resources\WikiFeedbacks\Pages\ListWikiFeedbacks;
use App\Models\WikiFeedback;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class WikiFeedbackResource extends Resource
{
    protected static ?string $model = WikiFeedback::class;

    protected static ?string $slug = 'wiki-feedbacks';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChatBubbleLeftRight;
    protected static ?string $navigationLabel = 'Feedback';
    protected static ?string $modelLabel = 'Feedback';
    protected static ?string $pluralModelLabel = 'Wiki feedback';
    protected static string|\UnitEnum|null $navigationGroup = 'Wiki';
    protected static ?int $navigationSort = 40;

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

                TextColumn::make('page.title')
                    ->label('Page')
                    ->searchable()
                    ->limit(40)
                    ->placeholder('—')
                    ->url(fn ($record) => $record->page_id
                        ? 'https://wiki-main.sharzhukov.com/p/' . ($record->page->slug ?? '')
                        : null)
                    ->openUrlInNewTab(),

                TextColumn::make('user.name')
                    ->label('User')
                    ->placeholder('— anonymous —')
                    ->toggleable(),

                IconColumn::make('is_helpful')
                    ->label('Helpful')
                    ->boolean()
                    ->trueIcon('heroicon-o-hand-thumb-up')
                    ->falseIcon('heroicon-o-hand-thumb-down')
                    ->trueColor('success')
                    ->falseColor('danger'),

                TextColumn::make('comment')
                    ->limit(80)
                    ->wrap()
                    ->placeholder('— no comment —'),

                TextColumn::make('ip')
                    ->label('IP')
                    ->limit(15)
                    ->fontFamily('mono')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->since()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                TernaryFilter::make('is_helpful')
                    ->label('Helpful')
                    ->trueLabel('Only helpful')
                    ->falseLabel('Only not helpful'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListWikiFeedbacks::route('/'),
        ];
    }
}
