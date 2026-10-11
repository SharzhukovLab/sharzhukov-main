@php
    $joinedYear = $user->created_at?->format('Y') ?? '—';
    $joinedDate = $user->created_at?->format('M Y') ?? '—';
    $socials    = is_array($user->social_links) ? $user->social_links : [];
    $badgeLabels = [];

    if ($user->isAdmin())         $badgeLabels[] = 'ADMIN';
    elseif ($user->isModerator()) $badgeLabels[] = 'MODERATOR';
    elseif ($user->isEditor())    $badgeLabels[] = 'EDITOR';

    if ($user->show_in_team && $user->team_role) $badgeLabels[] = 'TEAM';
    if ($user->is_trusted)                        $badgeLabels[] = 'TRUSTED';
    if ($user->email_verified_at)                 $badgeLabels[] = 'VERIFIED';
    if ($user->two_factor_confirmed_at)           $badgeLabels[] = '2FA';

    // Чередуем: accent → dark → outline → dark → outline ...
    $badges = [];
    foreach ($badgeLabels as $i => $label) {
        $color = match(true) {
            $i === 0 => 'accent',                // первый всегда accent (важно: admin/role)
            $i % 2 === 1 => 'dark',
            default => 'outline',
        };
        $badges[] = ['label' => $label, 'color' => $color];
    }
@endphp

