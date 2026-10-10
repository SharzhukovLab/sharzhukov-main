<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WikiRevision extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'page_id', 'editor_id', 'title', 'content', 'note', 'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function page(): BelongsTo
    {
        return $this->belongsTo(WikiPage::class, 'page_id');
    }

    public function editor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'editor_id');
    }

    /**
     * Откатить страницу к этой ревизии.
     */
    public function restore(): void
    {
        if (!$this->page) {
            return;
        }

        // Сначала сохраним текущее состояние как ревизию
        WikiRevision::create([
            'page_id' => $this->page_id,
            'editor_id' => auth()->id(),
            'title' => $this->page->title,
            'content' => $this->page->content,
            'note' => 'Auto-save before restore to revision #' . $this->id,
            'created_at' => now(),
        ]);

        // Затем применяем ревизию
        $this->page->update([
            'title' => $this->title ?? $this->page->title,
            'content' => $this->content,
        ]);
    }
}
