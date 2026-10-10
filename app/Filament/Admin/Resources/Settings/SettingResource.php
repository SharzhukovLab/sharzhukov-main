<?php

namespace App\Filament\Admin\Resources\Settings;

use App\Filament\Admin\Resources\Settings\Pages\CreateSetting;
use App\Filament\Admin\Resources\Settings\Pages\EditSetting;
use App\Filament\Admin\Resources\Settings\Pages\ListSettings;
use App\Models\Setting;
use BackedEnum;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SettingResource extends Resource
{
    protected static ?string $model = Setting::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;
    protected static ?string $navigationLabel = "Settings";
    protected static ?string $modelLabel = "Setting";
    protected static ?string $pluralModelLabel = "Settings";
    protected static string|\UnitEnum|null $navigationGroup = "Site";
    protected static ?int $navigationSort = 90;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make("key")->required()->maxLength(255)->unique(ignoreRecord: true),
            Textarea::make("value")->rows(8)->columnSpanFull(),
            TextInput::make("group")->default("general")->maxLength(255),
            TextInput::make("label")->maxLength(255),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make("key")->searchable()->sortable()->fontFamily("mono"),
                TextColumn::make("value")->limit(60)->wrap(),
                TextColumn::make("group")->badge()->sortable(),
                TextColumn::make("updated_at")->since()->sortable(),
            ])
            ->defaultSort("key");
    }

    public static function getPages(): array
    {
        return [
            "index"  => ListSettings::route("/"),
            "create" => CreateSetting::route("/create"),
            "edit"   => EditSetting::route("/{record}/edit"),
        ];
    }
}