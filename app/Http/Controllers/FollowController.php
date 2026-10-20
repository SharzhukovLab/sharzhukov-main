<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\NewFollowerNotification;
use Illuminate\Http\Request;

class FollowController extends Controller
{
    public function store(Request $request, User $user)
    {
        $me = $request->user();
        abort_if($me->is($user), 422, 'You cannot follow yourself');

        $created = $me->follow($user);

        if ($created) {
            $user->notify(new NewFollowerNotification($me->id));
        }

        return response()->json([
            'following'       => true,
            'followers_count' => $user->followers()->count(),
        ]);
    }

    public function destroy(Request $request, User $user)
    {
        $request->user()->unfollow($user);

        return response()->json([
            'following'       => false,
            'followers_count' => $user->followers()->count(),
        ]);
    }
}
