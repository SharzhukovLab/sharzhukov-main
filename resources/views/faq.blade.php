<x-layouts.public
    title="FAQ — Sharzhukov's Laboratory"
    description="Frequently asked questions about Sharzhukov's Laboratory — open source, hosting, contributions, and more.">

    <section class="brutal-grid-bg" style="padding:60px 24px 40px;border-bottom:3px solid var(--brutal-border);">
        <div style="max-width:820px;margin:0 auto;">
            <span class="brutal-marker">// FAQ</span>
            <h1 class="brutal-hero-title" style="margin:20px 0 12px;font-size:clamp(2rem,4vw,3rem);">Frequently Asked Questions</h1>
            <p style="color:var(--lab-fg-muted);font-size:17px;margin:0;line-height:1.6;max-width:600px;">
                Can't find your answer here?
                <a href="/contact" style="color:var(--brutal-accent);text-decoration:underline;">Contact us</a> — we answer every message.
            </p>
        </div>
    </section>

    <article style="max-width:820px;margin:0 auto;padding:48px 24px 80px;">
        @if($faqs->isEmpty())
            <p style="color:var(--lab-fg-muted);">No FAQ entries yet.</p>
        @else
            <div style="display:flex;flex-direction:column;gap:16px;">
                @foreach($faqs as $i => $faq)
                    <details style="border:2px solid var(--brutal-border);background:var(--lab-bg-elevated);box-shadow:4px 4px 0 var(--brutal-shadow);">
                        <summary style="cursor:pointer;padding:20px 24px;font-family:var(--font-sans);font-weight:900;font-size:16px;letter-spacing:-0.01em;display:flex;justify-content:space-between;align-items:center;gap:16px;list-style:none;">
                            <span style="display:flex;gap:14px;align-items:baseline;">
                                <span style="font-family:var(--font-mono);font-size:12px;color:var(--brutal-accent);font-weight:700;">
                                    {{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}
                                </span>
                                <span>{{ $faq->question }}</span>
                            </span>
                            <span style="font-family:var(--font-mono);font-size:20px;color:var(--brutal-accent);transition:transform .2s ease;flex-shrink:0;"
                                  class="faq-plus">+</span>
                        </summary>
                        <div style="padding:0 24px 24px 60px;border-top:2px dashed var(--brutal-border);padding-top:20px;">
                            <div class="lab-prose" style="font-size:15px;line-height:1.7;">
                                {!! $faq->answer !!}
                            </div>
                        </div>
                    </details>
                @endforeach
            </div>

            <div style="margin-top:48px;padding:24px;border:2px solid var(--brutal-border);background:var(--lab-bg-elevated);box-shadow:4px 4px 0 var(--brutal-shadow);display:flex;justify-content:space-between;align-items:center;gap:24px;flex-wrap:wrap;">
                <div>
                    <div style="font-family:var(--font-mono);font-size:11px;letter-spacing:0.08em;color:var(--lab-fg-muted);margin-bottom:6px;">// STILL HAVE QUESTIONS?</div>
                    <div style="font-family:var(--font-sans);font-weight:900;font-size:18px;">We're happy to help.</div>
                </div>
                <a href="/contact" class="lab-btn lab-btn--primary">Contact us →</a>
            </div>
        @endif
    </article>

    <style>
        details summary::-webkit-details-marker { display: none; }
        details summary { list-style: none; }
        details[open] .faq-plus { transform: rotate(45deg); }
    </style>
</x-layouts.public>
