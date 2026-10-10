<x-layouts.public :title="$page->title . ' — Sharzhukov\'s Laboratory'" :description="$page->meta_description">
    <section class="brutal-grid-bg" style="padding:60px 24px 40px;border-bottom:3px solid var(--brutal-border);">
        <div style="max-width:820px;margin:0 auto;">
            @if($page->hero_eyebrow)
                <span class="brutal-marker">{{ $page->hero_eyebrow }}</span>
            @endif
            <h1 class="brutal-hero-title" style="margin:20px 0 12px;font-size:clamp(2rem,4vw,3rem);">{{ $page->hero_title }}</h1>
            @if($page->hero_subtitle)
                <p style="color:var(--lab-fg-muted);font-size:17px;margin:0;line-height:1.6;max-width:600px;">{{ $page->hero_subtitle }}</p>
            @endif
        </div>
    </section>

    <article style="max-width:820px;margin:0 auto;padding:48px 24px 80px;">
        <div class="lab-prose">{!! $page->content !!}</div>

        @if($page->slug === 'about')
            @include('pages._team')
            @include('pages._github')
        @endif
    </article>
</x-layouts.public>
