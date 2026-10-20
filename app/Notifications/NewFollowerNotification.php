<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class NewFollowerNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public int $followerId) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        $follower = User::find($this->followerId);

        return [
            'type'              => 'new_follower',
            'follower_id'       => $this->followerId,
            'follower_username' => $follower?->username,
            'follower_name'     => $follower?->name,
            'follower_avatar'   => $follower?->avatar_url,
            'message'           => ($follower?->name ?? 'Someone') . ' started following you',
            'url'               => 'https://sharzhukov.com/u/' . ($follower?->username ?? ''),
        ];
    }
}
