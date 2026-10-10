<x-layouts.public :title="$project->title . ' — Sharzhukov\'s Laboratory'">
    <article style="max-width:820px;margin:0 auto;padding:60px 24px;">
        <a href="{{ route('projects.index') }}" class="lab-eyebrow" style="display:inline-block;margin-bottom:24px;">← All projects</a>

        @if($project->cover_image_url)
            <img src="{{ $project->cover_image_url }}" style="width:100%;aspect-ratio:16/9;object-fit:cover;border-radius:14px;margin-bottom:32px;">
        @endif

        <div style="display:flex;gap:8px;margin-bottom:16px;flex-wrap:wrap;">
            @if($project->is_featured)
                <x-lab::badge variant="accent">Featured</x-lab::badge>
            @endif
            @if($project->status_label)
                <x-lab::badge variant="success">{{ $project->status_label }}</x-lab::badge>
            @endif
            @if($project->license)
                <x-lab::badge>{{ $project->license }}</x-lab::badge>
            @endif
        </div>

        <h1 style="margin:0 0 12px;">{{ $project->title }}</h1>
        @if($project->tagline)
            <p style="font-size:18px;color:var(--lab-fg-muted);margin:0 0 24px;">{{ $project->tagline }}</p>
        @endif

        <div style="display:flex;gap:10px;flex-wrap:wrap;margin-bottom:32px;">
            @if($project->github_url)
                <x-lab::button variant="primary" href="{{ $project->github_url }}" target="_blank">GitHub ↗</x-lab::button>
            @endif
            @if($project->demo_url)
                <x-lab::button variant="secondary" href="{{ $project->demo_url }}" target="_blank">Live demo ↗</x-lab::button>
            @endif
            @if($project->download_url)
                <x-lab::button variant="secondary" href="{{ $project->download_url }}">Download</x-lab::button>
            @endif
        </div>

        @if($project->description)
            <div class="lab-prose">{!! $project->description !!}</div>
        @endif

        @if($project->tech_stack)
            <h3 style="margin-top:40px;">Tech stack</h3>
            <div style="display:flex;gap:8px;flex-wrap:wrap;">
                @foreach($project->tech_stack as $tech)
                    <x-lab::badge>{{ $tech }}</x-lab::badge>
                @endforeach
            </div>
        @endif

        @if($project->gallery_urls)
            <h3 style="margin-top:40px;">Gallery</h3>
            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:12px;">
                @foreach($project->gallery_urls as $url)
                    <img src="{{ $url }}" style="width:100%;aspect-ratio:16/10;object-fit:cover;border-radius:10px;">
                @endforeach
            </div>
        @endif
    </article>
</x-layouts.public>
