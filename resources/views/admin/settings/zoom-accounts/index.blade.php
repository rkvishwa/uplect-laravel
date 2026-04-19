<x-layouts.app :header="__('Settings')" :title="__('Settings')">
    <x-slot:sidebarNav>
        <x-admin.sidebar active="settings" />
    </x-slot:sidebarNav>

    <x-admin.settings-tabs tab="zoom" />

    <div class="flex flex-col gap-6 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-zinc-900 dark:text-zinc-50">{{ __('Zoom accounts') }}</h1>
            <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">{{ __('Manage Server-to-Server OAuth credentials and host users for Zoom meetings.') }}</p>
        </div>
        <a href="{{ route('admin.settings.zoom-accounts.create') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-zinc-900 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition-all hover:bg-zinc-800 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-zinc-900 focus:ring-offset-2 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-100 dark:focus:ring-white">
            <svg class="-ml-0.5 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
            {{ __('Add Zoom account') }}
        </a>
    </div>

    <div class="mt-8 overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-zinc-200/50 dark:bg-zinc-900/50 dark:ring-zinc-800">
        <div class="overflow-x-auto">
            <table class="min-w-full text-left text-sm whitespace-nowrap">
                <thead class="border-b border-zinc-200/50 bg-zinc-50/50 dark:border-zinc-800 dark:bg-zinc-900/50">
                    <tr>
                        <th class="px-6 py-4 font-semibold text-zinc-900 dark:text-zinc-100">{{ __('Name') }}</th>
                        <th class="px-6 py-4 font-semibold text-zinc-900 dark:text-zinc-100">{{ __('Account ID') }}</th>
                        <th class="px-6 py-4 font-semibold text-zinc-900 dark:text-zinc-100">{{ __('Host') }}</th>
                        <th class="px-6 py-4 font-semibold text-zinc-900 dark:text-zinc-100">{{ __('Status') }}</th>
                        <th class="px-6 py-4 text-right font-semibold text-zinc-900 dark:text-zinc-100">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800/80">
                    @forelse ($accounts as $account)
                        @php
                            $aid = (string) $account->account_id;
                            $masked = strlen($aid) > 4 ? \Illuminate\Support\Str::mask($aid, '*', 0, strlen($aid) - 4) : str_repeat('*', strlen($aid));
                        @endphp
                        <tr class="transition-colors hover:bg-zinc-50/50 dark:hover:bg-zinc-800/30">
                            <td class="px-6 py-4">
                                <div class="flex flex-col gap-1">
                                    <span class="font-medium text-zinc-900 dark:text-zinc-100">{{ $account->name }}</span>
                                    @if ($account->is_default)
                                        <span class="inline-flex w-fit items-center rounded-full bg-brand-50 px-2 py-0.5 text-[11px] font-semibold text-brand-800 dark:bg-brand-500/15 dark:text-brand-300">{{ __('Default') }}</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 font-mono text-xs text-zinc-600 dark:text-zinc-400">{{ $masked }}</td>
                            <td class="px-6 py-4 text-zinc-600 dark:text-zinc-300">{{ $account->host_user_id }}</td>
                            <td class="px-6 py-4">
                                @if ($account->is_active)
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400">
                                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                        {{ __('Active') }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-zinc-100 px-2.5 py-1 text-xs font-semibold text-zinc-700 dark:bg-zinc-800 dark:text-zinc-400">
                                        <span class="h-1.5 w-1.5 rounded-full bg-zinc-400"></span>
                                        {{ __('Inactive') }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex flex-wrap items-center justify-end gap-2 text-xs sm:gap-3 sm:text-sm">
                                    @if (! $account->is_default)
                                        <form method="POST" action="{{ route('admin.settings.zoom-accounts.default', $account) }}">
                                            @csrf
                                            <button type="submit" class="font-medium text-brand-600 transition hover:text-brand-700 dark:text-brand-400 dark:hover:text-brand-300">{{ __('Make default') }}</button>
                                        </form>
                                        <span class="hidden text-zinc-300 sm:inline dark:text-zinc-700">|</span>
                                    @endif
                                    @if ($account->is_active)
                                        <form method="POST" action="{{ route('admin.settings.zoom-accounts.deactivate', $account) }}">
                                            @csrf
                                            <button type="submit" class="font-medium text-zinc-500 transition hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-zinc-200">{{ __('Deactivate') }}</button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('admin.settings.zoom-accounts.activate', $account) }}">
                                            @csrf
                                            <button type="submit" class="font-medium text-emerald-600 transition hover:text-emerald-700 dark:text-emerald-400 dark:hover:text-emerald-300">{{ __('Activate') }}</button>
                                        </form>
                                    @endif
                                    <span class="hidden text-zinc-300 sm:inline dark:text-zinc-700">|</span>
                                    <a href="{{ route('admin.settings.zoom-accounts.edit', $account) }}" class="font-medium text-brand-600 transition hover:text-brand-700 dark:text-brand-400 dark:hover:text-brand-300">{{ __('Edit') }}</a>
                                    <span class="hidden text-zinc-300 sm:inline dark:text-zinc-700">|</span>
                                    <form
                                        method="POST"
                                        action="{{ route('admin.settings.zoom-accounts.destroy', $account) }}"
                                        data-confirm="{{ __('Delete this Zoom account?') }}"
                                        data-confirm-ok="{{ __('Delete') }}"
                                        data-confirm-cancel="{{ __('Cancel') }}"
                                    >
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="font-medium text-red-600 transition hover:text-red-700 dark:text-red-400 dark:hover:text-red-300">{{ __('Delete') }}</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-16 text-center">
                                <p class="font-medium text-zinc-900 dark:text-zinc-100">{{ __('No Zoom accounts yet') }}</p>
                                <p class="mt-1 text-sm text-zinc-500">{{ __('Add an account to create meeting links from the course timeline.') }}</p>
                                <a href="{{ route('admin.settings.zoom-accounts.create') }}" class="mt-4 inline-block text-sm font-semibold text-brand-600 hover:text-brand-700">{{ __('Add Zoom account') }} →</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">
        {{ $accounts->links() }}
    </div>
</x-layouts.app>
