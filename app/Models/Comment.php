<?php

namespace App\Models;



use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

#[ObservedBy([\App\Observers\CommentObserver::class])]
class Comment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'commentable_type', 'commentable_id',
        'user_id', 'parent_id',
        'body', 'status',
        'moderation_note', 'moderated_by', 'moderated_at',
        'ip_address', 'user_agent',
    ];

    protected $casts = [
        'moderated_at' => 'datetime',
    ];

    // ─── Relations ───
    public function commentable(): MorphTo
    {
        return $this->morphTo();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')->orderBy('created_at');
    }

    public function moderator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'moderated_by');
    }

    // ─── Scopes ───
    public function scopeApproved(Builder $q): Builder
    {
        return $q->where('status', 'approved');
    }

    public function scopePending(Builder $q): Builder
    {
        return $q->where('status', 'pending');
    }

    public function scopeTopLevel(Builder $q): Builder
    {
        return $q->whereNull('parent_id');
    }

    public function scopeForSubject(Builder $q, Model $subject): Builder
    {
        return $q->where('commentable_type', $subject->getMorphClass())
                 ->where('commentable_id', $subject->getKey());
    }

    // ─── Helpers ───
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isReply(): bool
    {
        return $this->parent_id !== null;
    }
}
