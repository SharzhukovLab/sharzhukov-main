<?php

namespace App\Observers;

use App\Models\Activity;
use App\Models\Follow;

class FollowObserver
{
    public function created(Follow $follow): void
    {
        if (! $follow->follower) return;

        $followee = $follow->followee;
        if (! $followee) return;

        Activity::record(
            $follow->follower,
            'user_followed',
            $followee,
            ['followee_username' => $followee->username]
        );
    }
}
