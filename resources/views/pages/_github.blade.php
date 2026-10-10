@php
    use App\Services\GithubService;

    $gh    = app(GithubService::class);
    $repos = $gh->repos(6);
    $count = $gh->repoCount();
@endphp

@if(!empty($repos))
    <h2>Open source</h2>
    <p>
        All our code lives on GitHub — {{ $count ?: count($repos) }} public repositories.
        Browse, star, fork, send PRs.
    </p>

    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:16px;margin:32px 0;">
        @foreach($repos as $repo)
            <a href="{{ $repo['url'] }}" target="_blank" rel="noopener"
               style="display:flex;flex-direction:column;gap:8px;padding:18px;border:2px solid var(--brutal-border);background:var(--lab-bg-elevated);box-shadow:4px 4px 0 var(--brutal-shadow);text-decoration:none;color:inherit;transition:all .15s ease;min-height:150px;"
               onmouseover="this.style.transform='translate(-3px,-3px)';this.style.boxShadow='7px 7px 0 var(--brutal-shadow)';"
               onmouseout="this.style.transform='';this.style.boxShadow='4px 4px 0 var(--brutal-shadow)';">
                <div style="display:flex;justify-content:space-between;align-items:start;gap:8px;">
                    <div style="font-family:var(--font-mono);font-size:13px;font-weight:700;color:var(--brutal-border);letter-spacing:-0.01em;">
                        {{ $repo['name'] }}
                    </div>
                    <span style="font-family:var(--font-mono);font-size:10px;color:var(--lab-fg-muted);">↗</span>
                </div>

                @if($repo['description'])
                    <p style="margin:0;font-size:13px;line-height:1.5;color:var(--lab-fg-muted);flex:1;">
                        {{ \Illuminate\Support\Str::limit($repo['description'], 100) }}
                    </p>
                @endif

                <div style="display:flex;gap:14px;margin-top:auto;padding-top:8px;font-family:var(--font-mono);font-size:10px;color:var(--lab-fg-muted);letter-spacing:0.05em;">
                    @if($repo['language'])
                        <span>● {{ $repo['language'] }}</span>
                    @endif
                    <span>★ {{ $repo['stars'] }}</span>
                    <span>⑂ {{ $repo['forks'] }}</span>
                </div>
            </a>
        @endforeach
    </div>

    <p style="margin-top:16px;">
        <a href="https://github.com/{{ config('services.github.org', 'SharzhukovLab') }}"
           target="_blank" rel="noopener" class="lab-btn lab-btn--secondary">
            View all on GitHub →
        </a>
    </p>
@endif
