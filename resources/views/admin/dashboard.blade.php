<x-layouts.app :header="__('Admin')" :title="__('Admin')">
    <x-slot:sidebarNav>
        <x-admin.sidebar active="dashboard" />
    </x-slot:sidebarNav>

    <div class="rounded-2xl border border-zinc-200/80 bg-white/80 p-10 shadow-sm dark:border-zinc-800 dark:bg-zinc-900/60">
        <p class="font-serif text-xl text-zinc-800 dark:text-zinc-100">{{ __('Admin workspace') }}</p>
        <p class="mt-2 max-w-lg text-sm leading-relaxed text-zinc-600 dark:text-zinc-400">
            {{ __('Your dashboard is ready. Course management, analytics, and more will appear here in upcoming milestones.') }}
        </p>
    </div>
</x-layouts.app>
