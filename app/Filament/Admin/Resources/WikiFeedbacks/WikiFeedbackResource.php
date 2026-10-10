<?php

namespace App\Filament\Admin\Resources\WikiFeedbacks;

use App\Filament\Admin\Resources\WikiFeedbacks\Pages\ListWikiFeedbacks;
use App\Models\WikiFeedback;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class WikiFeedbackResource extends Resource
{
    protected static ?string $model = WikiFeedback::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChatBubbleLeftRight;
    protected static ?string $navigationLabel = 'Feedback';
    protected static ?string $pluralModelLabel = 'Wiki feedback';
    protected static string|\UnitEnum|null $navigationGroup = 'Wiki';
    protected static ?int $navigationSort = 40;

    public static function getNavigationBadge(): ?string
    {
        $col = 'is_resolved';
        if (\Schema::hasColumn('wiki_feedback', $col)) {
            $n = WikiFeedback::where($col, false)->count();
            return $n > 0 ? (string) $n : null;
        }
        return null;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('#')->sortable()->width('60px'),
                TextColumn::make('page_id')->label('Page')->badge()->sortable(),
                TextColumn::make('type')->badge()->sortable(),
                TextColumn::make('message')->limit(60)->wrap()->toggleable(),
                TextColumn::make('status')->badge()->sortable(),
                TextColumn::make('created_at')->since()->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('type'),
                SelectFilter::make('status'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListWikiFeedbacks::route('/'),
        ];
    }
}
