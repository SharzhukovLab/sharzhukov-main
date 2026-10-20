<x-layouts.public :title="'Activity Feed — Sharzhukov\'s Laboratory'">
    <article style="max-width:820px;margin:0 auto;padding:60px 24px 80px;">
        <span class="brutal-marker">// FEED</span>
        <h1 style="font-family:var(--font-sans);font-weight:900;font-size:clamp(2rem,5vw,3rem);letter-spacing:-0.04em;margin:14px 0 8px;line-height:1;">
            Activity
        </h1>
        <p style="color:var(--lab-fg-muted);font-family:var(--font-mono);font-size:12px;letter-spacing:.08em;text-transform:uppercase;margin:0 0 32px;">
            You follow {{ $followingCnt }} {{ \Illuminate\Support\Str::plural('user', $followingCnt) }}
        </p>

        @if($activities->isEmpty())
            <div class="brutal-card" style="padding:48px;text-align:center;">
                <p style="color:var(--lab-fg-muted);font-weight:600;margin:0 0 16px;">
                    No activity yet. Follow some users to see their updates here.
                </p>
                <a href="{{ route('users.show', 'sharzhukov') }}" class="lab-btn lab-btn--secondary" style="font-size:12px;">
                    BROWSE USERS →
                </a>
            </div>
        @else
            <div class="lab-activity-list">
                @foreach($activities as $activity)
                    <x-lab::activity-item :activity="$activity" />
                @endforeach
            </div>

            @if($activities->hasMorePages())
                <div style="margin-top:32px;text-align:center;">
                    <a href="{{ $activities->nextPageUrl() }}" class="lab-btn lab-btn--secondary">
                        LOAD OLDER →
                    </a>
                </div>
            @endif
        @endif
    </article>
</x-layouts.public>
