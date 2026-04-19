@php
    $pending = $stats['enrollments_pending'] ?? 0;
@endphp

<x-layouts.app :header="__('Admin')" :title="__('Dashboard')">
    <x-slot:sidebarNav>
        <x-admin.sidebar active="dashboard" />
    </x-slot:sidebarNav>

    <div class="space-y-8 lg:space-y-10">
        {{-- Intro --}}
        <div class="rounded-3xl border border-zinc-200/90 bg-gradient-to-br from-white via-white to-brand-50/40 p-8 shadow-[0_4px_24px_rgba(15,23,42,0.04)] dark:border-zinc-800 dark:from-zinc-900 dark:via-zinc-900 dark:to-brand-950/30 dark:shadow-none sm:p-10">
            <p class="text-[11px] font-bold uppercase tracking-[0.2em] text-brand-700 dark:text-brand-300">{{ __('Overview') }}</p>
            <h2 class="mt-2 text-2xl font-extrabold tracking-tight text-zinc-900 dark:text-white sm:text-3xl">
                {{ __('Welcome back, :name', ['name' => auth()->user()->name]) }}
            </h2>
            <p class="mt-2 max-w-2xl text-sm leading-relaxed text-zinc-600 dark:text-zinc-400">
                {{ __('Manage categories, courses, enrollments, and lecturers from one place.') }}
            </p>
        </div>

        {{-- Stats --}}
        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <a href="{{ route('admin.courses.index') }}" class="group rounded-2xl border border-zinc-200/90 bg-white p-5 shadow-sm transition hover:border-brand-200 hover:shadow-md dark:border-zinc-800 dark:bg-zinc-900/80 dark:hover:border-brand-900">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="text-xs font-medium uppercase tracking-wide text-zinc-500 dark:text-zinc-400">{{ __('Courses') }}</p>
                        <p class="mt-2 text-3xl font-bold tabular-nums text-zinc-900 dark:text-white">{{ number_format($stats['courses']) }}</p>
                        <p class="mt-1 text-xs text-zinc-500 dark:text-zinc-400">{{ __(':count active', ['count' => number_format($stats['courses_active'])]) }}</p>
                    </div>
                    <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-brand-600/10 text-brand-600 dark:bg-brand-500/20 dark:text-brand-300">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" aria-hidden="true"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/><path d="M8 7h8M8 11h6"/></svg>
                    </span>
                </div>
            </a>

            <a href="{{ route('admin.categories.index') }}" class="group rounded-2xl border border-zinc-200/90 bg-white p-5 shadow-sm transition hover:border-brand-200 hover:shadow-md dark:border-zinc-800 dark:bg-zinc-900/80 dark:hover:border-brand-900">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="text-xs font-medium uppercase tracking-wide text-zinc-500 dark:text-zinc-400">{{ __('Categories') }}</p>
                        <p class="mt-2 text-3xl font-bold tabular-nums text-zinc-900 dark:text-white">{{ number_format($stats['categories']) }}</p>
                        <p class="mt-1 text-xs text-zinc-500 group-hover:text-brand-600 dark:text-zinc-400 dark:group-hover:text-brand-300">{{ __('Organize courses') }} →</p>
                    </div>
                    <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-emerald-500/10 text-emerald-600 dark:bg-emerald-500/20 dark:text-emerald-300">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" aria-hidden="true"><path d="M3 3h7v7H3zM14 3h7v7h-7zM14 14h7v7h-7zM3 14h7v7H3z"/></svg>
                    </span>
                </div>
            </a>

            <div class="rounded-2xl border border-zinc-200/90 bg-white p-5 shadow-sm dark:border-zinc-800 dark:bg-zinc-900/80">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="text-xs font-medium uppercase tracking-wide text-zinc-500 dark:text-zinc-400">{{ __('Students') }}</p>
                        <p class="mt-2 text-3xl font-bold tabular-nums text-zinc-900 dark:text-white">{{ number_format($stats['students']) }}</p>
                        <p class="mt-1 text-xs text-zinc-500 dark:text-zinc-400">{{ __('Registered accounts') }}</p>
                    </div>
                    <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-sky-500/10 text-sky-600 dark:bg-sky-500/20 dark:text-sky-300">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" aria-hidden="true"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    </span>
                </div>
            </div>

            <a href="{{ route('admin.lecturers.index') }}" class="group rounded-2xl border border-zinc-200/90 bg-white p-5 shadow-sm transition hover:border-brand-200 hover:shadow-md dark:border-zinc-800 dark:bg-zinc-900/80 dark:hover:border-brand-900">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="text-xs font-medium uppercase tracking-wide text-zinc-500 dark:text-zinc-400">{{ __('Lecturers') }}</p>
                        <p class="mt-2 text-3xl font-bold tabular-nums text-zinc-900 dark:text-white">{{ number_format($stats['lecturers']) }}</p>
                        <p class="mt-1 text-xs text-zinc-500 group-hover:text-brand-600 dark:text-zinc-400 dark:group-hover:text-brand-300">{{ __('Manage profiles') }} →</p>
                    </div>
                    <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-violet-500/10 text-violet-600 dark:bg-violet-500/20 dark:text-violet-300">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" aria-hidden="true"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    </span>
                </div>
            </a>
        </div>

        {{-- Charts --}}
        <div class="space-y-6">
            <div>
                <h3 class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">{{ __('Analytics') }}</h3>
                <p class="mt-1 text-xs text-zinc-500 dark:text-zinc-400">{{ __('Last six months and current breakdowns.') }}</p>
            </div>

            <div class="grid gap-6 lg:grid-cols-3">
                <div class="rounded-2xl border border-zinc-200/90 bg-white p-5 shadow-sm dark:border-zinc-800 dark:bg-zinc-900/80 lg:col-span-2">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400">{{ __('Enrollment trend') }}</p>
                            <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-400">{{ __('New enrollments by month') }}</p>
                        </div>
                    </div>
                    <div class="mt-4 h-64 w-full min-w-0">
                        <canvas id="chart-enrollment-trend" class="max-h-64"></canvas>
                    </div>
                </div>

                <div class="rounded-2xl border border-zinc-200/90 bg-white p-5 shadow-sm dark:border-zinc-800 dark:bg-zinc-900/80">
                    <p class="text-xs font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400">{{ __('Enrollments by status') }}</p>
                    <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-400">{{ __('All time') }}</p>
                    <div class="mt-4 flex h-64 w-full items-center justify-center min-w-0">
                        <canvas id="chart-enrollment-status" class="mx-auto max-h-64 max-w-full"></canvas>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-zinc-200/90 bg-white p-5 shadow-sm dark:border-zinc-800 dark:bg-zinc-900/80">
                <p class="text-xs font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400">{{ __('Courses per category') }}</p>
                <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-400">{{ __('Published and draft courses') }}</p>
                <div class="mt-4 h-72 w-full min-w-0">
                    <canvas id="chart-courses-by-category" class="max-h-72"></canvas>
                </div>
            </div>
        </div>

        {{-- Enrollments row --}}
        <div class="grid gap-4 lg:grid-cols-2">
            <a href="{{ route('admin.enrollments.index') }}" @class([
                'block rounded-2xl border p-6 shadow-sm transition hover:shadow-md dark:bg-zinc-900/80',
                'border-amber-200/90 bg-gradient-to-br from-amber-50/80 to-white dark:from-amber-950/30 dark:to-zinc-900 dark:border-amber-900/50' => $pending > 0,
                'border-zinc-200/90 bg-white dark:border-zinc-800' => $pending === 0,
            ])>
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400">{{ __('Pending enrollments') }}</p>
                        <p class="mt-2 text-4xl font-bold tabular-nums text-zinc-900 dark:text-white">{{ number_format($pending) }}</p>
                        <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-400">{{ __('Awaiting review') }}</p>
                    </div>
                    @if ($pending > 0)
                        <span class="rounded-full bg-amber-500 px-3 py-1 text-xs font-bold text-white">{{ __('Action needed') }}</span>
                    @endif
                </div>
            </a>

            <div class="rounded-2xl border border-zinc-200/90 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900/80">
                <p class="text-xs font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400">{{ __('Active enrollments') }}</p>
                <p class="mt-2 text-4xl font-bold tabular-nums text-zinc-900 dark:text-white">{{ number_format($stats['enrollments_active']) }}</p>
                <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-400">{{ __('Students currently enrolled in courses') }}</p>
            </div>
        </div>

        {{-- Quick actions --}}
        <div>
            <p class="mb-4 text-sm font-semibold text-zinc-900 dark:text-zinc-100">{{ __('Quick actions') }}</p>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('admin.categories.create') }}" class="inline-flex items-center gap-2 rounded-full border border-zinc-200 bg-white px-4 py-2.5 text-sm font-medium text-zinc-800 shadow-sm transition hover:border-brand-300 hover:bg-brand-50/80 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-100 dark:hover:border-brand-500 dark:hover:bg-brand-950/40">
                    <span class="text-brand-600 dark:text-brand-400">+</span> {{ __('New category') }}
                </a>
                <a href="{{ route('admin.courses.create') }}" class="inline-flex items-center gap-2 rounded-full border border-zinc-200 bg-white px-4 py-2.5 text-sm font-medium text-zinc-800 shadow-sm transition hover:border-brand-300 hover:bg-brand-50/80 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-100 dark:hover:border-brand-500 dark:hover:bg-brand-950/40">
                    <span class="text-brand-600 dark:text-brand-400">+</span> {{ __('New course') }}
                </a>
                <a href="{{ route('admin.enrollments.index') }}" class="inline-flex items-center gap-2 rounded-full border border-zinc-200 bg-white px-4 py-2.5 text-sm font-medium text-zinc-800 shadow-sm transition hover:border-brand-300 hover:bg-brand-50/80 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-100 dark:hover:border-brand-500 dark:hover:bg-brand-950/40">
                    {{ __('Review enrollments') }}
                </a>
            </div>
        </div>
    </div>

    <x-slot:scripts>
        <script type="application/json" id="admin-dashboard-chart-data">@json($chartData)</script>
        @vite(['resources/js/admin-dashboard.js'])
    </x-slot:scripts>
</x-layouts.app>
