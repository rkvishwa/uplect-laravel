@props([
    'title' => null,
])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? __('Sign in') }} — {{ config('app.name') }}</title>
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
<body class="min-h-full bg-zinc-50 text-zinc-900 antialiased dark:bg-zinc-950 dark:text-zinc-100">
    <div class="relative min-h-screen lg:grid lg:grid-cols-2">
        <div class="relative hidden overflow-hidden bg-gradient-to-br from-brand-900 via-brand-800 to-brand-600 lg:flex lg:flex-col lg:justify-between lg:p-12">
            <div class="pointer-events-none absolute inset-0 opacity-40" style="background-image:radial-gradient(circle at 20% 20%,rgba(255,255,255,0.15) 0,transparent 45%),radial-gradient(circle at 80% 0%,rgba(251,191,36,0.2) 0,transparent 40%);"></div>
            <div class="pointer-events-none absolute inset-0 bg-[linear-gradient(to_right,rgba(255,255,255,0.06)_1px,transparent_1px),linear-gradient(to_bottom,rgba(255,255,255,0.06)_1px,transparent_1px)] bg-[length:48px_48px]"></div>
            <div class="relative z-10">
                <x-logo class="text-white [&_span:last-child]:text-white" />
                <p class="mt-8 max-w-md font-serif text-3xl leading-tight text-white/95">
                    A calm, focused place for teaching and learning.
                </p>
                <p class="mt-4 max-w-sm text-sm leading-relaxed text-white/70">
                    Private university LMS — structured courses, clear communication, and a premium experience for your academic community.
                </p>
            </div>
            <p class="relative z-10 text-xs text-white/50">&copy; {{ date('Y') }} Uplect</p>
        </div>

        <div class="relative flex min-h-screen flex-col px-4 py-8 sm:px-8">
            <div class="flex justify-end">
                <x-theme-toggle />
            </div>
            <div class="flex flex-1 items-center justify-center py-10">
                <div class="w-full max-w-md space-y-6">
                    <div class="lg:hidden">
                        <x-logo />
                    </div>
                    {{ $slot }}
                </div>
            </div>
        </div>
    </div>
</body>
</html>
