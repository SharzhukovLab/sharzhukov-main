<?php

namespace App\Filament\Admin\Widgets;

use App\Models\ContactMessage;
use App\Models\News;
use App\Models\User;
use App\Models\UserProject;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class LabStatsOverview extends StatsOverviewWidget
{
    protected static ?int $sort = -10;

    protected function getStats(): array
    {
        $newsTotal     = News::count();
        $newsPublished = News::where('is_published', true)->count();

        $projectsTotal    = UserProject::count();
        $projectsPending  = UserProject::where('status', 'pending')->count();
        $projectsApproved = UserProject::where('status', 'approved')->count();

        $usersTotal  = User::count();
        $usersBanned = User::where('is_banned', true)->count();

        $messagesNew = ContactMessage::where('status', 'new')->count();

        return [
            Stat::make('News', $newsTotal)
                ->description($newsPublished . ' published')
                ->descriptionIcon('heroicon-m-newspaper')
                ->color('primary'),

            Stat::make('Projects', $projectsTotal)
                ->description($projectsPending > 0
                    ? $projectsPending . ' pending review'
                    : $projectsApproved . ' live')
                ->descriptionIcon('heroicon-m-rocket-launch')
                ->color($projectsPending > 0 ? 'warning' : 'success'),

            Stat::make('Users', $usersTotal)
                ->description($usersBanned > 0 ? $usersBanned . ' banned' : 'All active')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary'),

            Stat::make('Messages', $messagesNew)
                ->description($messagesNew > 0 ? 'Awaiting reply' : 'All read')
                ->descriptionIcon('heroicon-m-envelope')
                ->color($messagesNew > 0 ? 'danger' : 'success'),
        ];
    }
}
