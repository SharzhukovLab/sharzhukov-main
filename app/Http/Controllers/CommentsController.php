<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\News;
use App\Models\UserProject;
use App\Notifications\NewCommentNotification;
use App\Notifications\NewReplyNotification;
use App\Services\CommentService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class CommentsController extends Controller
{
    public function __construct(protected CommentService $service) {}

    /** Resolve subject (News / UserProject) из типа + id. */
    protected function resolveSubject(string $type, int $id): Model
    {
        $map = [
            'news'    => News::class,
            'project' => UserProject::class,
        ];

        abort_unless(isset($map[$type]), 404);

        return $map[$type]::findOrFail($id);
    }

    /** GET /api/comments/{type}/{id} — список с маркерами удалённых. */
    public function index(Request $request, string $type, int $id)
    {
        $subject     = $this->resolveSubject($type, $id);
        $isModerator = $request->user()?->isModerator() ?? false;

        // Тянем всё, включая soft-deleted (для маркеров)
        $rows = Comment::query()
            ->forSubject($subject)
            ->topLevel()
            ->withTrashed()
            ->with([
                'user:id,name,username,avatar_path,avatar_url_external,is_trusted,role',
                'replies' => function ($q) use ($isModerator) {
                    $q->with('user:id,name,username,avatar_path,avatar_url_external,is_trusted,role')
                      ->orderBy('created_at');
                    if (! $isModerator) {
                        $q->where('status', 'approved');
                    }
                },
            ])
            ->orderByDesc('created_at')
            ->limit(100)
            ->get();

        // PHP-фильтр маркеров:
        //  - живой top-level → показываем (approved для не-модератора)
        //  - marker (trashed) → показываем только если есть живые approved replies
        $visible = $rows->filter(function (Comment $c) use ($isModerator) {
            if ($c->trashed()) {
                return $c->replies->contains(fn (Comment $r) => $r->status === 'approved');
            }
            return $isModerator || $c->status === 'approved';
        })->values();

        // Счётчик = все видимые top-level + все approved replies внутри них
        $count = $visible->sum(function (Comment $c) {
            return 1 + $c->replies->where('status', 'approved')->count();
        });

        $comments = $visible->map(fn (Comment $c) => $this->presentComment($c, $isModerator))->values();

        return response()->json([
            'comments' => $comments,
            'count'    => $count,
        ]);
    }

    /** POST /api/comments — создать комментарий или ответ. */
    public function store(Request $request)
    {
        $data = $request->validate([
            'subject_type' => ['required', 'in:news,project'],
            'subject_id'   => ['required', 'integer', 'min:1'],
            'body'         => ['required', 'string', 'min:2', 'max:5000'],
            'parent_id'    => ['nullable', 'integer', 'exists:comments,id'],
        ]);

        $user = $request->user();

        // Rate limit: 5 комментов / 5 минут
        $key = 'comment:' . $user->id;
        if (RateLimiter::tooManyAttempts($key, 5)) {
            return response()->json([
                'error'       => 'rate_limited',
                'message'     => 'Too many requests. Please wait a couple of minutes.',
                'retry_after' => RateLimiter::availableIn($key),
            ], 429);
        }

        $subject = $this->resolveSubject($data['subject_type'], $data['subject_id']);

        $parent = null;
        if (! empty($data['parent_id'])) {
            $parent = Comment::findOrFail($data['parent_id']);
        }

        try {
            $comment = $this->service->create($user, $subject, $data['body'], $parent);
        } catch (\InvalidArgumentException $e) {
            return response()->json(['error' => 'invalid', 'message' => $e->getMessage()], 422);
        }

        RateLimiter::hit($key, 300);

        if ($comment->isApproved()) {
            $this->dispatchNotifications($comment, $subject, $parent, $user);
        }

        $isModerator = $user->isModerator();

        // Свежий счётчик = сколько видно
        $count = Comment::forSubject($subject)->approved()->count();

        return response()->json([
            'comment' => $this->presentComment($comment->load('user'), $isModerator),
            'status'  => $comment->status,
            'message' => $comment->isApproved()
                ? 'Comment published'
                : 'Comment submitted for moderation',
            'count'   => $count,
        ], 201);
    }

    /** DELETE /api/comments/{comment} — с marker-логикой. */
    public function destroy(Request $request, Comment $comment)
    {
        $user = $request->user();

        $canDelete = $user->id === $comment->user_id || $user->isModerator();
        abort_unless($canDelete, 403);

        if ($comment->trashed()) {
            return response()->json(['ok' => true, 'already_deleted' => true]);
        }

        $subject  = $comment->commentable;
        $authorId = $comment->user_id;
        $parentId = $comment->parent_id;

        // ─── Marker-логика: если есть живые approved replies — не удаляем, а помечаем
        $hasLiveReplies = Comment::query()
            ->where('parent_id', $comment->id)
            ->whereNull('deleted_at')
            ->where('status', 'approved')
            ->exists();

        if ($hasLiveReplies) {
            $comment->body = '';
            $comment->save();
            $comment->delete();
        } else {
            $comment->forceDelete();

            if ($parentId) {
                $parent = Comment::withTrashed()->find($parentId);
                if ($parent && $parent->trashed()) {
                    $parentHasLiveReplies = Comment::query()
                        ->where('parent_id', $parent->id)
                        ->whereNull('deleted_at')
                        ->where('status', 'approved')
                        ->exists();
                    if (! $parentHasLiveReplies) {
                        $parent->forceDelete();
                    }
                }
            }
        }

        $this->service->syncAuthorApprovedCount($authorId);

        $count = $subject
            ? Comment::forSubject($subject)->approved()->count()
            : 0;

        return response()->json(['ok' => true, 'count' => $count]);
    }

    /** POST /api/comments/{comment}/approve */
    public function approve(Request $request, Comment $comment)
    {
        abort_unless($request->user()->isModerator(), 403);

        $this->service->approve($comment, $request->user());

        $subject = $comment->commentable;
        if ($subject) {
            $parent = $comment->parent;
            $this->dispatchNotifications($comment, $subject, $parent, $comment->user);
        }

        return response()->json(['ok' => true, 'status' => 'approved']);
    }

    /** POST /api/comments/{comment}/reject */
    public function reject(Request $request, Comment $comment)
    {
        abort_unless($request->user()->isModerator(), 403);

        $note = $request->input('note');
        $this->service->reject($comment, $request->user(), $note);

        return response()->json(['ok' => true, 'status' => 'rejected']);
    }

    /** POST /api/comments/{comment}/spam */
    public function spam(Request $request, Comment $comment)
    {
        abort_unless($request->user()->isModerator(), 403);

        $this->service->markSpam($comment, $request->user());

        return response()->json(['ok' => true, 'status' => 'spam']);
    }

    // ─── Helpers ───

    protected function presentComment(Comment $c, bool $isModerator): array
    {
        $isDeleted = $c->trashed();

        return [
            'id'            => $c->id,
            'parent_id'     => $c->parent_id,
            'body'          => $isDeleted ? '' : $c->body,
            'status'        => $c->status,
            'is_deleted'    => $isDeleted,
            'created_at'    => $c->created_at?->toIso8601String(),
            'created_human' => $c->created_at?->diffForHumans(),
            'can_delete'    => ! $isDeleted && auth()->check() && (auth()->id() === $c->user_id || $isModerator),
            'user'          => [
                'id'         => $c->user?->id,
                'username'   => $c->user?->username,
                'name'       => $c->user?->name,
                'avatar'     => $c->user?->avatar_url,
                'is_trusted' => (bool) ($c->user?->is_trusted ?? false),
            ],
            'replies'       => $c->relationLoaded('replies')
                ? $c->replies->map(fn (Comment $r) => $this->presentComment($r, $isModerator))->all()
                : [],
        ];
    }

    protected function dispatchNotifications(Comment $comment, Model $subject, ?Comment $parent, $author): void
    {
        if ($parent && $parent->user_id !== $author->id) {
            $parent->user?->notify(new NewReplyNotification($comment->id));
            return;
        }

        $subjectOwnerId = $subject->user_id ?? ($subject->author_id ?? null);
        if ($subjectOwnerId && $subjectOwnerId !== $author->id) {
            \App\Models\User::find($subjectOwnerId)?->notify(new NewCommentNotification($comment->id));
        }
    }
}
