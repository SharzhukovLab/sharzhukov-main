<?php

namespace App\Observers;

use App\Models\Activity;
use App\Models\Comment;

class CommentObserver
{
    /** Создан + approved → пишем activity (pending не пишем). */
    public function created(Comment $comment): void
    {
        if ($comment->status !== 'approved') return;
        if (! $comment->user) return;

        $subject = $comment->commentable;
        if (! $subject) return;

        Activity::record(
            $comment->user,
            'comment_created',
            $subject,
            [
                'comment_id' => $comment->id,
                'excerpt'    => mb_substr($comment->body, 0, 160),
                'is_reply'   => $comment->parent_id !== null,
            ]
        );
    }

    /** Обновили status pending→approved вручную модератором. */
    public function updated(Comment $comment): void
    {
        if (! $comment->wasChanged('status')) return;
        if ($comment->status !== 'approved') return;
        if (! $comment->user) return;

        $subject = $comment->commentable;
        if (! $subject) return;

        Activity::record(
            $comment->user,
            'comment_created',
            $subject,
            [
                'comment_id' => $comment->id,
                'excerpt'    => mb_substr($comment->body, 0, 160),
                'is_reply'   => $comment->parent_id !== null,
            ]
        );
    }
}
