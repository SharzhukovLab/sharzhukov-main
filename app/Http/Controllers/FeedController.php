<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FeedController extends Controller
{
    public function index(Request $request): View
    {
        $me = $request->user();

        // IDs: те, на кого я подписан + я сам
        $followingIds = $me->following()->pluck('users.id')->all();
        $feedUserIds  = array_unique(array_merge($followingIds, [$me->id]));

        $activities = Activity::query()
            ->whereIn('user_id', $feedUserIds)
            ->with([
                'actor:id,name,username,avatar_path,avatar_url_external',
                'subject',
            ])
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->cursorPaginate(30);

        return view('feed', [
            'activities'   => $activities,
            'followingCnt' => count($followingIds),
        ]);
    }
}
