<x-layouts.app :header="__('Settings')" :title="__('Settings')">
    <x-slot:sidebarNav>
        <x-admin.sidebar active="settings" />
    </x-slot:sidebarNav>

    <x-admin.settings-tabs tab="general" />

    <div class="rounded-2xl border border-dashed border-zinc-200 bg-zinc-50/50 p-8 text-center dark:border-zinc-700 dark:bg-zinc-900/30">
        <h1 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100">{{ __('General') }}</h1>
        <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-400">{{ __('Additional platform settings will appear here. Zoom accounts are under the Zoom tab.') }}</p>
    </div>
</x-layouts.app>
