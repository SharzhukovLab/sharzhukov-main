<?php

namespace App\Filament\Admin\Resources\UserProjects;

use App\Filament\Admin\Resources\UserProjects\Pages\CreateUserProject;
use App\Filament\Admin\Resources\UserProjects\Pages\EditUserProject;
use App\Filament\Admin\Resources\UserProjects\Pages\ListUserProjects;
use App\Filament\Admin\Resources\UserProjects\Schemas\UserProjectForm;
use App\Filament\Admin\Resources\UserProjects\Tables\UserProjectsTable;
use App\Models\UserProject;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class UserProjectResource extends Resource
{
    protected static ?string $model = UserProject::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRocketLaunch;

    protected static ?string $navigationLabel = 'Projects';

    protected static ?string $modelLabel = 'Project';

    protected static ?string $pluralModelLabel = 'Projects';

    protected static string|\UnitEnum|null $navigationGroup = 'Content';

    protected static ?int $navigationSort = 20;

    public static function getNavigationBadge(): ?string
    {
        $count = UserProject::where('status', 'pending')->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function form(Schema $schema): Schema
    {
        return UserProjectForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return UserProjectsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListUserProjects::route('/'),
            'create' => CreateUserProject::route('/create'),
            'edit' => EditUserProject::route('/{record}/edit'),
        ];
    }
}