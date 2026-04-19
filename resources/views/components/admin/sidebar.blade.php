@props([
    'active' => '',
])

<a
    href="{{ route('admin.dashboard') }}"
    @class([
        'rounded-lg px-3 py-2 transition',
        'bg-brand-50 font-semibold text-brand-800 dark:bg-brand-950/40 dark:text-brand-200' => $active === 'dashboard',
        'text-zinc-600 hover:bg-zinc-100 dark:text-zinc-400 dark:hover:bg-zinc-800' => $active !== 'dashboard',
    ])
>{{ __('Dashboard') }}</a>
<a
    href="{{ route('admin.categories.index') }}"
    @class([
        'rounded-lg px-3 py-2 transition',
        'bg-brand-50 font-semibold text-brand-800 dark:bg-brand-950/40 dark:text-brand-200' => $active === 'categories',
        'text-zinc-600 hover:bg-zinc-100 dark:text-zinc-400 dark:hover:bg-zinc-800' => $active !== 'categories',
    ])
>{{ __('Categories') }}</a>
<a
    href="{{ route('admin.courses.index') }}"
    @class([
        'rounded-lg px-3 py-2 transition',
        'bg-brand-50 font-semibold text-brand-800 dark:bg-brand-950/40 dark:text-brand-200' => $active === 'courses',
        'text-zinc-600 hover:bg-zinc-100 dark:text-zinc-400 dark:hover:bg-zinc-800' => $active !== 'courses',
    ])
>{{ __('Courses') }}</a>
<a
    href="{{ route('admin.enrollments.index') }}"
    @class([
        'rounded-lg px-3 py-2 transition',
        'bg-brand-50 font-semibold text-brand-800 dark:bg-brand-950/40 dark:text-brand-200' => $active === 'enrollments',
        'text-zinc-600 hover:bg-zinc-100 dark:text-zinc-400 dark:hover:bg-zinc-800' => $active !== 'enrollments',
    ])
>
    {{ __('Enrollments') }}
    @if (($pendingEnrollmentsCount ?? 0) > 0)
        <span class="ml-1.5 inline-flex min-w-[1.25rem] items-center justify-center rounded-full bg-accent-400/90 px-1.5 py-0.5 text-[10px] font-bold text-zinc-900">{{ $pendingEnrollmentsCount }}</span>
    @endif
</a>
<a
    href="{{ route('admin.lecturers.index') }}"
    @class([
        'rounded-lg px-3 py-2 transition',
        'bg-brand-50 font-semibold text-brand-800 dark:bg-brand-950/40 dark:text-brand-200' => $active === 'lecturers',
        'text-zinc-600 hover:bg-zinc-100 dark:text-zinc-400 dark:hover:bg-zinc-800' => $active !== 'lecturers',
    ])
>{{ __('Lecturers') }}</a>
