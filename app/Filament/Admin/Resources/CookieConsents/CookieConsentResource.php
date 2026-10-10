<?php

namespace App\Filament\Admin\Resources\CookieConsents;

use App\Filament\Admin\Resources\CookieConsents\Pages\ListCookieConsents;
use App\Models\CookieConsent;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class CookieConsentResource extends Resource
{
    protected static ?string $model = CookieConsent::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShieldCheck;
    protected static ?string $navigationLabel = 'Cookie consents';
    protected static ?string $modelLabel = 'Consent';
    protected static ?string $pluralModelLabel = 'Cookie consents';
    protected static string|\UnitEnum|null $navigationGroup = 'Site';
    protected static ?int $navigationSort = 95;

    public static function canCreate(): bool { return false; }
    public static function canEdit($record): bool { return false; }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('#')->sortable()->width('60px'),
                TextColumn::make('user_id')->label('User')->badge()->sortable(),
                TextColumn::make('action')->badge()->color(fn ($state) => match ($state) {
                    'accept_all' => 'success',
                    'reject_all' => 'danger',
                    default      => 'warning',
                }),
                TextColumn::make('categories')->formatStateUsing(fn ($state) => is_array($state) ? implode(', ', array_keys(array_filter($state))) : '—'),
                TextColumn::make('ip_address')->limit(15)->toggleable(),
                TextColumn::make('accepted_at')->since()->sortable(),
            ])
            ->defaultSort('accepted_at', 'desc')
            ->filters([
                SelectFilter::make('action')->options([
                    'accept_all' => 'Accept all',
                    'reject_all' => 'Reject all',
                    'custom'     => 'Custom',
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCookieConsents::route('/'),
        ];
    }
}
