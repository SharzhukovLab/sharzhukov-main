<?php

namespace App\Models;



use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

#[ObservedBy([\App\Observers\ReactionObserver::class])]
class Reaction extends Model
{
    public const TYPES = ['like', 'love', 'fire', 'rocket'];

    protected $fillable = [
        'reactable_type', 'reactable_id', 'user_id', 'type',
    ];

    public function reactable(): MorphTo
    {
        return $this->morphTo();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeForSubject(Builder $q, Model $subject): Builder
    {
        return $q->where('reactable_type', $subject->getMorphClass())
                 ->where('reactable_id', $subject->getKey());
    }

    /** Агрегированные счётчики по типам: ['like' => 3, 'fire' => 1, ...] */
    public static function countsFor(Model $subject): array
    {
        $rows = static::query()
            ->forSubject($subject)
            ->selectRaw('type, COUNT(*) as c')
            ->groupBy('type')
            ->pluck('c', 'type')
            ->all();

        $out = [];
        foreach (self::TYPES as $t) {
            $out[$t] = (int) ($rows[$t] ?? 0);
        }
        return $out;
    }
}
