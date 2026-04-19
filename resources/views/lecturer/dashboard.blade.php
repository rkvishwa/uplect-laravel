<x-layouts.app :header="__('Lecturer')" :title="__('Lecturer')">
    <x-slot:sidebarNav>
        <a
            href="{{ route('lecturer.dashboard') }}"
            class="rounded-lg bg-brand-50 px-3 py-2 font-semibold text-brand-800 dark:bg-brand-950/40 dark:text-brand-200"
        >{{ __('Dashboard') }}</a>
    </x-slot:sidebarNav>

    <div class="rounded-2xl border border-zinc-200/80 bg-white/80 p-10 shadow-sm dark:border-zinc-800 dark:bg-zinc-900/60">
        <p class="font-serif text-xl text-zinc-800 dark:text-zinc-100">{{ __('Lecturer workspace') }}</p>
        <p class="mt-2 max-w-lg text-sm leading-relaxed text-zinc-600 dark:text-zinc-400">
            {{ __('Your teaching dashboard is ready. Course authoring, assignments, and grading will connect here next.') }}
        </p>
    </div>
</x-layouts.app>
