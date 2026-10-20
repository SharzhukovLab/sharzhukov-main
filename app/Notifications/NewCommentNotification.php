<?php

namespace App\Notifications;

use App\Models\Comment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class NewCommentNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public int $commentId) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        $comment = Comment::with('user', 'commentable')->find($this->commentId);
        if (! $comment) {
            return ['type' => 'new_comment', 'message' => 'Comment deleted', 'url' => null];
        }

        $author  = $comment->user;
        $subject = $comment->commentable;

        return [
            'type'            => 'new_comment',
            'comment_id'      => $comment->id,
            'author_id'       => $author?->id,
            'author_username' => $author?->username,
            'author_name'     => $author?->name,
            'author_avatar'   => $author?->avatar_url,
            'subject_type'    => class_basename($comment->commentable_type),
            'excerpt'         => mb_substr($comment->body, 0, 120),
            'message'         => ($author?->name ?? 'Someone') . ' left a comment',
            'url'             => $this->resolveUrl($comment),
        ];
    }

    protected function resolveUrl(Comment $comment): string
    {
        $subject = $comment->commentable;
        if (! $subject) {
            return 'https://sharzhukov.com/notifications';
        }

        $base = match (class_basename($comment->commentable_type)) {
            'News'        => 'https://sharzhukov.com/news/' . ($subject->slug ?? ''),
            'UserProject' => 'https://sharzhukov.com/projects/' . ($subject->slug ?? ''),
            'WikiPage'    => 'https://wiki.sharzhukov.com/' . ($subject->slug ?? ''),
            default       => 'https://sharzhukov.com/',
        };

        return $base . '#comment-' . $comment->id;
    }
}
