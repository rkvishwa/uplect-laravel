<x-layouts.app :header="__('Courses')" :title="__('Courses')">
    <x-slot:sidebarNav>
        <x-admin.sidebar active="courses" />
    </x-slot:sidebarNav>

    <div class="flex flex-col gap-6 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-zinc-900 dark:text-zinc-50">{{ __('Course Library') }}</h1>
            <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">{{ __('Manage your platform\'s curriculum, timelines, and live sessions.') }}</p>
        </div>
        <a href="{{ route('admin.courses.create') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-zinc-900 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition-all hover:bg-zinc-800 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-zinc-900 focus:ring-offset-2 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-100 dark:focus:ring-white">
            <svg class="-ml-0.5 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
            {{ __('Add Course') }}
        </a>
    </div>

    <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
        @forelse ($courses as $course)
            <div class="group flex flex-col overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-zinc-200/50 transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-brand-500/5 dark:bg-zinc-900/50 dark:ring-zinc-800">
                <div class="relative aspect-[4/3] w-full overflow-hidden bg-zinc-100 dark:bg-zinc-800">
                    @if ($course->image_path)
                        <img src="{{ asset('storage/'.$course->image_path) }}" alt="" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105" />
                    @else
                        <div class="flex h-full items-center justify-center text-sm font-medium text-zinc-400">
                            <svg class="h-10 w-10 opacity-20" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" /></svg>
                        </div>
                    @endif
                    
                    {{-- Status Badge Overlay --}}
                    <div class="absolute right-3 top-3">
                        @if ($course->status === \App\Models\Course::STATUS_ACTIVE)
                            <span class="inline-flex items-center rounded-full bg-emerald-500/90 px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wide text-white backdrop-blur-sm">{{ __('Active') }}</span>
                        @else
                            <span class="inline-flex items-center rounded-full bg-amber-500/90 px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wide text-white backdrop-blur-sm">{{ __('Draft') }}</span>
                        @endif
                    </div>
                </div>
                
                <div class="flex flex-1 flex-col p-5">
                    <div class="mb-2 flex items-center gap-2">
                        <span class="text-[11px] font-semibold uppercase tracking-wider text-brand-600 dark:text-brand-400">{{ $course->category?->name ?? __('Uncategorized') }}</span>
                    </div>
                    
                    <h3 class="line-clamp-2 text-[15px] font-bold leading-tight text-zinc-900 dark:text-white">
                        <a href="{{ route('admin.courses.edit', $course) }}" class="focus:outline-none">
                            <span class="absolute inset-0" aria-hidden="true"></span>
                            {{ $course->title }}
                        </a>
                    </h3>
                    
                    <div class="mt-auto pt-4">
                        <div class="flex items-center gap-2 text-xs text-zinc-500 dark:text-zinc-400">
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" /></svg>
                            {{ $course->lecturer?->name ?? __('No lecturer') }}
                        </div>
                        
                        <div class="mt-4 flex items-center justify-between border-t border-zinc-100 pt-4 dark:border-zinc-800">
                            <a href="{{ route('admin.courses.timeline.index', $course) }}" class="relative z-10 flex items-center gap-1.5 text-xs font-semibold text-brand-700 transition hover:text-brand-800 dark:text-brand-400 dark:hover:text-brand-300">
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                {{ __('Timeline') }}
                            </a>
                            <a href="{{ route('admin.courses.edit', $course) }}" class="relative z-10 text-xs font-medium text-zinc-500 transition hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-zinc-200">
                                {{ __('Edit') }} &rarr;
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full flex flex-col items-center justify-center rounded-3xl border border-dashed border-zinc-300 bg-zinc-50 py-20 dark:border-zinc-700 dark:bg-zinc-900/50">
                <svg class="h-10 w-10 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5" /></svg>
                <p class="mt-4 text-sm font-medium text-zinc-900 dark:text-zinc-100">{{ __('No courses found') }}</p>
                <p class="mt-1 text-xs text-zinc-500">{{ __('Get started by creating a new course.') }}</p>
                <a href="{{ route('admin.courses.create') }}" class="mt-6 inline-flex rounded-xl bg-zinc-900 px-5 py-2.5 text-sm font-medium text-white shadow-sm hover:bg-zinc-800 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-200">{{ __('New Course') }}</a>
            </div>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $courses->links() }}
    </div>
</x-layouts.app>
