@php
    use App\Models\User;

    $team = User::query()
        ->where('show_in_team', true)
        ->whereNull('deletion_requested_at')
        ->where('is_banned', false)
        ->orderBy('team_order')
        ->orderBy('name')
        ->get();
@endphp

@if($team->isNotEmpty())
    <h2>Who's behind this</h2>
    <p>Not a company — a collective. Here's who you'll actually be talking to:</p>

    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(240px,1fr));gap:16px;margin:32px 0;">
        @foreach($team as $user)
            <a href="/u/{{ $user->username }}"
               style="display:flex;gap:14px;align-items:center;padding:16px;border:2px solid var(--brutal-border);background:var(--lab-bg-elevated);box-shadow:4px 4px 0 var(--brutal-shadow);text-decoration:none;color:inherit;transition:all .15s ease;"
               onmouseover="this.style.transform='translate(-3px,-3px)';this.style.boxShadow='7px 7px 0 var(--brutal-shadow)';"
               onmouseout="this.style.transform='';this.style.boxShadow='4px 4px 0 var(--brutal-shadow)';">
                <img src="{{ $user->avatar_url }}"
                     alt="{{ $user->name }}"
                     style="width:56px;height:56px;border-radius:0;object-fit:cover;border:2px solid var(--brutal-border);flex-shrink:0;">
                <div style="min-width:0;overflow:hidden;">
                    <div style="font-family:var(--font-sans);font-weight:900;font-size:15px;letter-spacing:-0.01em;color:var(--brutal-border);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                        {{ $user->name }}
                    </div>
                    <div style="font-family:var(--font-mono);font-size:10px;letter-spacing:0.08em;color:var(--lab-fg-muted);margin-top:2px;text-transform:uppercase;">
                        {{ $user->team_role ?: '@' . $user->username }}
                    </div>
                </div>
            </a>
        @endforeach
    </div>
@endif
