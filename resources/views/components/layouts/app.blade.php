@props([
    'header' => 'Dashboard',
    'title' => null,
])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? $header }} — {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
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
    <div class="flex min-h-screen">
        <aside class="hidden w-64 flex-col border-r border-zinc-200/80 bg-white/90 py-6 backdrop-blur-md dark:border-zinc-800 dark:bg-zinc-900/90 lg:flex">
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

        <div class="flex min-h-screen flex-1 flex-col">
            <header class="sticky top-0 z-20 flex items-center justify-between gap-4 border-b border-zinc-200/80 bg-white/80 px-4 py-3 backdrop-blur-md dark:border-zinc-800 dark:bg-zinc-950/80 lg:px-8">
                <div class="flex items-center gap-3 lg:hidden">
                    <x-logo class="scale-75" />
                </div>
                <h1 class="hidden font-serif text-xl font-semibold tracking-tight text-zinc-900 dark:text-zinc-50 lg:block">
                    {{ $header }}
                </h1>
                <div class="ml-auto flex items-center gap-2">
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
