<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Admin' }} · {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="h-full font-sans antialiased bg-slate-100 dark:bg-slate-950 text-slate-800 dark:text-slate-100">
<div x-data="{ open: false }" class="min-h-full">
    {{-- Sidebar --}}
    <aside class="fixed inset-y-0 left-0 z-40 w-64 transform bg-slate-900 text-slate-300 transition-transform duration-200 lg:translate-x-0"
           :class="open ? 'translate-x-0' : '-translate-x-full'">
        <div class="flex h-16 items-center gap-2 px-6 border-b border-white/10">
            <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-indigo-600 font-bold text-white">HJ</span>
            <span class="font-semibold text-white">Portfolio Admin</span>
        </div>
        <nav class="px-3 py-4 space-y-1 text-sm">
            @php
                $links = [
                    ['admin.dashboard', 'Dashboard', 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
                    ['admin.profile', 'Profile', 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
                    ['admin.sections', 'Sections', 'M4 6h16M4 10h16M4 14h16M4 18h16'],
                    ['admin.projects', 'Projects', 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10'],
                    ['admin.skills', 'Skills', 'M13 10V3L4 14h7v7l9-11h-7z'],
                    ['admin.services', 'Services', 'M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4'],
                    ['admin.experience', 'Experience', 'M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'],
                    ['admin.education', 'Education', 'M12 14l9-5-9-5-9 5 9 5z M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z'],
                    ['admin.certifications', 'Certifications', 'M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z'],
                    ['admin.testimonials', 'Testimonials', 'M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z'],
                    ['admin.socials', 'Social Links', 'M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1'],
                    ['admin.messages', 'Messages', 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'],
                    ['admin.newsletter', 'Newsletter', 'M3 19v-8.93a2 2 0 01.89-1.664l7-4.666a2 2 0 012.22 0l7 4.666A2 2 0 0121 10.07V19M3 19a2 2 0 002 2h14a2 2 0 002-2M3 19l6.75-4.5M21 19l-6.75-4.5M12 3v6'],
                    ['admin.analytics', 'Analytics', 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'],
                ];
            @endphp
            @foreach ($links as [$route, $label, $icon])
                <a href="{{ route($route) }}" wire:navigate
                   @class([
                       'flex items-center gap-3 rounded-lg px-3 py-2 transition',
                       'bg-indigo-600 text-white' => request()->routeIs($route) || ($route === 'admin.projects' && request()->routeIs('admin.projects.*')),
                       'hover:bg-white/5 hover:text-white' => ! request()->routeIs($route),
                   ])>
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="{{ $icon }}"/></svg>
                    {{ $label }}
                </a>
            @endforeach
        </nav>
    </aside>

    {{-- Content --}}
    <div class="lg:pl-64">
        <header class="sticky top-0 z-30 flex h-16 items-center justify-between border-b border-slate-200 dark:border-white/10 bg-white/80 dark:bg-slate-900/80 px-4 backdrop-blur lg:px-8">
            <button @click="open = !open" class="lg:hidden p-2 rounded-md hover:bg-slate-100 dark:hover:bg-white/10">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
            <h1 class="text-lg font-semibold">{{ $title ?? 'Dashboard' }}</h1>
            <div class="flex items-center gap-3">
                <a href="{{ config('app.frontend_url', 'http://localhost:3000') }}" target="_blank"
                   class="hidden sm:inline-flex items-center gap-1 rounded-lg border border-slate-300 dark:border-white/10 px-3 py-1.5 text-sm hover:bg-slate-50 dark:hover:bg-white/5">
                    View site
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                </a>
                <a href="{{ route('profile') }}"
                   class="inline-flex items-center gap-1 rounded-lg border border-slate-300 dark:border-white/10 px-3 py-1.5 text-sm hover:bg-slate-50 dark:hover:bg-white/5">
                    Account
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="rounded-lg bg-slate-900 dark:bg-white/10 px-3 py-1.5 text-sm font-medium text-white">Logout</button>
                </form>
            </div>
        </header>

        @if (session('status'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3500)"
                 class="mx-4 mt-4 rounded-lg bg-emerald-50 dark:bg-emerald-900/30 px-4 py-3 text-sm text-emerald-700 dark:text-emerald-300 lg:mx-8">
                {{ session('status') }}
            </div>
        @endif

        <main class="p-4 lg:p-8">
            {{ $slot }}
        </main>
    </div>
</div>
@livewireScripts
</body>
</html>
