<x-layouts.app :header="__('Courses')" :title="__('Courses')">
    <x-slot:sidebarNav>
        <x-admin.sidebar active="courses" />
    </x-slot:sidebarNav>

    <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
        <div>
            <p class="font-serif text-xl text-zinc-900 dark:text-zinc-50">{{ __('Courses') }}</p>
            <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-400">{{ __('Manage courses, timeline, and Zoom sessions.') }}</p>
        </div>
        <a href="{{ route('admin.courses.create') }}" class="inline-flex rounded-xl bg-gradient-to-r from-brand-700 to-brand-600 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-brand-600/20">{{ __('Add course') }}</a>
    </div>

    <div class="mt-8 grid gap-6 sm:grid-cols-2 xl:grid-cols-3">
        @forelse ($courses as $course)
            <div class="overflow-hidden rounded-2xl border border-zinc-200/80 bg-white/90 shadow-sm dark:border-zinc-800 dark:bg-zinc-900/70">
                @if ($course->image_path)
                    <img src="{{ asset('storage/'.$course->image_path) }}" alt="" class="h-40 w-full object-cover" />
                @else
                    <div class="flex h-40 items-center justify-center bg-zinc-100 text-sm text-zinc-500 dark:bg-zinc-800">{{ __('No image') }}</div>
                @endif
                <div class="space-y-2 p-5">
                    <p class="font-semibold text-zinc-900 dark:text-zinc-50">{{ $course->title }}</p>
                    <p class="text-xs text-zinc-500">{{ $course->category?->name }} · {{ $course->lecturer?->name }}</p>
                    <p class="text-xs font-medium {{ $course->status === \App\Models\Course::STATUS_ACTIVE ? 'text-emerald-600' : 'text-amber-600' }}">{{ ucfirst($course->status) }}</p>
                    <div class="flex flex-wrap gap-2 pt-2">
                        <a href="{{ route('admin.courses.timeline.index', $course) }}" class="text-sm font-medium text-brand-700 dark:text-brand-400">{{ __('Timeline') }}</a>
                        <a href="{{ route('admin.courses.edit', $course) }}" class="text-sm font-medium text-zinc-600 dark:text-zinc-400">{{ __('Edit') }}</a>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-sm text-zinc-500">{{ __('No courses yet.') }}</p>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $courses->links() }}
    </div>
</x-layouts.app>
