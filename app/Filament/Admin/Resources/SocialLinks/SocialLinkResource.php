<?php

namespace App\Filament\Admin\Resources\SocialLinks;

use App\Filament\Admin\Resources\SocialLinks\Pages\CreateSocialLink;
use App\Filament\Admin\Resources\SocialLinks\Pages\EditSocialLink;
use App\Filament\Admin\Resources\SocialLinks\Pages\ListSocialLinks;
use App\Models\SocialLink;
use BackedEnum;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SocialLinkResource extends Resource
{
    protected static ?string $model = SocialLink::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShare;
    protected static ?string $navigationLabel = 'Social links';
    protected static ?string $pluralModelLabel = 'Social links';
    protected static string|\UnitEnum|null $navigationGroup = 'Community';
    protected static ?int $navigationSort = 20;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make()->schema([
                TextInput::make('name')->required()->maxLength(255),
                TextInput::make('url')->url()->required()->maxLength(500),
                TextInput::make('icon_key')->maxLength(100)->helperText('github, discord, telegram, x, reddit'),
                TextInput::make('accent')->maxLength(20)->helperText('HEX цвет, e.g. #ff5c1f'),
                Textarea::make('description')->rows(3)->columnSpanFull(),
                TextInput::make('order')->numeric()->default(0),
                Toggle::make('is_published')->default(true),
                Toggle::make('show_in_social_grid')->default(true),
                Toggle::make('show_in_footer')->default(true),
                Toggle::make('show_in_community')->default(true),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('order')->sortable()->width('60px'),
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('url')->limit(40)->copyable(),
                TextColumn::make('icon_key')->badge(),
                IconColumn::make('is_published')->boolean(),
                IconColumn::make('show_in_footer')->boolean()->label('Footer'),
            ])
            ->defaultSort('order');
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListSocialLinks::route('/'),
            'create' => CreateSocialLink::route('/create'),
            'edit'   => EditSocialLink::route('/{record}/edit'),
        ];
    }
}
