<?php

namespace App\Models;


use App\Models\Concerns\Reactable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class News extends Model
{
    use Reactable;

    protected $table = 'news';

    protected $fillable = [
        'title',
        'slug',
        'category',
        'excerpt',
        'body',
        'gradient',
        'cover_image_path',
        'related_repo',
        'external_url',
        'is_published',
        'is_pinned',
        'published_at',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'is_pinned'    => 'boolean',
        'published_at' => 'datetime',
    ];

    /**
     * Слаг генерируется автоматически из title.
     */
    protected static function booted(): void
    {
        static::creating(function (self $news) {
            if (empty($news->slug)) {
                $news->slug = Str::slug($news->title) . '-' . Str::random(4);
            }
            if (empty($news->published_at)) {
                $news->published_at = now();
            }
        });
    }

    /**
     * Опубликованные, отсортированные по дате (pinned сверху).
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true)
                     ->orderByDesc('is_pinned')
                     ->orderByDesc('published_at');
    }

    /**
     * Последние N новостей для слайдера.
     */
    public function scopeLatestForSlider($query, int $limit = 5)
    {
        return $query->published()->limit($limit);
    }

    public function getCoverUrlAttribute(): ?string
    {
        return $this->cover_image_path ? asset('storage/' . $this->cover_image_path) : null;
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Комментарии к новости.
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Только одобренные комментарии (для публичного рендера).
     */
    public function approvedComments()
    {
        return $this->hasMany(Comment::class)->where('status', 'approved');
    }
}
