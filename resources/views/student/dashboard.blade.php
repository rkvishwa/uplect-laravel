<x-layouts.app :header="__('Student')" :title="__('Student')">
    <x-slot:sidebarNav>
        <x-student.sidebar active="dashboard" />
    </x-slot:sidebarNav>

    <div class="rounded-2xl border border-zinc-200/80 bg-white/80 p-10 shadow-sm dark:border-zinc-800 dark:bg-zinc-900/60">
        <p class="font-sans text-xl text-zinc-800 dark:text-zinc-100">{{ __('Student workspace') }}</p>
        <p class="mt-2 max-w-lg text-sm leading-relaxed text-zinc-600 dark:text-zinc-400">
            {{ __('Browse the catalog, enroll in courses, and follow your combined timeline.') }}
        </p>
        <div class="mt-6 flex flex-wrap gap-3">
            <a href="{{ route('student.catalog.index') }}" class="rounded-xl bg-brand-600 px-4 py-2 text-sm font-semibold text-white">{{ __('Catalog') }}</a>
            <a href="{{ route('student.timeline.index') }}" class="rounded-xl border border-zinc-200 px-4 py-2 text-sm font-semibold dark:border-zinc-700">{{ __('Timeline') }}</a>
        </div>
    </div>
</x-layouts.app>
