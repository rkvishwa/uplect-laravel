@php
    $pending = $stats['enrollments_pending'] ?? 0;
@endphp

<x-layouts.app :header="__('Admin')" :title="__('Dashboard')">
    <x-slot:sidebarNav>
        <x-admin.sidebar active="dashboard" pendingEnrollmentsCount="{{ $pending }}" />
    </x-slot:sidebarNav>

    <div class="space-y-6 lg:space-y-8">
        {{-- Intro Banner --}}
        <div class="relative overflow-hidden rounded-3xl bg-white p-8 shadow-sm ring-1 ring-zinc-200/50 dark:bg-zinc-900/50 dark:ring-zinc-800 sm:p-10">
            <!-- Decorative background elements -->
            <div class="absolute -right-20 -top-20 -z-10 h-64 w-64 rounded-full bg-brand-50 blur-3xl dark:bg-brand-900/20"></div>
            <div class="absolute -bottom-20 -right-4 -z-10 h-40 w-40 rounded-full bg-accent-50 blur-2xl dark:bg-accent-900/20"></div>
            
            <div class="relative z-10 flex flex-col items-start lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <p class="inline-flex items-center gap-2 rounded-full bg-brand-50 px-3 py-1 text-xs font-semibold uppercase tracking-widest text-brand-700 dark:bg-brand-500/10 dark:text-brand-300">
                        <span class="relative flex h-2 w-2">
                            <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-brand-400 opacity-75"></span>
                            <span class="relative inline-flex h-2 w-2 rounded-full bg-brand-500"></span>
                        </span>
                        {{ __('Overview') }}
                    </p>
                    <h2 class="mt-4 text-3xl font-bold tracking-tight text-zinc-900 dark:text-white sm:text-4xl">
                        {{ __('Welcome back, :name', ['name' => auth()->user()->name]) }}
                    </h2>
                    <p class="mt-3 max-w-2xl text-[15px] leading-relaxed text-zinc-600 dark:text-zinc-400">
                        {{ __('Manage categories, courses, enrollments, and lecturers from one central command center. Stay on top of your platform\'s activity today.') }}
                    </p>
                </div>
                <div class="mt-8 shrink-0 lg:mt-0 lg:pl-10">
                    <div class="flex items-center gap-3">
                        <a href="{{ route('admin.courses.create') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-zinc-900 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-zinc-800 focus:outline-none focus:ring-2 focus:ring-zinc-900 focus:ring-offset-2 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-100 dark:focus:ring-white">
                            <svg class="-ml-0.5 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                            {{ __('New Course') }}
                        </a>
                        @if($pending > 0)
                        <a href="{{ route('admin.enrollments.index', ['status' => 'pending']) }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-amber-100 px-5 py-2.5 text-sm font-semibold text-amber-800 shadow-sm transition hover:bg-amber-200 dark:bg-amber-500/20 dark:text-amber-300 dark:hover:bg-amber-500/30">
                            {{ __('Review Pending') }}
                            <span class="flex h-5 w-5 items-center justify-center rounded-full bg-amber-800/20 text-[10px] font-bold text-amber-900 dark:text-amber-200">{{ $pending }}</span>
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Stats Grid --}}
        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <a href="{{ route('admin.courses.index') }}" class="group relative overflow-hidden rounded-2xl bg-white p-6 shadow-sm ring-1 ring-zinc-200/50 transition duration-300 hover:scale-[1.02] hover:shadow-md dark:bg-zinc-900/50 dark:ring-zinc-800">
                <div class="flex items-center gap-4">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-brand-50 text-brand-600 dark:bg-brand-500/10 dark:text-brand-400">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5" /></svg>
                    </div>
                    <div>
                        <p class="text-[13px] font-medium tracking-wide text-zinc-500 dark:text-zinc-400">{{ __('Total Courses') }}</p>
                        <div class="mt-0.5 flex items-baseline gap-2">
                            <p class="text-2xl font-bold tabular-nums text-zinc-900 dark:text-white">{{ number_format($stats['courses']) }}</p>
                            <span class="text-xs font-semibold text-emerald-600 dark:text-emerald-400">{{ __(':count active', ['count' => number_format($stats['courses_active'])]) }}</span>
                        </div>
                    </div>
                </div>
                <div class="absolute inset-x-0 bottom-0 h-1 bg-gradient-to-r from-brand-400 to-brand-600 transform scale-x-0 transition-transform duration-300 group-hover:scale-x-100"></div>
            </a>

            <a href="{{ route('admin.categories.index') }}" class="group relative overflow-hidden rounded-2xl bg-white p-6 shadow-sm ring-1 ring-zinc-200/50 transition duration-300 hover:scale-[1.02] hover:shadow-md dark:bg-zinc-900/50 dark:ring-zinc-800">
                <div class="flex items-center gap-4">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-orange-50 text-orange-600 dark:bg-orange-500/10 dark:text-orange-400">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 9.776c.112-.017.227-.026.344-.026h15.812c.117 0 .232.009.344.026m-16.5 0a2.25 2.25 0 00-1.883 2.542l.857 6a2.25 2.25 0 002.227 1.932H19.05a2.25 2.25 0 002.227-1.932l.857-6a2.25 2.25 0 00-1.883-2.542m-16.5 0V6A2.25 2.25 0 016 3.75h3.879a1.5 1.5 0 011.06.44l2.122 2.12a1.5 1.5 0 001.06.44H18A2.25 2.25 0 0120.25 9v.776" /></svg>
                    </div>
                    <div>
                        <p class="text-[13px] font-medium tracking-wide text-zinc-500 dark:text-zinc-400">{{ __('Categories') }}</p>
                        <div class="mt-0.5 flex items-baseline gap-2">
                            <p class="text-2xl font-bold tabular-nums text-zinc-900 dark:text-white">{{ number_format($stats['categories']) }}</p>
                        </div>
                    </div>
                </div>
                <div class="absolute inset-x-0 bottom-0 h-1 bg-gradient-to-r from-orange-400 to-orange-500 transform scale-x-0 transition-transform duration-300 group-hover:scale-x-100"></div>
            </a>

            <div class="group relative overflow-hidden rounded-2xl bg-white p-6 shadow-sm ring-1 ring-zinc-200/50 dark:bg-zinc-900/50 dark:ring-zinc-800">
                <div class="flex items-center gap-4">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-sky-50 text-sky-600 dark:bg-sky-500/10 dark:text-sky-400">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" /></svg>
                    </div>
                    <div>
                        <p class="text-[13px] font-medium tracking-wide text-zinc-500 dark:text-zinc-400">{{ __('Students') }}</p>
                        <div class="mt-0.5 flex items-baseline gap-2">
                            <p class="text-2xl font-bold tabular-nums text-zinc-900 dark:text-white">{{ number_format($stats['students']) }}</p>
                            <span class="text-xs text-zinc-400">{{ __('Registered') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <a href="{{ route('admin.lecturers.index') }}" class="group relative overflow-hidden rounded-2xl bg-white p-6 shadow-sm ring-1 ring-zinc-200/50 transition duration-300 hover:scale-[1.02] hover:shadow-md dark:bg-zinc-900/50 dark:ring-zinc-800">
                <div class="flex items-center gap-4">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-violet-50 text-violet-600 dark:bg-violet-500/10 dark:text-violet-400">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                    </div>
                    <div>
                        <p class="text-[13px] font-medium tracking-wide text-zinc-500 dark:text-zinc-400">{{ __('Lecturers') }}</p>
                        <div class="mt-0.5 flex items-baseline gap-2">
                            <p class="text-2xl font-bold tabular-nums text-zinc-900 dark:text-white">{{ number_format($stats['lecturers']) }}</p>
                        </div>
                    </div>
                </div>
                <div class="absolute inset-x-0 bottom-0 h-1 bg-gradient-to-r from-violet-400 to-violet-500 transform scale-x-0 transition-transform duration-300 group-hover:scale-x-100"></div>
            </a>
        </div>

        {{-- Charts --}}
        <div class="grid gap-6 lg:grid-cols-3">
            <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-zinc-200/50 dark:bg-zinc-900/50 dark:ring-zinc-800 lg:col-span-2">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="font-semibold text-zinc-900 dark:text-zinc-100">{{ __('Enrollment Activity') }}</h3>
                        <p class="mt-0.5 text-sm text-zinc-500 dark:text-zinc-400">{{ __('New enrollments over the past six months.') }}</p>
                    </div>
                </div>
                <div class="mt-8 h-64 w-full min-w-0">
                    <canvas id="chart-enrollment-trend" class="max-h-64"></canvas>
                </div>
            </div>

            <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-zinc-200/50 dark:bg-zinc-900/50 dark:ring-zinc-800">
                <h3 class="font-semibold text-zinc-900 dark:text-zinc-100">{{ __('Enrollment Status') }}</h3>
                <p class="mt-0.5 text-sm text-zinc-500 dark:text-zinc-400">{{ __('Overall breakdown') }}</p>
                <div class="mt-8 flex h-64 w-full items-center justify-center min-w-0">
                    <canvas id="chart-enrollment-status" class="mx-auto max-h-64 max-w-full"></canvas>
                </div>
            </div>
            
            <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-zinc-200/50 dark:bg-zinc-900/50 dark:ring-zinc-800 lg:col-span-3">
                <h3 class="font-semibold text-zinc-900 dark:text-zinc-100">{{ __('Courses per Category') }}</h3>
                <div class="mt-6 h-72 w-full min-w-0">
                    <canvas id="chart-courses-by-category" class="max-h-72"></canvas>
                </div>
            </div>
        </div>
    </div>

    <x-slot:scripts>
        <script type="application/json" id="admin-dashboard-chart-data">@json($chartData)</script>
        @vite(['resources/js/admin-dashboard.js'])
    </x-slot:scripts>
</x-layouts.app>
