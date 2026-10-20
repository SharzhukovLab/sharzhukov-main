<?php

namespace App\Observers;

use App\Models\Activity;
use App\Models\Reaction;

class ReactionObserver
{
    /** Только «сильные» реакции попадают в ленту (fire/love/rocket). */
    public function created(Reaction $reaction): void
    {
        if ($reaction->type === 'like') return;
        if (! $reaction->user) return;

        $subject = $reaction->reactable;
        if (! $subject) return;

        Activity::record(
            $reaction->user,
            'reaction_added',
            $subject,
            ['reaction' => $reaction->type]
        );
    }
}
