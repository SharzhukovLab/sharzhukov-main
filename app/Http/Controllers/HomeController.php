<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\Setting;
use App\Models\ShortLink;
use App\Models\User;
use App\Models\UserProject;
use App\Models\WikiPage;
use App\Services\GithubService;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $projects = UserProject::where('status', 'approved')
            ->orderByDesc('is_featured')
            ->orderBy('order')
            ->limit(3)
            ->get();

        $news = News::where('is_published', true)
            ->orderByDesc('is_pinned')
            ->orderByDesc('published_at')
            ->limit(3)
            ->get();

        $heroBadges = Setting::get('hero.badges', [
            ['text' => '// OPEN SOURCE'],
            ['text' => 'EST. 2026'],
            ['text' => 'EU · GDPR'],
        ]);

        $terminal = [
            'repo_url' => Setting::get('terminal.repo_url', 'git@github.com:SharzhukovLab/lab-ui.git'),
            'prompt'   => Setting::get('terminal.prompt', '~/sharzhukov-lab'),
            'lines'    => Setting::get('terminal.lines', [
                ['type' => 'prompt', 'text' => 'git clone git@github.com:SharzhukovLab/lab-ui.git'],
                ['type' => 'ok',     'text' => 'Cloning into lab-ui...'],
                ['type' => 'ok',     'text' => '342 objects received, 1.2 MiB'],
                ['type' => 'prompt', 'text' => 'cd lab-ui && npm install'],
                ['type' => 'ok',     'text' => 'Ready in 1.2s'],
            ]),
        ];

        // ── Dynamic stats (кэш 10 минут) ──
        $stats = Cache::remember('home.stats.v2', 600, function () {
            $gh = app(GithubService::class);
            $repos = $gh->repos(100);

            $totalStars = collect($repos)->sum('stars');
            $totalForks = collect($repos)->sum('forks');

            return [
                'projects'      => UserProject::where('status', 'approved')->count(),
                'contributors'  => User::whereNotNull('email_verified_at')
                                        ->where('is_banned', false)
                                        ->whereNull('deletion_requested_at')
                                        ->count(),
                'stars'         => $totalStars,
                'forks'         => $totalForks,
                'repos'         => count($repos),
                'wiki_pages'    => WikiPage::count(),
                'short_links'   => ShortLink::count(),
                'news'          => News::where('is_published', true)->count(),
            ];
        });

        return view('home', compact('projects', 'news', 'heroBadges', 'terminal', 'stats'));
    }
}
