@php
    $initial = mb_strtoupper(mb_substr($user->name, 0, 1));
@endphp

<x-layouts.app :header="__('Admin')" :title="__('Profile')">
    <x-slot:sidebarNav>
        <x-admin.sidebar active="profile" />
    </x-slot:sidebarNav>

    <div class="mx-auto max-w-2xl space-y-8">
        <div>
            <p class="text-[11px] font-bold uppercase tracking-[0.2em] text-brand-700 dark:text-brand-300">{{ __('Account') }}</p>
            <h2 class="mt-1 font-sans text-2xl text-zinc-900 dark:text-zinc-50">{{ __('Your profile') }}</h2>
            <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-400">{{ __('Update your photo, contact details, and password.') }}</p>
        </div>

        <form
            method="POST"
            action="{{ route('admin.profile.update') }}"
            enctype="multipart/form-data"
            class="space-y-6 rounded-2xl border border-zinc-200/80 bg-white/90 p-8 shadow-sm dark:border-zinc-800 dark:bg-zinc-900/70"
        >
            @csrf
            @method('PATCH')

            <div>
                <p class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">{{ __('Profile photo') }}</p>
                <p class="mt-0.5 text-xs text-zinc-500 dark:text-zinc-400">{{ __('PNG or JPG, up to 2 MB.') }}</p>
                <div class="mt-4 flex flex-col items-start gap-4 sm:flex-row sm:items-center">
                    @if ($user->avatar_url)
                        <img
                            src="{{ $user->avatar_url }}"
                            alt=""
                            class="h-20 w-20 rounded-2xl border border-zinc-200 object-cover dark:border-zinc-700"
                        />
                    @else
                        <span class="flex h-20 w-20 items-center justify-center rounded-2xl border border-zinc-200 bg-brand-600/10 text-2xl font-bold text-brand-800 dark:border-zinc-700 dark:bg-brand-500/15 dark:text-brand-200" aria-hidden="true">{{ $initial }}</span>
                    @endif
                    <div class="flex flex-wrap items-center gap-3">
                        <label class="cursor-pointer rounded-xl border border-zinc-200 bg-white px-4 py-2.5 text-sm font-medium text-zinc-800 shadow-sm transition hover:border-brand-300 hover:text-brand-800 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-200 dark:hover:border-brand-500">
                            <input type="file" name="avatar" accept="image/*" class="sr-only" />
                            {{ __('Choose image') }}
                        </label>
                        @if ($user->avatar_path)
                            <button
                                type="submit"
                                form="admin-avatar-remove-form"
                                class="rounded-xl border border-red-200 bg-red-50 px-4 py-2.5 text-sm font-medium text-red-800 hover:bg-red-100 dark:border-red-900/60 dark:bg-red-950/40 dark:text-red-200 dark:hover:bg-red-950/60"
                                onclick="return confirm('{{ __('Remove this profile photo?') }}');"
                            >
                                {{ __('Remove photo') }}
                            </button>
                        @endif
                    </div>
                </div>
                @error('avatar')<p class="mt-2 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
            </div>

            <div class="border-t border-zinc-200/80 pt-6 dark:border-zinc-800">
                <p class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">{{ __('Details') }}</p>
                <div class="mt-4 space-y-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">{{ __('Full name') }}</label>
                        <input
                            type="text"
                            name="name"
                            id="name"
                            value="{{ old('name', $user->name) }}"
                            required
                            autocomplete="name"
                            class="mt-1 w-full rounded-xl border border-zinc-200 bg-white px-4 py-2.5 text-sm dark:border-zinc-700 dark:bg-zinc-950"
                        />
                        @error('name')<p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">{{ __('Email address') }}</label>
                        <input
                            type="email"
                            name="email"
                            id="email"
                            value="{{ old('email', $user->email) }}"
                            required
                            autocomplete="email"
                            class="mt-1 w-full rounded-xl border border-zinc-200 bg-white px-4 py-2.5 text-sm dark:border-zinc-700 dark:bg-zinc-950"
                        />
                        @error('email')<p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                        @if (! $user->hasVerifiedEmail())
                            <p class="mt-2 text-xs text-amber-700 dark:text-amber-300">{{ __('Your email is not verified yet.') }}</p>
                        @endif
                    </div>
                    <div>
                        <label for="phone" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">{{ __('Phone number') }}</label>
                        <input
                            type="tel"
                            name="phone"
                            id="phone"
                            value="{{ old('phone', $user->phone) }}"
                            required
                            autocomplete="tel"
                            class="mt-1 w-full rounded-xl border border-zinc-200 bg-white px-4 py-2.5 text-sm dark:border-zinc-700 dark:bg-zinc-950"
                        />
                        @error('phone')<p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <div class="flex gap-3 border-t border-zinc-200/80 pt-6 dark:border-zinc-800">
                <button type="submit" class="rounded-xl bg-brand-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-brand-500">{{ __('Save changes') }}</button>
            </div>
        </form>

        @if ($user->avatar_path)
            <form id="admin-avatar-remove-form" method="POST" action="{{ route('admin.profile.avatar.destroy') }}" class="hidden" aria-hidden="true">
                @csrf
                @method('DELETE')
            </form>
        @endif

        <form
            method="POST"
            action="{{ route('admin.profile.password') }}"
            class="space-y-6 rounded-2xl border border-zinc-200/80 bg-white/90 p-8 shadow-sm dark:border-zinc-800 dark:bg-zinc-900/70"
        >
            @csrf
            @method('PATCH')

            <div>
                <p class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">{{ __('Change password') }}</p>
                <p class="mt-0.5 text-xs text-zinc-500 dark:text-zinc-400">{{ __('Use your current password to set a new one.') }}</p>
            </div>

            <div class="space-y-4">
                <div>
                    <label for="current_password" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">{{ __('Current password') }}</label>
                    <input
                        type="password"
                        name="current_password"
                        id="current_password"
                        required
                        autocomplete="current-password"
                        class="mt-1 w-full rounded-xl border border-zinc-200 bg-white px-4 py-2.5 text-sm dark:border-zinc-700 dark:bg-zinc-950"
                    />
                    @error('current_password')<p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">{{ __('New password') }}</label>
                    <input
                        type="password"
                        name="password"
                        id="password"
                        required
                        autocomplete="new-password"
                        class="mt-1 w-full rounded-xl border border-zinc-200 bg-white px-4 py-2.5 text-sm dark:border-zinc-700 dark:bg-zinc-950"
                    />
                    @error('password')<p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">{{ __('Confirm new password') }}</label>
                    <input
                        type="password"
                        name="password_confirmation"
                        id="password_confirmation"
                        required
                        autocomplete="new-password"
                        class="mt-1 w-full rounded-xl border border-zinc-200 bg-white px-4 py-2.5 text-sm dark:border-zinc-700 dark:bg-zinc-950"
                    />
                </div>
            </div>

            <div>
                <button type="submit" class="rounded-xl bg-zinc-900 px-5 py-2.5 text-sm font-semibold text-white hover:bg-zinc-800 dark:bg-zinc-100 dark:text-zinc-900 dark:hover:bg-white">{{ __('Update password') }}</button>
            </div>
        </form>
    </div>
</x-layouts.app>
