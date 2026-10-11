<?php

namespace App\Observers;

use App\Models\News;
use App\Models\NewsletterSubscriber;
use App\Models\UserProject;
use App\Models\WikiCategory;
use App\Models\WikiPage;
use App\Notifications\ProjectPublishedNotification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class UserProjectObserver
{
    public function created(UserProject $project): void
    {
        if ($project->status === 'approved') {
            $this->publish($project);
        }
    }

    public function updated(UserProject $project): void
    {
        if ($project->wasChanged('status') && $project->status === 'approved') {
            $this->publish($project);
        }
    }

    protected function publish(UserProject $project): void
    {
        try { $this->createWikiPage($project); }       catch (\Throwable $e) { Log::warning('[observer] wiki failed', ['id'=>$project->id,'err'=>$e->getMessage()]); }
        try { $this->createNews($project); }            catch (\Throwable $e) { Log::warning('[observer] news failed', ['id'=>$project->id,'err'=>$e->getMessage()]); }
        try { $this->notifySubscribers($project); }     catch (\Throwable $e) { Log::warning('[observer] newsletter failed', ['id'=>$project->id,'err'=>$e->getMessage()]); }
        try { $this->sendPersonalMessage($project); }   catch (\Throwable $e) { Log::warning('[observer] notification failed', ['id'=>$project->id,'err'=>$e->getMessage()]); }
    }

    protected function createWikiPage(UserProject $project): void
    {
        $category = WikiCategory::firstOrCreate(
            ['slug' => 'project'],
            ['name' => 'Projects', 'description' => 'Open-source projects', 'is_visible' => true, 'sort_order' => 10]
        );

        $base = Str::slug($project->title);
        $slug = $base;
        $i = 2;
        while (WikiPage::where('slug', $slug)->exists()) { $slug = $base . '-' . $i++; }

        WikiPage::create([
            'category_id'      => $category->id,
            'author_id'        => $project->user_id,
            'title'            => $project->title,
            'slug'             => $slug,
            'excerpt'          => $project->tagline,
            'content'          => $project->description ?? $project->tagline,
            'status'           => 'published',
            'published_at'     => now(),
            'meta_title'       => $project->title . " — Sharzhukov's Laboratory",
            'meta_description' => $project->tagline,
            'views_count'      => 0, 'sort_order' => 0, 'is_pinned' => false,
        ]);
    }

    protected function createNews(UserProject $project): void
    {
        $base = 'project-' . Str::slug($project->title);
        $slug = $base;
        $i = 2;
        while (News::where('slug', $slug)->exists()) { $slug = $base . '-' . $i++; }

        News::create([
            'title'            => 'New project: ' . $project->title,
            'slug'             => $slug,
            'category'         => 'project',
            'excerpt'          => $project->tagline,
            'body'             => $project->description ?? $project->tagline,
            'gradient'         => 'orange',
            'cover_image_path' => $project->cover_image_path,
            'related_repo'     => $project->github_url,
            'external_url'     => $project->demo_url,
            'is_published'     => true,
            'is_pinned'        => false,
            'published_at'     => now(),
        ]);
    }

    protected function notifySubscribers(UserProject $project): void
    {
        $subs = NewsletterSubscriber::where('status', 'confirmed')->get();
        $count = 0;

        foreach ($subs as $sub) {
            try {
                Mail::raw(
                    "New project: {$project->title}\n\n{$project->tagline}\n\nRead more: https://main.sharzhukov.com/projects/{$project->slug}\n",
                    function ($m) use ($sub, $project) {
                        $m->to($sub->email)->subject('New project: ' . $project->title);
                    }
                );
                $count++;
            } catch (\Throwable $e) {
                Log::warning('[observer] mail to subscriber failed', ['email'=>$sub->email,'err'=>$e->getMessage()]);
            }
        }
        Log::info('[observer] newsletter sent', ['project'=>$project->id,'count'=>$count]);
    }

    protected function sendPersonalMessage(UserProject $project): void
    {
        if ($project->user) {
            $project->user->notify(new ProjectPublishedNotification($project));
        }
    }
}
