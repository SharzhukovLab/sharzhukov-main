<x-layouts.public :title="$news->title . ' — Sharzhukov\'s Laboratory'">
    <article style="max-width:720px;margin:0 auto;padding:60px 24px;">
        <a href="{{ route('news.index') }}" class="lab-eyebrow" style="display:inline-block;margin-bottom:24px;">← All posts</a>

        <div style="display:flex;gap:8px;margin-bottom:16px;">
            <x-lab::badge variant="primary">{{ $news->category }}</x-lab::badge>
            @if($news->is_pinned)
                <x-lab::badge variant="accent">Pinned</x-lab::badge>
            @endif
        </div>

        <h1 style="margin:0 0 12px;">{{ $news->title }}</h1>
        <p class="lab-eyebrow" style="margin-bottom:32px;">{{ $news->published_at?->format('F d, Y') }}</p>

        @if($news->cover_image_url)
            <img src="{{ $news->cover_image_url }}" style="width:100%;border-radius:14px;margin-bottom:32px;">
        @endif

        <div class="lab-prose">{!! $news->body !!}</div>

        @if($news->external_url)
            <div style="margin-top:40px;">
                <x-lab::button variant="secondary" href="{{ $news->external_url }}" target="_blank">Read more ↗</x-lab::button>
            </div>
        @endif

    
    <div style="max-width:720px;margin:0 auto;padding:0 24px;">
        <x-lab::reactions :subject="$news" subject-type="news" variant="bar" />
    </div>
<x-lab::comments :subject="$news" subject-type="news" />
    </article>
</x-layouts.public>
