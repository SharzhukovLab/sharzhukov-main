<?php

namespace App\Notifications;

use App\Models\UserProject;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ProjectPublishedNotification extends Notification
{
    use Queueable;

    public function __construct(public UserProject $project) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type'       => 'project_published',
            'title'      => 'Your project is live!',
            'message'    => "Project «{$this->project->title}» is now published in the Wiki and News.",
            // ВАЖНО: абсолютный URL — projects.show существует ТОЛЬКО на main
            'url'        => "https://sharzhukov.com/projects/{$this->project->slug}",
            'project_id' => $this->project->id,
        ];
    }
}