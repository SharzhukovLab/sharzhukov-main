<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $fillable = [
        'slug', 'title', 'hero_eyebrow', 'hero_title',
        'hero_subtitle', 'meta_description', 'is_published',
    ];

    protected $casts = ['is_published' => 'boolean'];

    public function blocks()
    {
        return $this->hasMany(PageBlock::class)->where('is_published', true)->orderBy('order');
    }

    public function allBlocks()
    {
        return $this->hasMany(PageBlock::class)->orderBy('order');
    }

    public function getRouteKeyName(): string { return 'slug'; }
}
