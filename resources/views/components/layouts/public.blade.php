@props(['title' => null, 'description' => null])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? "Sharzhukov's Laboratory" }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#ff5c1f">
    {{-- Favicon --}}
    <link rel="icon" href="/favicon.ico" sizes="any">
    <link rel="icon" type="image/png" sizes="32x32" href="/img/branding/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/img/branding/favicon-16x16.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/img/branding/apple-touch-icon.png">
    <link rel="manifest" href="/site.webmanifest">
    <meta name="description" content="Sharzhukov's Laboratory — an open-source collective building tools for developers.">
    @stack('meta')
        <script>
        (function () {
            var t = localStorage.getItem('theme');
            if (t === 'dark') document.documentElement.classList.add('dark');
        })();
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- SEO + Open Graph --}}
    <meta name="description" content="{{ $description ?? 'Sharzhukov\'s Laboratory — open-source collective building tools for developers.' }}">
    <meta name="robots" content="index, follow">

    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $title ?? config('app.name') }}">
    <meta property="og:description" content="{{ $description ?? 'Open-source collective building tools for developers. Made in EU.' }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:site_name" content="Sharzhukov's Laboratory">
    <meta property="og:image" content="{{ url('/img/branding/core-white.png') }}">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $title ?? config('app.name') }}">
    <meta name="twitter:description" content="{{ $description ?? 'Open-source collective building tools for developers.' }}">

    <link rel="canonical" href="{{ url()->current() }}">
</head>
<body>

@php
    $navItems = \App\Models\Setting::get('nav.items') ?? [
        ['label' => 'Products', 'url' => '/products', 'external' => false],
        ['label' => 'Projects', 'url' => '/projects', 'external' => false],
        ['label' => 'News',     'url' => '/news',     'external' => false],
        ['label' => 'Wiki',     'url' => 'https://wiki.sharzhukov.com', 'external' => true],
        ['label' => 'FAQ',      'url' => '/faq',      'external' => false],
        ['label' => 'About',    'url' => '/about',    'external' => false],
    ];
@endphp
<x-lab::site-nav
    brand-title="SHARZHUKOV'S LAB"
    brand-sub="// OPEN SOURCE"
    :items="$navItems"
/>

<main>{{ $slot }}</main>

<x-lab::site-footer context="main" />

<script>
    function toggleLabTheme() {
        var d = document.documentElement.classList.toggle('dark');
        localStorage.setItem('theme', d ? 'dark' : 'light');
    }
</script>

{{-- Scroll progress --}}
<div id="scroll-progress" style="position:fixed;top:0;left:0;height:4px;background:var(--brutal-accent);z-index:100;width:0;transition:width .1s linear;box-shadow:0 0 8px var(--brutal-accent);"></div>

{{-- Back to top --}}
<button id="back-to-top" onclick="window.scrollTo({top:0,behavior:'smooth'})" style="position:fixed;bottom:32px;right:32px;width:52px;height:52px;background:var(--brutal-accent);color:#fff;border:3px solid var(--brutal-border);box-shadow:4px 4px 0 var(--brutal-shadow);cursor:pointer;display:none;z-index:99;align-items:center;justify-content:center;">
    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M12 19V5M5 12l7-7 7 7"/></svg>
</button>

<script>
    // Scroll progress
    const progress = document.getElementById('scroll-progress');
    const backToTop = document.getElementById('back-to-top');
    window.addEventListener('scroll', () => {
        const h = document.documentElement.scrollHeight - window.innerHeight;
        const p = (window.scrollY / h) * 100;
        progress.style.width = p + '%';
        backToTop.style.display = window.scrollY > 600 ? 'flex' : 'none';
    });
</script>
<x-lab::cookie-consent />
</body>
</html>