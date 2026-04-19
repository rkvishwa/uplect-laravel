@props([
    'active' => '',
    'pendingEnrollmentsCount' => 0,
])

@php
    $navItems = [
        [
            'name' => 'Dashboard',
            'id' => 'dashboard',
            'route' => route('admin.dashboard'),
            'icon' => '<svg class="h-5 w-5 opacity-75" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" /></svg>',
        ],
        [
            'name' => 'Categories',
            'id' => 'categories',
            'route' => route('admin.categories.index'),
            'icon' => '<svg class="h-5 w-5 opacity-75" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 9.776c.112-.017.227-.026.344-.026h15.812c.117 0 .232.009.344.026m-16.5 0a2.25 2.25 0 00-1.883 2.542l.857 6a2.25 2.25 0 002.227 1.932H19.05a2.25 2.25 0 002.227-1.932l.857-6a2.25 2.25 0 00-1.883-2.542m-16.5 0V6A2.25 2.25 0 016 3.75h3.879a1.5 1.5 0 011.06.44l2.122 2.12a1.5 1.5 0 001.06.44H18A2.25 2.25 0 0120.25 9v.776" /></svg>',
        ],
        [
            'name' => 'Courses',
            'id' => 'courses',
            'route' => route('admin.courses.index'),
            'icon' => '<svg class="h-5 w-5 opacity-75" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5" /></svg>',
        ],
        [
            'name' => 'Enrollments',
            'id' => 'enrollments',
            'route' => route('admin.enrollments.index'),
            'icon' => '<svg class="h-5 w-5 opacity-75" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" /></svg>',
            'badge' => $pendingEnrollmentsCount ?? 0,
        ],
        [
            'name' => 'Lecturers',
            'id' => 'lecturers',
            'route' => route('admin.lecturers.index'),
            'icon' => '<svg class="h-5 w-5 opacity-75" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" /></svg>',
        ],
        [
            'name' => 'Profile',
            'id' => 'profile',
            'route' => route('admin.profile.edit'),
            'icon' => '<svg class="h-5 w-5 opacity-75" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" /></svg>',
        ],
    ];
@endphp

<div class="space-y-1">
    @foreach ($navItems as $item)
        @php
            $isActive = $active === $item['id'];
        @endphp
        <a
            href="{{ $item['route'] }}"
            @class([
                'group flex items-center gap-3 rounded-xl px-3 py-2.5 text-[14px] font-medium transition-all duration-300',
                'bg-brand-50 text-brand-900 shadow-sm shadow-brand-100/50 dark:bg-brand-500/10 dark:text-brand-300 dark:shadow-none' => $isActive,
                'text-zinc-600 hover:bg-zinc-100 hover:text-zinc-900 dark:text-zinc-400 dark:hover:bg-zinc-800/50 dark:hover:text-zinc-50' => !$isActive,
            ])
        >
            <div @class([
                'flex shrink-0 items-center justify-center transition-transform duration-300',
                'text-brand-600 dark:text-brand-400' => $isActive,
                'text-zinc-400 group-hover:text-zinc-600 dark:text-zinc-500 dark:group-hover:text-zinc-300' => !$isActive,
            ])>
                {!! $item['icon'] !!}
            </div>
            
            <span class="flex-1 truncate">{{ __($item['name']) }}</span>

            @if (isset($item['badge']) && $item['badge'] > 0)
                <span @class([
                    'ml-auto inline-flex min-w-[1.5rem] items-center justify-center rounded-full px-1.5 py-0.5 text-[11px] font-bold tracking-wide',
                    'bg-brand-200/50 text-brand-800 dark:bg-brand-400/20 dark:text-brand-300' => $isActive,
                    'bg-brand-100/50 text-brand-600 dark:bg-brand-500/20 dark:text-brand-400' => !$isActive,
                ])>
                    {{ $item['badge'] }}
                </span>
            @endif
        </a>
    @endforeach
</div>
