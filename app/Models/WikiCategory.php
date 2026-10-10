<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class WikiCategory extends Model
{
    use HasFactory, HasSlug;

    protected $fillable = [
        'parent_id', 'name', 'slug', 'description',
        'icon', 'sort_order', 'is_visible',
    ];

    protected $casts = [
        'is_visible' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    // ─── Отношения ───
    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')->orderBy('sort_order');
    }

    public function pages(): HasMany
    {
        return $this->hasMany(WikiPage::class, 'category_id');
    }

    public function publishedPages(): HasMany
    {
        return $this->pages()->where('status', 'published')
            ->orderBy('sort_order')
            ->orderBy('title');
    }

    // ─── Скоупы ───
    public function scopeVisible($q)
    {
        return $q->where('is_visible', true);
    }

    public function scopeOrdered($q)
    {
        return $q->orderBy('sort_order')->orderBy('name');
    }

    public function scopeRoot($q)
    {
        return $q->whereNull('parent_id');
    }

    // ─── Утилиты ───
    public static function tree(): array
    {
        return static::visible()
            ->root()
            ->ordered()
            ->with(['children' => fn ($q) => $q->visible()->ordered()])
            ->get()
            ->toArray();
    }

    public function getUrlAttribute(): string
    {
        return route('wiki.category', $this);
    }
}
