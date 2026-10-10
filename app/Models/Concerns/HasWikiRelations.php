<?php

namespace App\Models\Concerns;

use App\Models\WikiFeedback;
use App\Models\WikiPage;
use App\Models\WikiRevision;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasWikiRelations
{
    public function wikiPages(): HasMany
    {
        return $this->hasMany(WikiPage::class, 'author_id');
    }

    public function wikiRevisions(): HasMany
    {
        return $this->hasMany(WikiRevision::class, 'editor_id');
    }

    public function wikiFeedback(): HasMany
    {
        return $this->hasMany(WikiFeedback::class, 'user_id');
    }

    /**
     * Кто может создавать / редактировать страницы wiki.
     * Роли соответствуют main-таблице users: admin | editor | moderator.
     */
    public function canEditWiki(): bool
    {
        return in_array($this->role ?? 'user', ['admin', 'editor', 'moderator'], true);
    }

    public function canModerateWiki(): bool
    {
        return in_array($this->role ?? 'user', ['admin', 'moderator'], true);
    }

    /**
     * Кто вообще видит /admin (Filament wiki).
     */
    public function canAdminWiki(): bool
    {
        return $this->canEditWiki();
    }
}
