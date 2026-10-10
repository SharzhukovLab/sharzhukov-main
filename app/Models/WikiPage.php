<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Laravel\Scout\Searchable;
use League\CommonMark\CommonMarkConverter;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\GithubFlavoredMarkdownExtension;
use League\CommonMark\MarkdownConverter;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class WikiPage extends Model
{
    use HasFactory, HasSlug, Searchable;

    protected $fillable = [
        'category_id', 'parent_id', 'author_id',
        'title', 'slug', 'excerpt', 'content',
        'meta_title', 'meta_description',
        'status', 'views_count', 'sort_order',
        'is_pinned', 'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'is_pinned' => 'boolean',
        'views_count' => 'integer',
        'sort_order' => 'integer',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    // ─── Отношения ───
    public function category(): BelongsTo
    {
        return $this->belongsTo(WikiCategory::class, 'category_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')->orderBy('sort_order');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(WikiTag::class, 'wiki_page_tag', 'page_id', 'tag_id');
    }

    public function revisions(): HasMany
    {
        return $this->hasMany(WikiRevision::class, 'page_id')->latest('created_at');
    }

    public function feedback(): HasMany
    {
        return $this->hasMany(WikiFeedback::class, 'page_id');
    }

    public function related(): BelongsToMany
    {
        return $this->belongsToMany(
            self::class,
            'wiki_related_pages',
            'page_id',
            'related_page_id'
        );
    }

    // ─── Скоупы ───
    public function scopePublished($q)
    {
        return $q->where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function scopeOfCategory($q, $categorySlug)
    {
        return $q->whereHas('category', fn ($c) => $c->where('slug', $categorySlug));
    }

    // ─── Бизнес-логика ───
    public function incrementViews(): void
    {
        $this->increment('views_count');
    }

    public function renderedContent(): string
    {
        $environment = new Environment([
            'html_input' => 'strip',
            'allow_unsafe_links' => false,
        ]);
        $environment->addExtension(new CommonMarkCoreExtension());
        $environment->addExtension(new GithubFlavoredMarkdownExtension());
        $converter = new MarkdownConverter($environment);

        $content = $this->content ?? '';

        // Убираем ВЕДУЩИЙ H1 из markdown — он дублирует <h1> из шаблона
        $content = preg_replace('/\A\s*#\s+.+?(\r?\n|$)/', '', $content, 1);

        return (string) $converter->convert($content);
    }

    /**
     * TOC — массив ['level' => 2|3, 'id' => 'slug', 'title' => 'Original']
     */
    public function getToc(): array
    {
        if (empty($this->content)) {
            return [];
        }

        preg_match_all('/^(#{2,3})\s+(.+?)\s*$/m', $this->content, $m, PREG_SET_ORDER);

        $toc = [];
        $used = [];
        foreach ($m as $row) {
            $level = strlen($row[1]);
            $title = trim($row[2]);
            $base = Str::slug($title) ?: 'section';
            $id = $base;
            $i = 2;
            while (in_array($id, $used, true)) {
                $id = $base . '-' . $i++;
            }
            $used[] = $id;
            $toc[] = ['level' => $level, 'id' => $id, 'title' => $title];
        }

        return $toc;
    }

    /**
     * @return array<int, array{title:string, url:string}>
     */
    public function getBreadcrumbs(): array
    {
        $crumbs = [['title' => 'Wiki', 'url' => route('wiki.index')]];

        if ($this->category) {
            $crumbs[] = [
                'title' => $this->category->name,
                'url' => route('wiki.show', $this->category->slug),
            ];
        }

        // Родительские страницы
        $ancestors = [];
        $p = $this->parent;
        while ($p) {
            array_unshift($ancestors, $p);
            $p = $p->parent;
        }
        foreach ($ancestors as $a) {
            $crumbs[] = ['title' => $a->title, 'url' => route('wiki.show', $a->slug)];
        }

        $crumbs[] = ['title' => $this->title, 'url' => route('wiki.show', $this->slug)];

        return $crumbs;
    }

    public function getUrlAttribute(): string
    {
        return route('wiki.show', $this->slug);
    }

    // ─── Scout ───
    public function toSearchableArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'excerpt' => $this->excerpt,
            'content' => $this->content,
            'status' => $this->status,
            'category_id' => $this->category_id,
        ];
    }

    public function shouldBeSearchable(): bool
    {
        return $this->status === 'published';
    }
}
