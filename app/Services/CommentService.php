<?php

namespace App\Services;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CommentService
{
    /**
     * Порог автопубликации: approved_comments_count >= TRUSTED_THRESHOLD
     * → коммент публикуется сразу, минуя модерацию.
     */
    public const TRUSTED_THRESHOLD = 5;

    /**
     * Создаёт комментарий. Автоматически решает статус:
     *  - модератор → approved
     *  - is_trusted → approved
     *  - approved_comments_count >= TRUSTED_THRESHOLD → approved
     *  - иначе → pending
     */
    public function create(
        User $author,
        Model $subject,
        string $body,
        ?Comment $parent = null
    ): Comment {
        $body = trim($body);
        if ($body === '') {
            throw new \InvalidArgumentException('Comment body is empty');
        }
        if (mb_strlen($body) > 5000) {
            throw new \InvalidArgumentException('Comment body is too long (max 5000 chars)');
        }

        $status = $this->resolveStatus($author);

        // Если это reply — родитель должен принадлежать тому же subject
        if ($parent) {
            if ($parent->commentable_type !== $subject->getMorphClass()
                || (int) $parent->commentable_id !== (int) $subject->getKey()) {
                throw new \InvalidArgumentException('Parent comment belongs to a different subject');
            }
            // Вложенность максимум 1 уровень: reply на reply → прикрепляем к top-level родителю
            if ($parent->parent_id !== null) {
                $parent = $parent->parent()->first() ?? $parent;
            }
        }

        return DB::transaction(function () use ($author, $subject, $body, $parent, $status) {
            $comment = Comment::create([
                'commentable_type' => $subject->getMorphClass(),
                'commentable_id'   => $subject->getKey(),
                'user_id'          => $author->id,
                'parent_id'        => $parent?->id,
                'body'             => $body,
                'status'           => $status,
                'ip_address'       => request()->ip(),
                'user_agent'       => mb_substr((string) request()->userAgent(), 0, 255),
            ]);

            // Автосинк счётчика при авто-approve
            if ($status === 'approved') {
                $this->syncAuthorApprovedCount($author->id);
            }

            return $comment;
        });
    }

    public function approve(Comment $comment, User $moderator): void
    {
        $comment->update([
            'status'        => 'approved',
            'moderated_by'  => $moderator->id,
            'moderated_at'  => now(),
        ]);

        // Инкремент счётчика у автора — на основании реально опубликованных
        $this->syncAuthorApprovedCount($comment->user_id);
    }

    public function reject(Comment $comment, User $moderator, ?string $note = null): void
    {
        $comment->update([
            'status'         => 'rejected',
            'moderation_note'=> $note,
            'moderated_by'   => $moderator->id,
            'moderated_at'   => now(),
        ]);
        $this->syncAuthorApprovedCount($comment->user_id);
    }

    public function markSpam(Comment $comment, User $moderator): void
    {
        $comment->update([
            'status'       => 'spam',
            'moderated_by' => $moderator->id,
            'moderated_at' => now(),
        ]);
        $this->syncAuthorApprovedCount($comment->user_id);
    }

    protected function resolveStatus(User $author): string
    {
        if (method_exists($author, 'isModerator') && $author->isModerator()) {
            return 'approved';
        }
        if (!empty($author->is_trusted)) {
            return 'approved';
        }
        if ((int) ($author->approved_comments_count ?? 0) >= self::TRUSTED_THRESHOLD) {
            return 'approved';
        }
        return 'pending';
    }

    public function syncAuthorApprovedCount(int $userId): void
    {
        $count = Comment::where('user_id', $userId)
            ->where('status', 'approved')
            ->whereNull('deleted_at')
            ->count();

        User::where('id', $userId)->update(['approved_comments_count' => $count]);
    }
}
