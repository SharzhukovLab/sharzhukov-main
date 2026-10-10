<x-layouts.public title="News — Sharzhukov's Laboratory">
    <section style="max-width:1120px;margin:0 auto;padding:80px 24px 40px;">
        <span class="lab-eyebrow">// News</span>
        <h1 style="margin:12px 0 8px;">Lab notes</h1>
        <p style="color:var(--lab-fg-muted);">Updates, releases, and experiments.</p>
    </section>

    <section style="max-width:1120px;margin:0 auto;padding:0 24px 80px;">
        @if($news->isEmpty())
            <x-lab::card style="text-align:center;padding:60px;">
                <p style="color:var(--lab-fg-muted);margin:0;">No posts yet.</p>
            </x-lab::card>
        @else
            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(320px,1fr));gap:20px;">
                @foreach($news as $item)
                    <a href="{{ route('news.show', $item) }}" style="text-decoration:none;color:inherit;">
                        <x-lab::card hoverable>
                            @if($item->cover_image_url)
                                <img src="{{ $item->cover_image_url }}" style="width:100%;aspect-ratio:16/9;object-fit:cover;border-radius:8px;margin-bottom:14px;">
                            @endif
                            <div style="display:flex;gap:8px;margin-bottom:10px;">
                                <x-lab::badge variant="primary">{{ $item->category }}</x-lab::badge>
                                @if($item->is_pinned)
                                    <x-lab::badge variant="accent">Pinned</x-lab::badge>
                                @endif
                            </div>
                            <h3 style="margin:0 0 8px;font-size:17px;">{{ $item->title }}</h3>
                            <p style="color:var(--lab-fg-muted);font-size:14px;margin:0 0 12px;">{{ $item->excerpt }}</p>
                            <span class="lab-eyebrow">{{ $item->published_at?->format('M d, Y') }}</span>
                        </x-lab::card>
                    </a>
                @endforeach
            </div>
            <div style="margin-top:32px;">{{ $news->links() }}</div>
        @endif
    </section>
</x-layouts.public>
