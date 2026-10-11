<x-layouts.public title="Sharzhukov's Laboratory — Open Source Collective" description="An open-source collective building tools for developers. Made in EU.">

    {{-- HERO (2 columns) --}}
    <section class="brutal-grid-bg" style="padding:80px 24px 80px;border-bottom:3px solid var(--brutal-border);">
        <div style="max-width:1200px;margin:0 auto;">
            <div class="brutal-hero-grid">

                {{-- LEFT --}}
                <div style="position:relative;">
                    <div style="display:flex;gap:10px;flex-wrap:wrap;margin-bottom:28px;">
                        @foreach($heroBadges as $idx => $badge)
                            <span class="brutal-badge {{ $idx === 0 ? 'brutal-badge--dark' : ($idx === 1 ? 'brutal-badge--orange' : '') }}">{{ $badge['text'] }}</span>
                        @endforeach
                    </div>

                    <h1 class="brutal-hero-title" style="max-width:700px;">
                        BUILDING TOOLS<br>
                        FOR <span style="background:var(--brutal-accent);color:#fff;padding:0 .12em;box-shadow:6px 6px 0 var(--brutal-shadow);display:inline-block;transform:rotate(-1deg);">DEVELOPERS</span><br>
                        WHO SHIP.
                    </h1>

                    <p style="font-size:18px;color:var(--lab-fg-muted);max-width:520px;margin:32px 0 40px;line-height:1.6;font-weight:500;">
                        A small team crafting open-source software, experimentation, and documentation.
                    </p>

                    <div style="display:flex;gap:16px;flex-wrap:wrap;">
                        <x-lab::button variant="primary" size="lg" href="{{ url('/projects') }}">
                            EXPLORE PROJECTS <x-lucide-arrow-right style="width:16px;height:16px;" />
                        </x-lab::button>
                        <x-lab::button variant="secondary" size="lg" href="https://github.com/SharzhukovLab" target="_blank">
                            <x-lucide-star style="width:16px;height:16px;" fill="currentColor" /> STAR ON GITHUB
                        </x-lab::button>
                    </div>

                    {{-- Doodle arrow --}}
                    <svg class="brutal-doodle" style="position:static !important;display:block;margin:16px 0 0 32px;width:80px;height:80px;pointer-events:none;" viewBox="0 0 80 80" fill="none" stroke="var(--brutal-accent)" stroke-width="4" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M8 8 C 20 40, 38 58, 64 68" />
                        <path d="M50 58 L 66 70 L 54 76" stroke-width="4" />
                    </svg>
                </div>

                {{-- RIGHT: Terminal --}}
                <div style="position:relative;">
                    <span class="brutal-sticker" style="position:absolute;top:-14px;right:20px;z-index:2;">try it now</span>
                    <div class="brutal-terminal">
                        <div class="brutal-terminal-bar">
                            <span class="brutal-terminal-dot r"></span>
                            <span class="brutal-terminal-dot y"></span>
                            <span class="brutal-terminal-dot g"></span>
                            <span class="brutal-terminal-title">~/sharzhukov-lab</span>
                        </div>
                        <div class="brutal-terminal-body">
                            <span class="brutal-terminal-line"><span class="prompt">$</span> git clone git@github.com:SharzhukovLab/lab-ui.git</span>
                            <span class="brutal-terminal-line"><span class="ok">✓</span> <span class="dim">Cloning into 'lab-ui'...</span></span>
                            <span class="brutal-terminal-line"><span class="ok">✓</span> <span class="dim">342 objects received, 1.2 MiB</span></span>
                            <span class="brutal-terminal-line" style="margin-top:10px;"><span class="prompt">$</span> cd lab-ui && npm install</span>
                            <span class="brutal-terminal-line"><span class="ok">✓</span> <span class="dim">Ready in 1.2s</span></span>
                            <span class="brutal-terminal-line" style="margin-top:14px;"><span class="accent">→</span> @if($stats['projects'] > 0){{ $stats['projects'] }} {{ \Illuminate\Support\Str::plural('project', $stats['projects']) }} live @else open source · EU · GDPR @endif</span>
                            <span class="brutal-terminal-line"><span class="accent">→</span> 48 contributors</span>
                            <span class="brutal-terminal-line"><span class="accent">→</span> 100% open source</span>
                            <span class="brutal-terminal-line" style="margin-top:14px;"><span class="prompt">$</span> <span class="brutal-cursor"></span></span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

