<?php

namespace App\Observers;

use App\Models\Activity;
use App\Models\WikiPage;

class WikiPageObserver
{
    /**
     * Пишем активность ТОЛЬКО в updated при переходе draft→published.
     * Не в created — потому что WikiPage автоматически создаётся
     * из UserProjectObserver'а и мы не хотим дублировать active.
     */
    public function updated(WikiPage $page): void
    {
        if (! $page->wasChanged('status')) return;
        if ($page->status !== 'published') return;

        $authorId = $page->author_id ?? $page->user_id ?? null;
        if (! $authorId) return;

        $author = \App\Models\User::find($authorId);
        if (! $author) return;

        Activity::record(
            $author,
            'wiki_published',
            $page,
            [
                'title' => $page->title,
                'slug'  => $page->slug,
            ]
        );
    }
}
