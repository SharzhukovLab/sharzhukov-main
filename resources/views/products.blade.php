<x-layouts.public
    title="Products — Sharzhukov's Laboratory"
    description="All open-source products and projects from Sharzhukov's Laboratory — pulled live from GitHub and curated by hand.">

    <section class="brutal-grid-bg" style="padding:60px 24px 40px;border-bottom:3px solid var(--brutal-border);">
        <div style="max-width:1200px;margin:0 auto;">
            <span class="brutal-marker">// PRODUCTS</span>
            <h1 class="brutal-hero-title" style="margin:20px 0 12px;font-size:clamp(2rem,4vw,3.5rem);">
                Open-source products.
            </h1>
            <p style="color:var(--lab-fg-muted);font-size:17px;margin:0;line-height:1.6;max-width:620px;">
                Everything we ship is open source. Live from GitHub and hand-curated — {{ $stats['total'] }} products total.
            </p>

            <div style="display:flex;gap:8px;margin-top:28px;flex-wrap:wrap;">
                <a href="{{ route('products.index', ['source' => 'all']) }}"
                   class="lab-btn lab-btn--sm {{ $source === 'all' ? 'lab-btn--primary' : 'lab-btn--secondary' }}">
                    ALL ({{ $stats['total'] }})
                </a>
                <a href="{{ route('products.index', ['source' => 'github']) }}"
                   class="lab-btn lab-btn--sm {{ $source === 'github' ? 'lab-btn--primary' : 'lab-btn--secondary' }}">
                    FROM GITHUB ({{ $stats['github'] }})
                </a>
                <a href="{{ route('products.index', ['source' => 'manual']) }}"
                   class="lab-btn lab-btn--sm {{ $source === 'manual' ? 'lab-btn--primary' : 'lab-btn--secondary' }}">
                    CURATED ({{ $stats['local'] }})
                </a>
            </div>
        </div>
    </section>

    <article style="max-width:1200px;margin:0 auto;padding:48px 24px 80px;">

        @if($stats['total'] === 0)
            <div style="border:2px solid var(--brutal-border);padding:60px 24px;text-align:center;box-shadow:4px 4px 0 var(--brutal-shadow);">
                <p style="margin:0;color:var(--lab-fg-muted);font-weight:600;">No products yet.</p>
            </div>
        @else

            {{-- CURATED --}}
            @if($localProducts->isNotEmpty())
                <h2 style="margin:0 0 24px;font-family:var(--font-sans);font-weight:900;font-size:24px;letter-spacing:-0.02em;">
                    // CURATED
                </h2>
                <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(320px,1fr));gap:24px;margin-bottom:56px;">
                    @foreach($localProducts as $product)
                        @php
                            $href = $product->url ?: '#';
                            $isExternal = (bool) $product->url;
                        @endphp
                        <a href="{{ $href }}"
                           @if($isExternal) target="_blank" rel="noopener" @endif
                           style="text-decoration:none;color:inherit;">
                            <div style="border:2px solid var(--brutal-border);background:var(--lab-bg-elevated);box-shadow:4px 4px 0 var(--brutal-shadow);padding:24px;height:100%;display:flex;flex-direction:column;gap:12px;transition:all .15s ease;"
                                 onmouseover="this.style.transform='translate(-3px,-3px)';this.style.boxShadow='7px 7px 0 var(--brutal-shadow)';"
                                 onmouseout="this.style.transform='';this.style.boxShadow='4px 4px 0 var(--brutal-shadow)';">

                                <div style="display:flex;justify-content:space-between;align-items:start;gap:12px;">
                                    <h3 style="margin:0;font-family:var(--font-sans);font-weight:900;font-size:18px;letter-spacing:-0.01em;">
                                        {{ $product->name }}
                                    </h3>
                                    <span class="brutal-marker" style="font-size:9px;">{{ strtoupper($product->icon ?? 'CURATED') }}</span>
                                </div>

                                @if($product->tagline)
                                    <p style="margin:0;color:var(--lab-fg);font-size:14px;line-height:1.5;font-weight:600;">
                                        {{ $product->tagline }}
                                    </p>
                                @endif

                                @if($product->description)
                                    <p style="margin:0;color:var(--lab-fg-muted);font-size:13px;line-height:1.55;flex:1;">
                                        {{ \Illuminate\Support\Str::limit($product->description, 160) }}
                                    </p>
                                @endif
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif

            {{-- GITHUB (live) --}}
            @if(!empty($githubRepos))
                <h2 style="margin:0 0 24px;font-family:var(--font-sans);font-weight:900;font-size:24px;letter-spacing:-0.02em;">
                    // FROM GITHUB ({{ count($githubRepos) }})
                </h2>
                <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(320px,1fr));gap:24px;">
                    @foreach($githubRepos as $repo)
                        <a href="{{ $repo['url'] }}" target="_blank" rel="noopener"
                           style="text-decoration:none;color:inherit;">
                            <div style="border:2px solid var(--brutal-border);background:var(--lab-bg-elevated);box-shadow:4px 4px 0 var(--brutal-shadow);padding:24px;height:100%;display:flex;flex-direction:column;gap:12px;transition:all .15s ease;"
                                 onmouseover="this.style.transform='translate(-3px,-3px)';this.style.boxShadow='7px 7px 0 var(--brutal-shadow)';"
                                 onmouseout="this.style.transform='';this.style.boxShadow='4px 4px 0 var(--brutal-shadow)';">

                                <div style="display:flex;justify-content:space-between;align-items:start;gap:12px;">
                                    <h3 style="margin:0;font-family:var(--font-mono);font-weight:900;font-size:16px;letter-spacing:-0.01em;">
                                        {{ $repo['name'] }}
                                    </h3>
                                    <span style="font-family:var(--font-mono);font-size:11px;color:var(--lab-fg-muted);">↗</span>
                                </div>

                                @if($repo['description'])
                                    <p style="margin:0;color:var(--lab-fg-muted);font-size:14px;line-height:1.55;flex:1;">
                                        {{ \Illuminate\Support\Str::limit($repo['description'], 140) }}
                                    </p>
                                @endif

                                <div style="display:flex;gap:14px;flex-wrap:wrap;margin-top:auto;padding-top:12px;border-top:1px dashed var(--brutal-border);font-family:var(--font-mono);font-size:10px;text-transform:uppercase;letter-spacing:0.08em;color:var(--lab-fg-muted);">
                                    @if($repo['language'])<span>● {{ $repo['language'] }}</span>@endif
                                    <span>★ {{ $repo['stars'] }}</span>
                                    <span>⑂ {{ $repo['forks'] }}</span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        @endif

        <div style="margin-top:64px;padding:32px;border:2px solid var(--brutal-border);background:var(--lab-bg-elevated);box-shadow:4px 4px 0 var(--brutal-shadow);display:flex;justify-content:space-between;align-items:center;gap:24px;flex-wrap:wrap;">
            <div>
                <div style="font-family:var(--font-mono);font-size:11px;letter-spacing:0.08em;color:var(--lab-fg-muted);margin-bottom:6px;">// SEE ALL REPOS</div>
                <div style="font-family:var(--font-sans);font-weight:900;font-size:18px;">Want to browse the code?</div>
            </div>
            <a href="https://github.com/{{ config('services.github.org', 'SharzhukovLab') }}"
               target="_blank" rel="noopener" class="lab-btn lab-btn--primary">
                github.com/{{ config('services.github.org', 'SharzhukovLab') }} →
            </a>
        </div>
    </article>
</x-layouts.public>
