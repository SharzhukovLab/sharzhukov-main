<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Activity extends Model
{
    public const UPDATED_AT = null; // только created_at

    protected $fillable = [
        'user_id', 'type',
        'subject_type', 'subject_id',
        'data',
    ];

    protected $casts = [
        'data'       => 'array',
        'created_at' => 'datetime',
    ];

    public function actor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function subject(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Записать событие. Идемпотентно: не плодит дубли одного типа
     * по одному subject от одного актора в течение часа.
     */
    public static function record(
        User $actor,
        string $type,
        ?Model $subject = null,
        array $data = []
    ): ?self {
        // Anti-spam: тот же (user, type, subject) в последний час?
        if ($subject) {
            $exists = static::query()
                ->where('user_id', $actor->id)
                ->where('type', $type)
                ->where('subject_type', $subject->getMorphClass())
                ->where('subject_id', $subject->getKey())
                ->where('created_at', '>', now()->subHour())
                ->exists();
            if ($exists) return null;
        }

        return static::create([
            'user_id'      => $actor->id,
            'type'         => $type,
            'subject_type' => $subject?->getMorphClass(),
            'subject_id'   => $subject?->getKey(),
            'data'         => $data ?: null,
        ]);
    }
}
