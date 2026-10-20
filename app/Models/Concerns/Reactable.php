<?php

namespace App\Models\Concerns;

use App\Models\Reaction;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait Reactable
{
    public function reactions(): MorphMany
    {
        return $this->morphMany(Reaction::class, 'reactable');
    }

    public function reactionCounts(): array
    {
        return Reaction::countsFor($this);
    }

    public function reactionTotal(): int
    {
        return array_sum($this->reactionCounts());
    }

    /** Что текущий юзер поставил: ['like' => true, 'fire' => true, ...] */
    public function userReactions(?int $userId): array
    {
        if (! $userId) {
            return [];
        }
        $types = Reaction::query()
            ->forSubject($this)
            ->where('user_id', $userId)
            ->pluck('type')
            ->all();
        return array_fill_keys($types, true);
    }
}