{{-- TECH STACK --}}
    <section class="brutal-grid-bg" style="padding:80px 24px;border-bottom:3px solid var(--brutal-border);background:var(--lab-bg-elevated);">
        <div style="max-width:1200px;margin:0 auto;">
            <div style="margin-bottom:40px;">
                <span class="brutal-marker">// STACK</span>
                <h2 style="font-family:var(--font-sans);font-weight:900;font-size:clamp(2rem,4vw,2.75rem);letter-spacing:-0.03em;margin:16px 0 8px;line-height:1;">
                    Built with <x-lucide-wrench style="width:32px;height:32px;display:inline-block;vertical-align:middle;color:var(--brutal-accent);" />
                </h2>
                <p style="color:var(--lab-fg-muted);font-size:16px;margin:0;font-weight:500;max-width:520px;">Tools we use to ship fast and stay boring.</p>
            </div>
            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:16px;">
                @foreach(['Laravel','PHP 8.4','MySQL','Alpine.js','Vite','Tailwind v4','Filament 4','Livewire','Redis','Docker','GitHub Actions','Cloudflare'] as $tech)
                    <div class="brutal-card" style="padding:18px 20px;box-shadow:4px 4px 0 var(--brutal-shadow);">
                        <div style="display:flex;align-items:center;gap:12px;">
                            <x-lucide-box style="width:20px;height:20px;flex-shrink:0;color:var(--brutal-accent);" />
                            <span style="font-family:var(--font-mono);font-size:13px;font-weight:700;">{{ $tech }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- PRINCIPLES --}}
    <section class="brutal-grid-bg" style="padding:80px 24px;border-bottom:3px solid var(--brutal-border);">
        <div style="max-width:1200px;margin:0 auto;">
            <div style="margin-bottom:40px;">
                <span class="brutal-marker">// MANIFESTO</span>
                <h2 style="font-family:var(--font-sans);font-weight:900;font-size:clamp(2rem,4vw,2.75rem);letter-spacing:-0.03em;margin:16px 0 8px;line-height:1;">
                    How we work
                </h2>
            </div>
            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:24px;">
                @php
                    $principles = [
                        ['icon' => 'code-2', 'title' => 'Open by default', 'text' => 'Everything we build is open source. No exceptions, no lock-in.'],
                        ['icon' => 'zap', 'title' => 'Ship fast', 'text' => 'Small commits, small PRs. Weekly releases, not yearly.'],
                        ['icon' => 'shield-check', 'title' => 'Privacy first', 'text' => 'GDPR, EU servers, no tracking. Your data is yours.'],
                        ['icon' => 'users', 'title' => 'Community driven', 'text' => 'Built by developers, for developers. Everyone welcome.'],
                    ];
                @endphp
                @foreach($principles as $idx => $p)
                    <div class="brutal-card" style="padding:28px;">
                        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
                            <x-dynamic-component :component="'lucide-' . $p['icon']" style="width:32px;height:32px;color:var(--brutal-accent);" />
                            <span style="font-family:var(--font-mono);font-size:11px;font-weight:700;opacity:.4;">0{{ $idx + 1 }}</span>
                        </div>
                        <h3 style="margin:0 0 10px;font-size:18px;font-weight:800;letter-spacing:-0.02em;">{{ $p['title'] }}</h3>
                        <p style="color:var(--lab-fg-muted);font-size:14px;margin:0;line-height:1.55;font-weight:500;">{{ $p['text'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
{{-- FEATURED PROJECTS --}}
    <section class="brutal-grid-bg" style="padding:80px 24px;border-bottom:3px solid var(--brutal-border);">
        <div style="max-width:1200px;margin:0 auto;">
            <div style="display:flex;justify-content:space-between;align-items:flex-end;flex-wrap:wrap;gap:16px;margin-bottom:40px;">
                <div>
                    <span class="brutal-marker">// 01 · FEATURED</span>
                    <h2 style="font-family:var(--font-sans);font-weight:900;font-size:clamp(2rem,4vw,2.75rem);letter-spacing:-0.03em;margin:16px 0 0;line-height:1;">
                        Our best work <x-lucide-sparkles class="brutal-spark" style="width:32px;height:32px;display:inline-block;vertical-align:middle;color:var(--brutal-accent);" />
                    </h2>
                </div>
                <a href="{{ url('/projects') }}" class="lab-btn lab-btn--secondary">ALL PROJECTS <x-lucide-arrow-right style="width:14px;height:14px;" /></a>
            </div>

            @if($projects->isEmpty())
                <div class="brutal-card" style="text-align:center;padding:60px;">
                    <p style="color:var(--lab-fg-muted);margin:0;font-weight:600;">No projects yet.</p>
                </div>
            @else
                <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(320px,1fr));gap:28px;">
                    @foreach($projects as $project)
                        <a href="{{ route('projects.show', $project) }}" class="brutal-card {{ $loop->even ? 'tilt-r' : 'tilt-l' }}">
                            @if($project->cover_image_url)
                                <img src="{{ $project->cover_image_url }}" style="width:100%;aspect-ratio:16/9;object-fit:cover;border:2px solid var(--brutal-border);margin-bottom:18px;">
                            @endif
                            <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:12px;margin-bottom:12px;">
                                <span class="brutal-badge">PRJ-{{ str_pad($project->id, 3, '0', STR_PAD_LEFT) }}</span>
                                @if($project->is_featured)
                                    <span class="brutal-badge brutal-badge--orange">FEATURED</span>
                                @endif
                            </div>
                            <h3 style="margin:0 0 10px;font-size:20px;font-weight:800;letter-spacing:-0.02em;line-height:1.2;">{{ $project->title }}</h3>
                            @if($project->tagline)
                                <p style="color:var(--lab-fg-muted);font-size:14px;margin:0;line-height:1.55;font-weight:500;">{{ $project->tagline }}</p>
                            @endif
                            <div style="display:flex;justify-content:space-between;align-items:center;margin-top:20px;padding-top:16px;border-top:2px dashed var(--brutal-border);">
                                <span style="font-family:var(--font-mono);font-size:11px;font-weight:700;opacity:.6;">{{ $project->created_at->format('Y-m-d') }}</span>
                                <span style="font-family:var(--font-mono);font-size:12px;font-weight:700;color:var(--brutal-accent);">VIEW →</span>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    {{-- LATEST NEWS --}}
    <section class="brutal-grid-bg" style="padding:80px 24px;border-bottom:3px solid var(--brutal-border);">
        <div style="max-width:1200px;margin:0 auto;">
            <div style="display:flex;justify-content:space-between;align-items:flex-end;flex-wrap:wrap;gap:16px;margin-bottom:40px;">
                <div>
                    <span class="brutal-marker">// 02 · LATEST NEWS</span>
                    <h2 style="font-family:var(--font-sans);font-weight:900;font-size:clamp(2rem,4vw,2.75rem);letter-spacing:-0.03em;margin:16px 0 0;line-height:1;">
                        From the lab <x-lucide-sparkles class="brutal-spark" style="width:32px;height:32px;display:inline-block;vertical-align:middle;color:var(--brutal-accent);" />
                    </h2>
                </div>
                <a href="{{ url('/news') }}" class="lab-btn lab-btn--secondary">ALL POSTS <x-lucide-arrow-right style="width:14px;height:14px;" /></a>
            </div>

            @if($news->isEmpty())
                <div class="brutal-card" style="text-align:center;padding:60px;">
                    <p style="color:var(--lab-fg-muted);margin:0;font-weight:600;">No posts yet.</p>
                </div>
            @else
                <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(320px,1fr));gap:28px;">
                    @foreach($news as $item)
                        <a href="{{ route('news.show', $item) }}" class="brutal-card">
                            <div style="display:flex;justify-content:space-between;align-items:center;gap:12px;margin-bottom:14px;">
                                <span class="brutal-badge brutal-badge--orange">{{ strtoupper($item->category) }}</span>
                                <span style="font-family:var(--font-mono);font-size:11px;font-weight:700;opacity:.6;">{{ $item->published_at?->format('Y-m-d') }}</span>
                            </div>
                            <h3 style="margin:0 0 10px;font-size:18px;font-weight:800;letter-spacing:-0.02em;line-height:1.3;">{{ $item->title }}</h3>
                            <p style="color:var(--lab-fg-muted);font-size:13px;margin:0;line-height:1.55;font-weight:500;">{{ $item->excerpt }}</p>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    

    {{-- CONTACTS / COMMUNITY --}}
    <section class="brutal-grid-bg" style="padding:0 24px;">
        <div style="max-width:1200px;margin:0 auto;">

            <div class="brutal-contact">
                <div>
                    <h2 class="brutal-contact-title">Discord.</h2>
                    <p class="brutal-contact-sub">Join the conversation</p>
                    <p class="brutal-contact-text">
                        We use Discord as our main community hub. It keeps our conversations open and accessible — for newcomers and experienced developers alike.
                    </p>
                    <div class="brutal-contact-actions">
                        <x-lab::button variant="primary" href="https://discord.gg/sharzhukov" target="_blank">
                            OPEN DISCORD
                        </x-lab::button>
                        <a href="https://sharzhukov.com" class="brutal-link-arrow">Learn more <x-lucide-arrow-right style="width:14px;height:14px;" /></a>
                    </div>
                </div>
                <div class="brutal-orbit">
                    <div class="brutal-orbit-ring"></div>
                    <div class="brutal-orbit-ring brutal-orbit-ring--sm"></div>
                    <div class="brutal-orbit-ring brutal-orbit-ring--xs"></div>
                    <div class="brutal-orbit-core">
                        <svg viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path d="M20.317 4.37a19.79 19.79 0 0 0-4.885-1.515.074.074 0 0 0-.079.037c-.21.375-.444.864-.608 1.25a18.27 18.27 0 0 0-5.487 0 12.64 12.64 0 0 0-.617-1.25.077.077 0 0 0-.079-.037A19.736 19.736 0 0 0 3.677 4.37a.07.07 0 0 0-.032.027C.533 9.046-.32 13.58.099 18.057a.082.082 0 0 0 .031.057 19.9 19.9 0 0 0 5.993 3.03.078.078 0 0 0 .084-.028 14.09 14.09 0 0 0 1.226-1.994.076.076 0 0 0-.041-.106 13.107 13.107 0 0 1-1.872-.892.077.077 0 0 1-.008-.128 10.2 10.2 0 0 0 .372-.292.074.074 0 0 1 .077-.01c3.928 1.793 8.18 1.793 12.062 0a.074.074 0 0 1 .078.01c.12.098.246.198.373.292a.077.077 0 0 1-.006.127 12.299 12.299 0 0 1-1.873.892.077.077 0 0 0-.041.107c.36.698.772 1.362 1.225 1.993a.076.076 0 0 0 .084.028 19.839 19.839 0 0 0 6.002-3.03.077.077 0 0 0 .032-.054c.5-5.177-.838-9.674-3.549-13.66a.061.061 0 0 0-.031-.03zM8.02 15.33c-1.183 0-2.157-1.085-2.157-2.419 0-1.333.956-2.419 2.157-2.419 1.21 0 2.176 1.096 2.157 2.42 0 1.333-.956 2.418-2.157 2.418zm7.975 0c-1.183 0-2.157-1.085-2.157-2.419 0-1.333.955-2.419 2.157-2.419 1.21 0 2.176 1.096 2.157 2.42 0 1.333-.946 2.418-2.157 2.418z"/>
                        </svg>
                    </div>
                    <div class="brutal-orbit-dot dot-1"></div>
                    <div class="brutal-orbit-dot dot-2"></div>
                    <div class="brutal-orbit-dot dot-3"></div>
                    <div class="brutal-orbit-dot dot-4"></div>
                </div>
            </div>

            <div class="brutal-contact reverse">
                <div class="brutal-orbit">
                    <div class="brutal-orbit-ring"></div>
                    <div class="brutal-orbit-ring brutal-orbit-ring--sm"></div>
                    <div class="brutal-orbit-ring brutal-orbit-ring--xs"></div>
                    <div class="brutal-orbit-core">
                        <svg viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z"/>
                        </svg>
                    </div>
                    <div class="brutal-orbit-dot dot-1"></div>
                    <div class="brutal-orbit-dot dot-2"></div>
                    <div class="brutal-orbit-dot dot-3"></div>
                    <div class="brutal-orbit-dot dot-4"></div>
                </div>
                <div>
                    <h2 class="brutal-contact-title">Telegram.</h2>
                    <p class="brutal-contact-sub">Stay in the loop</p>
                    <p class="brutal-contact-text">
                        Our Telegram channel is where we share updates, announcements and behind-the-scenes progress. It's the fastest way to stay in the loop — no noise, just the important stuff.
                    </p>
                    <div class="brutal-contact-actions">
                        <x-lab::button variant="primary" href="https://t.me/sharzhukov" target="_blank">
                            FOLLOW ON TELEGRAM
                        </x-lab::button>
                        <a href="https://sharzhukov.com" class="brutal-link-arrow">Learn more <x-lucide-arrow-right style="width:14px;height:14px;" /></a>
                    </div>
                </div>
            </div>

            <div class="brutal-contact">
                <div>
                    <h2 class="brutal-contact-title">GitHub.</h2>
                    <p class="brutal-contact-sub">Open source. Open minds.</p>
                    <p class="brutal-contact-text">
                        All our projects are open source. Dive into the code, report issues, suggest features, or contribute directly to the repositories.
                    </p>
                    <div class="brutal-contact-actions">
                        <x-lab::button variant="primary" href="https://github.com/SharzhukovLab" target="_blank">
                            VIEW ON GITHUB
                        </x-lab::button>
                        <a href="https://github.com/SharzhukovLab" class="brutal-link-arrow" target="_blank">Contribute <x-lucide-arrow-right style="width:14px;height:14px;" /></a>
                    </div>
                </div>
                <div class="brutal-orbit">
                    <div class="brutal-orbit-ring"></div>
                    <div class="brutal-orbit-ring brutal-orbit-ring--sm"></div>
                    <div class="brutal-orbit-ring brutal-orbit-ring--xs"></div>
                    <div class="brutal-orbit-core">
                        <svg viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 .297c-6.63 0-12 5.373-12 12 0 5.303 3.438 9.8 8.205 11.385.6.113.82-.258.82-.577 0-.285-.01-1.04-.015-2.04-3.338.724-4.042-1.61-4.042-1.61C4.422 18.07 3.633 17.7 3.633 17.7c-1.087-.744.084-.729.084-.729 1.205.084 1.838 1.236 1.838 1.236 1.07 1.835 2.809 1.305 3.495.998.108-.776.417-1.305.76-1.605-2.665-.3-5.466-1.332-5.466-5.93 0-1.31.465-2.38 1.235-3.22-.135-.303-.54-1.523.105-3.176 0 0 1.005-.322 3.3 1.23.96-.267 1.98-.399 3-.405 1.02.006 2.04.138 3 .405 2.28-1.552 3.285-1.23 3.285-1.23.645 1.653.24 2.873.12 3.176.765.84 1.23 1.91 1.23 3.22 0 4.61-2.805 5.625-5.475 5.92.42.36.81 1.096.81 2.22 0 1.606-.015 2.896-.015 3.286 0 .315.21.69.825.57C20.565 22.092 24 17.592 24 12.297c0-6.627-5.373-12-12-12"/>
                        </svg>
                    </div>
                    <div class="brutal-orbit-dot dot-1"></div>
                    <div class="brutal-orbit-dot dot-2"></div>
                    <div class="brutal-orbit-dot dot-3"></div>
                    <div class="brutal-orbit-dot dot-4"></div>
                </div>
            </div>

        </div>
    </section>

    {{-- FAQ --}}
    <section class="brutal-grid-bg" style="padding:80px 24px;border-top:3px solid var(--brutal-border);border-bottom:3px solid var(--brutal-border);">
        <div style="max-width:900px;margin:0 auto;">
            <div style="margin-bottom:48px;">
                <span class="brutal-marker">// FAQ</span>
                <h2 style="font-family:var(--font-sans);font-weight:900;font-size:clamp(2rem,4vw,2.75rem);letter-spacing:-0.03em;margin:16px 0 0;line-height:1;">
                    Common questions
                </h2>
            </div>

            @php
    $faqs = \App\Models\Faq::visible()->ordered()->get();
@endphp

            @foreach($faqs as $faq)
                <details class="brutal-faq-item">
                    <summary>{{ $faq->question }}</summary>
                    <div class="brutal-faq-body">{!! $faq->answer !!}</div>
                </details>
            @endforeach

        </div>
    </section>

    {{-- NEWSLETTER --}}
    <section style="padding:80px 24px;">
        <div style="max-width:900px;margin:0 auto;">
            <div class="brutal-newsletter">
                <div>
                    <p style="font-family:var(--font-mono);font-size:11px;font-weight:700;letter-spacing:.15em;text-transform:uppercase;color:var(--brutal-accent);margin:0 0 12px;">// NEWSLETTER</p>
                    <h2 style="font-family:var(--font-sans);font-weight:900;font-size:clamp(1.75rem,3.5vw,2.5rem);letter-spacing:-0.03em;line-height:1.05;margin:0 0 12px;color:var(--lab-bg);">
                        Get lab notes<br>in your inbox.
                    </h2>
                    <p style="color:rgba(255,255,255,.7);font-size:14px;margin:0;line-height:1.6;">
                        Monthly digest — new projects, blog posts, and behind-the-scenes. No spam.
                    </p>
                </div>
                <form method="POST" action="{{ url('/newsletter/subscribe') }}" style="display:flex;flex-direction:column;gap:12px;">
                    @csrf
                    <input type="email" name="email" required placeholder="you@example.com">
                    <x-lab::button variant="primary" type="submit" style="background:#fff !important;color:var(--brutal-border) !important;border-color:var(--brutal-border) !important;">
                        SUBSCRIBE →
                    </x-lab::button>
                </form>
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="brutal-grid-bg" style="padding:100px 24px;">
        <div style="max-width:900px;margin:0 auto;text-align:center;">
            <span class="brutal-sticker" style="margin-bottom:24px;">// JOIN US</span>
            <h2 style="font-family:var(--font-sans);font-weight:900;font-size:clamp(2.25rem,5vw,3.75rem);letter-spacing:-0.04em;margin:24px 0 20px;line-height:1;">
                Ship with us <x-lucide-sparkles class="brutal-spark" style="width:32px;height:32px;display:inline-block;vertical-align:middle;color:var(--brutal-accent);" />
            </h2>
            <p style="color:var(--lab-fg-muted);font-size:18px;margin:0 auto 40px;max-width:520px;font-weight:500;line-height:1.6;">
                Whether you contribute code, docs, or ideas — you're welcome here.
            </p>
            <div style="display:flex;gap:16px;justify-content:center;flex-wrap:wrap;">
                <x-lab::button variant="primary" size="lg" href="https://account.sharzhukov.com/register">
                    CREATE ACCOUNT <x-lucide-arrow-right style="width:16px;height:16px;" />
                </x-lab::button>
                <x-lab::button variant="secondary" size="lg" href="https://github.com/SharzhukovLab" target="_blank">
                    CONTRIBUTE ON GITHUB
                </x-lab::button>
            </div>
        </div>
    </section>

</x-layouts.public>