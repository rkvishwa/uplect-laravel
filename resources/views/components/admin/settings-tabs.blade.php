@props([
    'tab' => 'zoom',
])

<nav class="mb-8 flex flex-wrap gap-2 border-b border-zinc-200 pb-1 dark:border-zinc-800" aria-label="{{ __('Settings sections') }}">
    <a
        href="{{ route('admin.settings.zoom-accounts.index') }}"
        @class([
            'inline-flex items-center rounded-t-lg px-4 py-2.5 text-sm font-semibold transition-colors',
            'border-b-2 border-brand-600 text-brand-700 dark:border-brand-400 dark:text-brand-300' => $tab === 'zoom',
            'text-zinc-600 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-zinc-100' => $tab !== 'zoom',
        ])
    >
        {{ __('Zoom') }}
    </a>
    <a
        href="{{ route('admin.settings.general') }}"
        @class([
            'inline-flex items-center rounded-t-lg px-4 py-2.5 text-sm font-semibold transition-colors',
            'border-b-2 border-brand-600 text-brand-700 dark:border-brand-400 dark:text-brand-300' => $tab === 'general',
            'text-zinc-600 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-zinc-100' => $tab !== 'general',
        ])
    >
        {{ __('General') }}
    </a>
</nav>
