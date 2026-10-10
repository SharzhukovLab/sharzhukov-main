<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SocialLink extends Model
{
    protected $fillable = [
        'name', 'url', 'description',
        'icon_key', 'custom_icon_path', 'accent',
        'show_in_social_grid', 'show_in_footer', 'show_in_community',
        'order', 'is_published',
    ];

    protected $casts = [
        'show_in_social_grid' => 'boolean',
        'show_in_footer'      => 'boolean',
        'show_in_community'   => 'boolean',
        'is_published'        => 'boolean',
    ];

    public function scopePublished($q)   { return $q->where('is_published', true)->orderBy('order'); }
    public function scopeInGrid($q)      { return $q->published()->where('show_in_social_grid', true); }
    public function scopeInFooter($q)    { return $q->published()->where('show_in_footer', true); }
    public function scopeInCommunity($q) { return $q->published()->where('show_in_community', true); }

    public function getCustomIconUrlAttribute(): ?string
    {
        return $this->custom_icon_path ? asset('storage/' . $this->custom_icon_path) : null;
    }
}
