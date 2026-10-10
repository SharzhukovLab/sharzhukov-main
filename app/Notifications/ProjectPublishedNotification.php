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
            'title'      => 'Ваш проект опубликован!',
            'message'    => "Проект «{$this->project->title}» теперь доступен в Wiki и новостях.",
            'url'        => "/projects/{$this->project->slug}",
            'project_id' => $this->project->id,
        ];
    }
}
