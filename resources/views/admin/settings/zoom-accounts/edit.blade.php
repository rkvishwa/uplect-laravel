<x-layouts.app :header="__('Settings')" :title="__('Settings')">
    <x-slot:sidebarNav>
        <x-admin.sidebar active="settings" />
    </x-slot:sidebarNav>

    <x-admin.settings-tabs tab="zoom" />

    <div class="mx-auto max-w-xl">
        <p class="font-sans text-xl text-zinc-900 dark:text-zinc-50">{{ __('Edit Zoom account') }}</p>
        <p class="mt-1 text-sm text-zinc-500">{{ $zoomAccount->name }}</p>
        <form method="POST" action="{{ route('admin.settings.zoom-accounts.update', $zoomAccount) }}" class="mt-8 space-y-6 rounded-2xl border border-zinc-200/80 bg-white/90 p-8 shadow-sm dark:border-zinc-800 dark:bg-zinc-900/70">
            @csrf
            @method('PUT')
            <div>
                <label for="name" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">{{ __('Display name') }}</label>
                <input type="text" name="name" id="name" value="{{ old('name', $zoomAccount->name) }}" required class="mt-1 w-full rounded-xl border border-zinc-200 bg-white px-4 py-2.5 text-sm dark:border-zinc-700 dark:bg-zinc-950" />
            </div>
            <div>
                <label for="account_id" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">{{ __('Account ID') }}</label>
                <input type="text" name="account_id" id="account_id" value="{{ old('account_id', $zoomAccount->account_id) }}" required autocomplete="off" class="mt-1 w-full rounded-xl border border-zinc-200 bg-white px-4 py-2.5 text-sm font-mono dark:border-zinc-700 dark:bg-zinc-950" />
            </div>
            <div>
                <label for="client_id" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">{{ __('Client ID') }}</label>
                <input type="text" name="client_id" id="client_id" value="{{ old('client_id', $zoomAccount->client_id) }}" required autocomplete="off" class="mt-1 w-full rounded-xl border border-zinc-200 bg-white px-4 py-2.5 text-sm font-mono dark:border-zinc-700 dark:bg-zinc-950" />
            </div>
            <div>
                <label for="client_secret" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">{{ __('Client secret') }}</label>
                <input type="password" name="client_secret" id="client_secret" value="{{ old('client_secret') }}" autocomplete="new-password" class="mt-1 w-full rounded-xl border border-zinc-200 bg-white px-4 py-2.5 text-sm dark:border-zinc-700 dark:bg-zinc-950" placeholder="{{ __('Leave blank to keep current') }}" />
            </div>
            <div>
                <label for="host_user_id" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">{{ __('Host user ID or email') }}</label>
                <input type="text" name="host_user_id" id="host_user_id" value="{{ old('host_user_id', $zoomAccount->host_user_id) }}" required class="mt-1 w-full rounded-xl border border-zinc-200 bg-white px-4 py-2.5 text-sm dark:border-zinc-700 dark:bg-zinc-950" />
            </div>
            <div>
                <label for="timezone" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">{{ __('Timezone') }}</label>
                <input type="text" name="timezone" id="timezone" value="{{ old('timezone', $zoomAccount->timezone) }}" class="mt-1 w-full rounded-xl border border-zinc-200 bg-white px-4 py-2.5 text-sm dark:border-zinc-700 dark:bg-zinc-950" />
            </div>
            <div class="flex items-center gap-2">
                <input type="hidden" name="waiting_room" value="0" />
                <input type="checkbox" name="waiting_room" id="waiting_room" value="1" @checked(old('waiting_room', $zoomAccount->waiting_room)) class="rounded border-zinc-300 text-brand-600 focus:ring-brand-500" />
                <label for="waiting_room" class="text-sm text-zinc-700 dark:text-zinc-300">{{ __('Waiting room') }}</label>
            </div>
            <div class="flex items-center gap-2">
                <input type="hidden" name="is_active" value="0" />
                <input type="checkbox" name="is_active" id="is_active" value="1" @checked(old('is_active', $zoomAccount->is_active)) class="rounded border-zinc-300 text-brand-600 focus:ring-brand-500" />
                <label for="is_active" class="text-sm text-zinc-700 dark:text-zinc-300">{{ __('Active') }}</label>
            </div>
            <div class="flex items-center gap-2">
                <input type="hidden" name="is_default" value="0" />
                <input type="checkbox" name="is_default" id="is_default" value="1" @checked(old('is_default', $zoomAccount->is_default)) class="rounded border-zinc-300 text-brand-600 focus:ring-brand-500" />
                <label for="is_default" class="text-sm text-zinc-700 dark:text-zinc-300">{{ __('Set as default account') }}</label>
            </div>
            <div class="flex gap-3">
                <button type="submit" class="rounded-xl bg-brand-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-brand-500">{{ __('Save') }}</button>
                <a href="{{ route('admin.settings.zoom-accounts.index') }}" class="rounded-xl border border-zinc-200 px-5 py-2.5 text-sm font-medium text-zinc-700 hover:bg-zinc-50 dark:border-zinc-700 dark:text-zinc-200 dark:hover:bg-zinc-800">{{ __('Cancel') }}</a>
            </div>
        </form>
    </div>
</x-layouts.app>
