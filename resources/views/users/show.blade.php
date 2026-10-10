<x-layouts.public :title="$user->name . ' — Sharzhukov\'s Laboratory'">
    <section style="max-width:1120px;margin:0 auto;padding:60px 24px;">
        <div style="display:flex;gap:24px;align-items:center;margin-bottom:48px;flex-wrap:wrap;">
            <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" style="width:96px;height:96px;border-radius:50%;object-fit:cover;">
            <div>
                <h1 style="margin:0 0 4px;">{{ $user->name }}</h1>
                <p style="color:var(--lab-fg-muted);margin:0;">@{{ $user->username }}</p>
                @if($user->bio)
                    <p style="margin:12px 0 0;color:var(--lab-fg);">{{ $user->bio }}</p>
                @endif
            </div>
        </div>

        @if($projects->isNotEmpty())
            <h2 style="margin-bottom:20px;">Projects</h2>
            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:16px;">
                @foreach($projects as $project)
                    <a href="{{ route('projects.show', $project) }}" style="text-decoration:none;color:inherit;">
                        <x-lab::card hoverable>
                            <h3 style="margin:0 0 6px;font-size:16px;">{{ $project->title }}</h3>
                            @if($project->tagline)
                                <p style="color:var(--lab-fg-muted);font-size:14px;margin:0;">{{ $project->tagline }}</p>
                            @endif
                        </x-lab::card>
                    </a>
                @endforeach
            </div>
        @endif
    </section>
</x-layouts.public>
