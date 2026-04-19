@props([
    'header' => 'Dashboard',
    'title' => null,
])

@php
    $roleEyebrow = match (auth()->user()->role ?? '') {
        \App\Models\User::ROLE_ADMIN => __('Admin'),
        \App\Models\User::ROLE_LECTURER => __('Lecturer'),
        \App\Models\User::ROLE_STUDENT => __('Student'),
        default => config('app.name'),
    };
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? $header }} — {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        try {
            const t = localStorage.getItem('theme');
            if (t === 'dark' || (!t && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            }
        } catch (e) {}
    </script>
    <style>[x-cloak]{display:none!important}</style>
</head>
<body class="min-h-full bg-zinc-100 text-zinc-900 antialiased dark:bg-zinc-950 dark:text-zinc-100">
    <div class="min-h-screen">
        <aside class="fixed inset-y-0 left-0 z-40 hidden w-64 flex-col overflow-y-auto border-r border-zinc-200/80 bg-white/90 py-6 backdrop-blur-md dark:border-zinc-800 dark:bg-zinc-900/90 lg:flex">
            <div class="px-6">
                <x-logo class="scale-90" />
            </div>
            <nav class="mt-10 flex flex-1 flex-col gap-1 px-3 text-sm font-medium">
                {{ $sidebarNav ?? '' }}
            </nav>
            <div class="px-6 pt-4 text-xs text-zinc-500 dark:text-zinc-400">
                Signed in as<br>
                <span class="font-semibold text-zinc-800 dark:text-zinc-200">{{ auth()->user()->name }}</span>
            </div>
        </aside>

        <div class="flex min-h-screen flex-col lg:pl-64">
            <header class="sticky top-0 z-30 flex w-full items-center justify-between gap-4 border-b border-zinc-200/80 bg-white/90 px-4 py-3.5 shadow-[0_1px_0_0_rgba(15,23,42,0.04)] backdrop-blur-md dark:border-zinc-800 dark:bg-zinc-950/90 dark:shadow-[0_1px_0_0_rgba(0,0,0,0.35)] lg:px-8">
                {{-- Mobile: mark + page title --}}
                <div class="flex min-w-0 flex-1 items-stretch gap-3 lg:hidden">
                    <x-logo variant="mark" class="shrink-0 self-center" />
                    <div class="flex min-w-0 flex-1 flex-col justify-center border-l border-zinc-200/90 pl-3 dark:border-zinc-700/90">
                        <p class="truncate text-[11px] font-semibold uppercase tracking-[0.16em] text-brand-700 dark:text-brand-300">
                            {{ $roleEyebrow }}
                        </p>
                        <p class="mt-0.5 truncate text-[15px] font-semibold leading-snug tracking-tight text-zinc-900 dark:text-zinc-50">
                            {{ $header }}
                        </p>
                    </div>
                </div>

                {{-- Desktop: accent + title --}}
                <div class="hidden min-w-0 flex-1 items-center gap-4 lg:flex">
                    <span class="h-10 w-1 shrink-0 rounded-full bg-gradient-to-b from-brand-500 via-brand-600 to-brand-700 shadow-sm dark:from-brand-400 dark:via-brand-500 dark:to-brand-600" aria-hidden="true"></span>
                    <div class="min-w-0 py-0.5">
                        <p class="text-[11px] font-semibold uppercase tracking-[0.18em] text-brand-700 dark:text-brand-300">
                            {{ $roleEyebrow }}
                        </p>
                        <h1 class="mt-0.5 truncate text-xl font-semibold tracking-tight text-zinc-900 dark:text-zinc-50">
                            {{ $header }}
                        </h1>
                    </div>
                </div>

                <div class="flex shrink-0 items-center gap-2">
                    <x-theme-toggle />
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="rounded-xl border border-zinc-200 bg-white px-4 py-2 text-sm font-medium text-zinc-700 shadow-sm transition hover:border-brand-300 hover:text-brand-800 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-200 dark:hover:border-brand-500">
                            {{ __('Sign out') }}
                        </button>
                    </form>
                </div>
            </header>

            <main class="flex-1 px-4 py-8 lg:px-10">
                <x-flash />
                <div class="mt-6">
                    {{ $slot }}
                </div>
            </main>
        </div>
    </div>
    @isset($scripts)
        {!! $scripts !!}
    @endisset
</body>
</html>
