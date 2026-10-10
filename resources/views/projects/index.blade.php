<x-layouts.public title="Projects — Sharzhukov's Laboratory">
    <section style="max-width:1120px;margin:0 auto;padding:80px 24px 40px;">
        <span class="lab-eyebrow">// Projects</span>
        <h1 style="margin:12px 0 8px;">Open-source work</h1>
        <p style="color:var(--lab-fg-muted);max-width:560px;">Tools, experiments, and libraries built by the lab and the community.</p>
    </section>

    <section style="max-width:1120px;margin:0 auto;padding:0 24px 80px;">
        @if($projects->isEmpty())
            <x-lab::card style="text-align:center;padding:60px;">
                <p style="color:var(--lab-fg-muted);margin:0;">No projects yet. Check back soon.</p>
            </x-lab::card>
        @else
            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(320px,1fr));gap:20px;">
                @foreach($projects as $project)
                    <a href="{{ route('projects.show', $project) }}" style="text-decoration:none;color:inherit;">
                        <x-lab::card hoverable>
                            @if($project->cover_image_url)
                                <img src="{{ $project->cover_image_url }}" style="width:100%;aspect-ratio:16/9;object-fit:cover;border-radius:8px;margin-bottom:14px;">
                            @endif
                            <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:12px;margin-bottom:8px;">
                                <h3 style="margin:0;font-size:17px;">{{ $project->title }}</h3>
                                @if($project->is_featured)
                                    <x-lab::badge variant="accent">Featured</x-lab::badge>
                                @endif
                            </div>
                            @if($project->tagline)
                                <p style="color:var(--lab-fg-muted);font-size:14px;margin:0 0 12px;">{{ $project->tagline }}</p>
                            @endif
                            @if($project->tech_stack)
                                <div style="display:flex;gap:6px;flex-wrap:wrap;">
                                    @foreach(array_slice($project->tech_stack, 0, 4) as $tech)
                                        <x-lab::badge>{{ $tech }}</x-lab::badge>
                                    @endforeach
                                </div>
                            @endif
                        </x-lab::card>
                    </a>
                @endforeach
            </div>
            <div style="margin-top:32px;">{{ $projects->links() }}</div>
        @endif
    </section>
</x-layouts.public>
