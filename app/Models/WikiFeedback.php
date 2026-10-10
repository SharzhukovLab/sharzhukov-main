<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WikiFeedback extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'wiki_feedback';

    protected $fillable = [
        'page_id', 'user_id', 'is_helpful', 'comment', 'ip', 'user_agent', 'created_at',
    ];

    protected $casts = [
        'is_helpful' => 'boolean',
        'created_at' => 'datetime',
    ];

    public function page(): BelongsTo
    {
        return $this->belongsTo(WikiPage::class, 'page_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopeHelpful($q)
    {
        return $q->where('is_helpful', true);
    }

    public function scopeUnhelpful($q)
    {
        return $q->where('is_helpful', false);
    }
}
