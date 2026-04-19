<x-layouts.app :header="__('Student')" :title="__('Student')">
    <x-slot:sidebarNav>
        <a
            href="{{ route('student.dashboard') }}"
            class="rounded-lg bg-brand-50 px-3 py-2 font-semibold text-brand-800 dark:bg-brand-950/40 dark:text-brand-200"
        >{{ __('Dashboard') }}</a>
    </x-slot:sidebarNav>

    <div class="rounded-2xl border border-zinc-200/80 bg-white/80 p-10 shadow-sm dark:border-zinc-800 dark:bg-zinc-900/60">
        <p class="font-serif text-xl text-zinc-800 dark:text-zinc-100">{{ __('Student workspace') }}</p>
        <p class="mt-2 max-w-lg text-sm leading-relaxed text-zinc-600 dark:text-zinc-400">
            {{ __('You’re signed in. Your courses, schedule, and progress will show up here as the LMS grows.') }}
        </p>
    </div>
</x-layouts.app>
