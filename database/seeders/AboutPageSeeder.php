<?php
require '/var/www/main_shrz_usr/data/www/main.sharzhukov.com/vendor/autoload.php';
$app = require '/var/www/main_shrz_usr/data/www/main.sharzhukov.com/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Page;

$contactEmail = 'admin@sharzhukov.com';
$faqEmail     = 'faq@sharzhukov.com';

$content = <<<HTML
<p style="font-size:18px;line-height:1.7;color:var(--lab-fg-muted);">Sharzhukov's Laboratory is a small open-source collective building tools for developers. We're based in the EU, we host our own infrastructure, and we ship software we actually use ourselves.</p>

<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:0;border:2px solid var(--brutal-border);margin:40px 0;">
    <div style="padding:24px;border-right:2px solid var(--brutal-border);">
        <div style="font-family:var(--font-mono);font-size:11px;letter-spacing:0.08em;color:var(--lab-fg-muted);margin-bottom:8px;">// SITES</div>
        <div style="font-family:var(--font-sans);font-weight:900;font-size:32px;letter-spacing:-0.03em;">4</div>
    </div>
    <div style="padding:24px;border-right:2px solid var(--brutal-border);">
        <div style="font-family:var(--font-mono);font-size:11px;letter-spacing:0.08em;color:var(--lab-fg-muted);margin-bottom:8px;">// REPOS</div>
        <div style="font-family:var(--font-sans);font-weight:900;font-size:32px;letter-spacing:-0.03em;">5+</div>
    </div>
    <div style="padding:24px;border-right:2px solid var(--brutal-border);">
        <div style="font-family:var(--font-mono);font-size:11px;letter-spacing:0.08em;color:var(--lab-fg-muted);margin-bottom:8px;">// SINCE</div>
        <div style="font-family:var(--font-sans);font-weight:900;font-size:32px;letter-spacing:-0.03em;">2024</div>
    </div>
    <div style="padding:24px;">
        <div style="font-family:var(--font-mono);font-size:11px;letter-spacing:0.08em;color:var(--lab-fg-muted);margin-bottom:8px;">// MADE IN</div>
        <div style="font-family:var(--font-sans);font-weight:900;font-size:32px;letter-spacing:-0.03em;">EU</div>
    </div>
</div>

<h2>What we build</h2>
<p>Small, focused tools for developers and technical teams. Nothing that tries to be everything for everyone — just things that solve a real problem and stay out of your way once installed.</p>
<ul>
    <li><strong>Main site</strong> — the front door: projects, news, contact.</li>
    <li><strong>Account</strong> — single sign-on for the whole ecosystem, with 2FA, passkeys, session management.</li>
    <li><strong>Wiki &amp; Docs</strong> — a community-editable knowledge base with revisions, categories, and tags.</li>
    <li><strong>Short Links</strong> — minimal URL shortener with click analytics.</li>
</ul>

<h2>Principles</h2>
<ol>
    <li><strong>Simple over clever.</strong> Boring technology wins. We pick tools that are well-documented, have a long track record, and don't try to surprise us.</li>
    <li><strong>EU-hosted, GDPR-first.</strong> Your data stays in Europe. We don't sell it, we don't profile you, we don't share it with advertisers. See our <a href="/privacy">Privacy Policy</a>.</li>
    <li><strong>Open by default.</strong> Code lives in public repositories. If something's broken, you can fix it and send a PR.</li>
    <li><strong>Own your stack.</strong> No vendor lock-in. Every service we use can be swapped out — nothing is a black box.</li>
    <li><strong>Fast, then fast.</strong> Performance is a feature, not an afterthought. Every page is server-rendered in one round trip, no chatty SPA backend-for-frontend nonsense.</li>
</ol>

<h2>The stack</h2>
<p>For the curious ones:</p>
<ul>
    <li><strong>Backend:</strong> PHP 8.4, Laravel 13, Filament 4 admin panels, Livewire 3</li>
    <li><strong>Database:</strong> MySQL 8, Redis-compatible cache layer</li>
    <li><strong>Frontend:</strong> Tailwind CSS 4, Vite, vanilla JS where it counts, Inter &amp; JetBrains Mono</li>
    <li><strong>Infrastructure:</strong> Ubuntu 24.04 server in the Netherlands (Aeza), Cloudflare in front, Resend for email</li>
    <li><strong>Design:</strong> Neo-brutalist, single accent color (<code>#2563eb</code>), thick borders, offset shadows, zero-radius corners</li>
</ul>

<h2>Who's behind this</h2>
<p>One developer, a few collaborators, and a lot of coffee. We're not a company — we're a collective. If you find a bug, report it. If you fix a bug, open a PR. If you have an idea, write it down and send it over.</p>

<h2>Get in touch</h2>
<p>We answer every email that isn't spam. Pick the channel that fits:</p>
<ul>
    <li><strong>General enquiries:</strong> <a href="mailto:{$contactEmail}">{$contactEmail}</a></li>
    <li><strong>FAQ &amp; privacy requests:</strong> <a href="mailto:{$faqEmail}">{$faqEmail}</a></li>
    <li><strong>Code &amp; issues:</strong> <a href="https://github.com/SharzhukovLab" rel="noopener" target="_blank">github.com/SharzhukovLab</a></li>
</ul>

<div style="border:2px solid var(--brutal-border);padding:24px;margin-top:40px;background:var(--lab-bg-elevated);">
    <div style="font-family:var(--font-mono);font-size:11px;letter-spacing:0.08em;color:var(--lab-fg-muted);margin-bottom:12px;">// NEXT STEPS</div>
    <p style="margin:0 0 16px;font-size:16px;">Ready to try the ecosystem? Create a free account and you're in — no credit card, no trial timer, no dark patterns.</p>
    <a href="https://account-main.sharzhukov.com/login" class="lab-btn lab-btn--primary" style="display:inline-block;">Create account &rarr;</a>
</div>
HTML;

$page = Page::updateOrCreate(
    ['slug' => 'about'],
    [
        'title'            => 'About',
        'hero_eyebrow'     => '// ABOUT',
        'hero_title'       => "Sharzhukov's Laboratory",
        'hero_subtitle'    => 'A small open-source collective building tools for developers. Made in EU.',
        'content'          => $content,
        'blocks'           => null,
        'meta_description' => "About Sharzhukov's Laboratory — who we are, what we build, and how to get in touch. An open-source collective from the EU.",
        'is_published'     => true,
    ]
);

echo "✓ about updated — id={$page->id}, " . strlen($content) . " bytes\n";
