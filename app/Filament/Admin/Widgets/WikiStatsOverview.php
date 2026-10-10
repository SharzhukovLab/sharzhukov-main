<?php

namespace App\Filament\Admin\Widgets;

use App\Models\WikiCategory;
use App\Models\WikiFeedback;
use App\Models\WikiPage;
use App\Models\WikiRevision;
use App\Models\WikiTag;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class WikiStatsOverview extends StatsOverviewWidget
{
    protected static ?int $sort = -10;

    protected function getStats(): array
    {
        $pagesTotal     = WikiPage::count();
        $pagesPublished = WikiPage::where('status', 'published')->count();
        $pagesDraft     = WikiPage::where('status', 'draft')->count();

        $categories = WikiCategory::count();
        $tags       = WikiTag::count();
        $revisions  = WikiRevision::count();
        $feedback   = WikiFeedback::count();

        $totalViews = WikiPage::sum('views_count');

        return [
            Stat::make('Pages', $pagesTotal)
                ->description($pagesPublished . ' published · ' . $pagesDraft . ' drafts')
                ->descriptionIcon('heroicon-m-document-text')
                ->color($pagesDraft > 0 ? 'warning' : 'success'),

            Stat::make('Categories', $categories)
                ->description('Organized structure')
                ->descriptionIcon('heroicon-m-folder-open')
                ->color('primary'),

            Stat::make('Tags', $tags)
                ->description($revisions . ' total revisions')
                ->descriptionIcon('heroicon-m-tag')
                ->color('primary'),

            Stat::make('Total Views', number_format($totalViews))
                ->description($feedback > 0 ? $feedback . ' feedback' : 'No feedback')
                ->descriptionIcon('heroicon-m-eye')
                ->color('success'),
        ];
    }
}
