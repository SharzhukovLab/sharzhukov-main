<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\News;
use App\Models\User;
use App\Models\WikiPage;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class UserController extends Controller
{
    public function show(string $username): View
    {
        $user = User::where('username', $username)
            ->withCount(['followers', 'following'])
            ->firstOrFail();

        // 404 для banned / удалённых
        if ($user->is_banned || $user->deletion_requested_at) {
            abort(404);
        }

        $projects = $user->approvedProjects()
            ->orderByDesc('created_at')
            ->limit(12)
            ->get();

        // Счётчики (безопасно — только существующие поля/таблицы)
        $stats = [
            'projects' => $user->approvedProjects()->count(),
            'comments' => (int) ($user->approved_comments_count ?? 0),
            'news'     => 0,
            'wiki'     => 0,
        ];

        if (Schema::hasTable('news') && Schema::hasColumn('news', 'user_id')) {
            $stats['news'] = News::where('user_id', $user->id)->where('status', 'published')->count();
        } elseif (Schema::hasTable('news') && Schema::hasColumn('news', 'author_id')) {
            $stats['news'] = News::where('author_id', $user->id)->where('status', 'published')->count();
        }

        if (Schema::hasTable('wiki_pages') && Schema::hasColumn('wiki_pages', 'user_id')) {
            $stats['wiki'] = WikiPage::where('user_id', $user->id)->where('status', 'published')->count();
        } elseif (Schema::hasTable('wiki_pages') && Schema::hasColumn('wiki_pages', 'author_id')) {
            $stats['wiki'] = WikiPage::where('author_id', $user->id)->where('status', 'published')->count();
        }

        $canonicalUrl = route('users.show', $user->username);

        $isFollowing = auth()->check() && auth()->user()->id !== $user->id
            ? auth()->user()->isFollowing($user)
            : false;

        return view('users.show', compact('user', 'projects', 'stats', 'canonicalUrl', 'isFollowing'));
    }
}