<x-layouts.public :title="$user->name . ' (@' . $user->username . ') — Sharzhukov\'s Laboratory'">

    @push('meta')
        <meta property="og:type" content="profile">
        <meta property="og:title" content="{{ $user->name }} (@{{ $user->username }})">
        @if($user->bio)
            <meta property="og:description" content="{{ \Illuminate\Support\Str::limit($user->bio, 160) }}">
        @endif
        <meta property="og:image" content="{{ $user->avatar_url }}">
        <meta property="og:url" content="{{ $canonicalUrl }}">
        <meta name="twitter:card" content="summary">
        <meta name="twitter:title" content="{{ $user->name }} (@{{ $user->username }})">
        @if($user->bio)
            <meta name="twitter:description" content="{{ \Illuminate\Support\Str::limit($user->bio, 160) }}">
        @endif
        <link rel="canonical" href="{{ $canonicalUrl }}">
        <meta name="description" content="{{ $user->bio ? \Illuminate\Support\Str::limit($user->bio, 160) : $user->name . ' on Sharzhukov\'s Laboratory — projects, contributions, profile.' }}">
    @endpush

    {{-- HERO --}}
    <section class="brutal-grid-bg" style="padding:64px 24px 48px;border-bottom:3px solid var(--brutal-border);">
        <div style="max-width:900px;margin:0 auto;">
            <span class="brutal-marker">// PROFILE</span>

            <div style="display:flex;gap:28px;align-items:flex-start;flex-wrap:wrap;margin-top:20px;">
                <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}"
                     style="width:120px;height:120px;border-radius:0;object-fit:cover;border:3px solid var(--brutal-border);box-shadow:6px 6px 0 var(--brutal-shadow);flex-shrink:0;">

                <div style="min-width:0;flex:1;">
                    <h1 style="font-family:var(--font-sans);font-weight:900;font-size:clamp(1.9rem,4vw,2.75rem);letter-spacing:-0.035em;line-height:1.05;margin:0 0 6px;">
                        {{ $user->name }}
                    </h1>
                    <p style="font-family:var(--font-mono);font-size:14px;color:var(--lab-fg-muted);margin:0 0 4px;letter-spacing:0.05em;">
                        &#64;{{ $user->username }}
                    </p>
                    @if($user->team_role)
                        <p style="font-family:var(--font-mono);font-size:12px;color:var(--brutal-accent);margin:0 0 16px;letter-spacing:0.08em;font-weight:700;text-transform:uppercase;">
                            {{ $user->team_role }}
                        </p>
                    @endif

                    @if(count($badges))
                        <div style="display:flex;flex-wrap:wrap;gap:8px;margin-bottom:16px;">
                            @foreach($badges as $b)
                                <span class="brutal-badge brutal-badge--{{ $b['color'] }}">{{ $b['label'] }}</span>
                            @endforeach
                        </div>
                    @endif

                    @if($user->bio)
                        <p style="color:var(--lab-fg);font-size:15px;line-height:1.65;margin:0;max-width:600px;">
                            {{ $user->bio }}
                        </p>
                    @endif
                </div>
            </div>

            @php
                $renderedSocials = collect($socials)
                    ->filter(fn ($s) => is_array($s) && !empty($s['url']) && !empty($s['platform']))
                    ->values();
            @endphp
            @if($renderedSocials->isNotEmpty())
                <div style="margin-top:28px;display:flex;flex-wrap:wrap;gap:10px;">
                    @foreach($renderedSocials as $s)
                        @php
                            $platform = $s['platform'];
                            $url      = $s['url'];
                            $label    = \App\Models\User::allowedSocialPlatforms()[$platform] ?? ucfirst($platform);
                            $icon     = match($platform) {
                                'github'   => 'github',
                                'twitter'  => 'twitter',
                                'telegram' => 'send',
                                'discord'  => 'message-circle',
                                'reddit'   => 'message-square',
                                'youtube'  => 'youtube',
                                'twitch'   => 'twitch',
                                'website'  => 'globe',
                                'mastodon' => 'at-sign',
                                'linkedin' => 'linkedin',
                                default    => 'link-2',
                            };
                        @endphp
                        <a href="{{ $url }}" target="_blank" rel="noopener nofollow ugc" class="lab-btn lab-btn--secondary" style="font-size:11px;padding:8px 12px;text-transform:uppercase;letter-spacing:0.06em;">
                            <x-dynamic-component :component="'lucide-' . $icon" style="width:14px;height:14px;" />
                            <span>{{ $label }}</span>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    {{-- STATS --}}
    <section class="brutal-grid-bg" style="padding:32px 24px;border-bottom:3px solid var(--brutal-border);background:var(--lab-bg-elevated);">
        <div style="max-width:900px;margin:0 auto;display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:16px;">
            @foreach([
                ['value' => $stats['projects'], 'label' => 'Projects'],
                ['value' => $stats['comments'], 'label' => 'Comments'],
                ['value' => $stats['news'],     'label' => 'News'],
                ['value' => $stats['wiki'],     'label' => 'Wiki pages'],
                ['value' => $joinedYear,        'label' => 'Member since'],
            ] as $s)
                <div class="brutal-card" style="padding:18px;">
                    <div style="font-family:var(--font-mono);font-weight:900;font-size:26px;line-height:1;color:var(--brutal-border);">{{ $s['value'] }}</div>
                    <div style="font-family:var(--font-mono);font-size:11px;font-weight:700;letter-spacing:0.1em;color:var(--lab-fg-muted);text-transform:uppercase;margin-top:8px;">{{ $s['label'] }}</div>
                </div>
            @endforeach
        </div>
    </section>

    
    {{-- HARDWARE --}}
    @php $specsBlocks = $user->team_specs_list; @endphp
    @if(!empty($specsBlocks))
        <section class="brutal-grid-bg" style="padding:40px 24px 56px;border-bottom:3px solid var(--brutal-border);">
            <div style="max-width:900px;margin:0 auto;">
                <span class="brutal-marker">// HARDWARE</span>
                <h2 style="font-family:var(--font-sans);font-weight:900;font-size:clamp(1.5rem,3vw,2rem);letter-spacing:-0.03em;margin:14px 0 24px;line-height:1.1;">
                    Gear
                </h2>

                <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(300px,1fr));gap:20px;">
                    @foreach($specsBlocks as $block)
                        <div class="brutal-card" style="padding:0;overflow:hidden;">
                            <div style="padding:14px 18px;background:var(--brutal-border);color:var(--lab-bg);">
                                <div style="font-family:var(--font-mono);font-size:10px;letter-spacing:0.15em;opacity:.7;text-transform:uppercase;margin-bottom:4px;">// DEVICE</div>
                                <div style="font-family:var(--font-sans);font-weight:900;font-size:16px;letter-spacing:-0.01em;">{{ $block['name'] }}</div>
                            </div>
                            <div style="padding:6px 0;">
                                @forelse($block['items'] as $item)
                                    <div style="display:flex;justify-content:space-between;gap:12px;padding:10px 18px;border-bottom:2px solid var(--brutal-border);">
                                        <span style="font-family:var(--font-mono);font-size:11px;font-weight:700;letter-spacing:0.08em;color:var(--brutal-accent);text-transform:uppercase;">
                                            {{ $item['label'] }}
                                        </span>
                                        <span style="font-family:var(--font-sans);font-weight:700;font-size:14px;color:var(--lab-fg);text-align:right;">
                                            {{ $item['value'] }}
                                        </span>
                                    </div>
                                @empty
                                    <div style="padding:18px;color:var(--lab-fg-muted);font-size:13px;font-family:var(--font-mono);">No components</div>
                                @endforelse
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
    {{-- PROJECTS --}}
    <article style="max-width:900px;margin:0 auto;padding:56px 24px 72px;">
        <div style="display:flex;justify-content:space-between;align-items:flex-end;flex-wrap:wrap;gap:12px;margin-bottom:28px;">
            <div>
                <span class="brutal-marker">// WORK</span>
                <h2 style="font-family:var(--font-sans);font-weight:900;font-size:clamp(1.5rem,3vw,2rem);letter-spacing:-0.03em;margin:12px 0 0;line-height:1.1;">
                    Projects
                </h2>
            </div>
            @if($stats['projects'] > 0)
                <a href="{{ url('/projects') }}" class="lab-btn lab-btn--secondary" style="font-size:11px;">
                    ALL PROJECTS →
                </a>
            @endif
        </div>

        @if($projects->isEmpty())
            <div class="brutal-card" style="padding:48px;text-align:center;">
                <p style="color:var(--lab-fg-muted);font-weight:600;margin:0;">No approved projects yet.</p>
            </div>
        @else
            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:18px;">
                @foreach($projects as $project)
                    <a href="{{ $project->public_url }}" style="text-decoration:none;color:inherit;display:block;">
                        <div class="brutal-card" style="padding:22px;height:100%;display:flex;flex-direction:column;gap:10px;">
                            <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:10px;">
                                <span class="brutal-badge">PRJ-{{ str_pad($project->id, 3, '0', STR_PAD_LEFT) }}</span>
                                @if($project->tech_stack)
                                    <span style="font-family:var(--font-mono);font-size:10px;color:var(--lab-fg-muted);">
                                        {{ \Illuminate\Support\Str::limit(is_array($project->tech_stack) ? implode(' · ', $project->tech_stack) : $project->tech_stack, 40) }}
                                    </span>
                                @endif
                            </div>
                            <h3 style="margin:0;font-family:var(--font-sans);font-weight:900;font-size:17px;letter-spacing:-0.01em;">
                                {{ $project->title }}
                            </h3>
                            @if($project->tagline)
                                <p style="color:var(--lab-fg-muted);font-size:13px;margin:0;line-height:1.5;">
                                    {{ $project->tagline }}
                                </p>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </article>

    {{-- CTA --}}
    <section style="padding:0 24px 80px;">
        <div style="max-width:900px;margin:0 auto;">
            <x-lab::contact-cta context="main" />
        </div>
    </section>
</x-layouts.public>