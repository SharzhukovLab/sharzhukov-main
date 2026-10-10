<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class WikiTag extends Model
{
    use HasFactory, HasSlug;

    protected $fillable = ['name', 'slug', 'color'];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function pages(): BelongsToMany
    {
        return $this->belongsToMany(WikiPage::class, 'wiki_page_tag', 'tag_id', 'page_id');
    }

    public function scopeOrdered($q)
    {
        return $q->orderBy('name');
    }

    public function getUrlAttribute(): string
    {
        return route('wiki.tag', $this->slug);
    }
}
