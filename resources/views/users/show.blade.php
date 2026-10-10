<x-layouts.public :title="$user->name . ' — Sharzhukov\'s Laboratory'">
    <section class="brutal-grid-bg" style="padding:60px 24px 40px;border-bottom:3px solid var(--brutal-border);">
        <div style="max-width:820px;margin:0 auto;display:flex;gap:24px;align-items:center;flex-wrap:wrap;">
            <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}"
                 style="width:96px;height:96px;border-radius:0;object-fit:cover;border:3px solid var(--brutal-border);box-shadow:4px 4px 0 var(--brutal-shadow);flex-shrink:0;">
            <div style="min-width:0;flex:1;">
                <span class="brutal-marker">// USER</span>
                <h1 class="brutal-hero-title" style="margin:12px 0 6px;font-size:clamp(1.75rem,3.5vw,2.5rem);">{{ $user->name }}</h1>
                <p style="font-family:var(--font-mono);font-size:13px;color:var(--lab-fg-muted);margin:0;letter-spacing:0.05em;">
                    &#64;{{ $user->username }}
                    @if($user->team_role) · {{ $user->team_role }} @endif
                </p>
                @if($user->bio)
                    <p style="margin:14px 0 0;color:var(--lab-fg);line-height:1.6;max-width:600px;">{{ $user->bio }}</p>
                @endif
            </div>
        </div>
    </section>

    <article style="max-width:820px;margin:0 auto;padding:48px 24px 80px;">
        @if($projects->isNotEmpty())
            <h2 style="margin:0 0 20px;font-family:var(--font-sans);font-weight:900;font-size:22px;letter-spacing:-0.02em;">Projects</h2>
            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:16px;">
                @foreach($projects as $project)
                    <a href="{{ route('projects.show', $project) }}" style="text-decoration:none;color:inherit;">
                        <div style="padding:18px;border:2px solid var(--brutal-border);background:var(--lab-bg-elevated);box-shadow:4px 4px 0 var(--brutal-shadow);transition:all .15s ease;"
                             onmouseover="this.style.transform='translate(-3px,-3px)';this.style.boxShadow='7px 7px 0 var(--brutal-shadow)';"
                             onmouseout="this.style.transform='';this.style.boxShadow='4px 4px 0 var(--brutal-shadow)';">
                            <h3 style="margin:0 0 6px;font-size:16px;font-family:var(--font-sans);font-weight:900;">{{ $project->title }}</h3>
                            @if($project->tagline)
                                <p style="color:var(--lab-fg-muted);font-size:14px;margin:0;line-height:1.5;">{{ $project->tagline }}</p>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <p style="color:var(--lab-fg-muted);">No approved projects yet.</p>
        @endif
    </article>
</x-layouts.public>
