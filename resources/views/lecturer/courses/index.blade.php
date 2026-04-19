<x-layouts.app :header="__('My courses')" :title="__('My courses')">
    <x-slot:sidebarNav>
        <x-lecturer.sidebar active="courses" />
    </x-slot:sidebarNav>

    <div class="grid gap-4 sm:grid-cols-2">
        @foreach ($courses as $course)
            <div class="rounded-2xl border border-zinc-200 bg-white/90 p-5 dark:border-zinc-800 dark:bg-zinc-900/70">
                <p class="font-semibold">{{ $course->title }}</p>
                <a href="{{ route('lecturer.courses.timeline', $course) }}" class="mt-3 inline-block text-sm font-medium text-brand-700">{{ __('Timeline') }}</a>
            </div>
        @endforeach
    </div>
    <div class="mt-6">{{ $courses->links() }}</div>
</x-layouts.app>
