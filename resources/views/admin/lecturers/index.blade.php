<x-layouts.app :header="__('Lecturers')" :title="__('Lecturers')">
    <x-slot:sidebarNav>
        <x-admin.sidebar active="lecturers" />
    </x-slot:sidebarNav>

    <div x-data="{ open: false }" @keydown.escape.window="open = false" class="space-y-8">
        <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
        <div>
            <p class="font-sans text-xl text-zinc-900 dark:text-zinc-50">{{ __('Lecturer accounts') }}</p>
            <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-400">{{ __('Create and remove lecturer logins. Only admins can manage lecturers.') }}</p>
        </div>
        <button
            type="button"
            class="inline-flex items-center justify-center rounded-xl bg-gradient-to-r from-brand-700 to-brand-600 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-brand-600/20 transition hover:from-brand-600 hover:to-brand-500"
            @click="open = true"
        >
            {{ __('Add lecturer') }}
        </button>
        </div>

        <div class="mt-0">
        <div class="overflow-hidden rounded-2xl border border-zinc-200/80 bg-white/90 shadow-sm dark:border-zinc-800 dark:bg-zinc-900/70">
            <table class="min-w-full divide-y divide-zinc-200 text-sm dark:divide-zinc-800">
                <thead class="bg-zinc-50/80 dark:bg-zinc-950/50">
                    <tr>
                        <th class="px-6 py-3 text-left font-semibold text-zinc-700 dark:text-zinc-300">{{ __('Name') }}</th>
                        <th class="px-6 py-3 text-left font-semibold text-zinc-700 dark:text-zinc-300">{{ __('Email') }}</th>
                        <th class="px-6 py-3 text-left font-semibold text-zinc-700 dark:text-zinc-300">{{ __('Phone') }}</th>
                        <th class="px-6 py-3 text-right font-semibold text-zinc-700 dark:text-zinc-300">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-800">
                    @forelse ($lecturers as $lecturer)
                        <tr class="hover:bg-zinc-50/50 dark:hover:bg-zinc-800/30">
                            <td class="px-6 py-4 font-medium text-zinc-900 dark:text-zinc-100">{{ $lecturer->name }}</td>
                            <td class="px-6 py-4 text-zinc-600 dark:text-zinc-400">{{ $lecturer->email }}</td>
                            <td class="px-6 py-4 text-zinc-600 dark:text-zinc-400">{{ $lecturer->phone }}</td>
                            <td class="px-6 py-4 text-right">
                                <form method="POST" action="{{ route('admin.lecturers.destroy', $lecturer) }}" onsubmit="return confirm(@js(__('Remove this lecturer? They will no longer be able to sign in.')));">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-sm font-medium text-red-600 hover:text-red-500 dark:text-red-400">
                                        {{ __('Remove') }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-zinc-500 dark:text-zinc-400">
                                {{ __('No lecturers yet. Add your first lecturer to get started.') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $lecturers->links() }}
        </div>

        <div
            x-show="open"
            x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center bg-zinc-950/60 px-4 backdrop-blur-sm"
            @click.self="open = false"
        >
            <div class="max-h-[90vh] w-full max-w-md overflow-y-auto rounded-2xl border border-zinc-200 bg-white p-6 shadow-2xl dark:border-zinc-700 dark:bg-zinc-900" @click.stop>
                <h3 class="font-sans text-lg font-semibold text-zinc-900 dark:text-zinc-50">{{ __('New lecturer') }}</h3>
                <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-400">{{ __('They can sign in immediately — email is pre-verified.') }}</p>

                <form method="POST" action="{{ route('admin.lecturers.store') }}" class="mt-6 space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">{{ __('Full name') }}</label>
                        <input name="name" value="{{ old('name') }}" required class="mt-1 w-full rounded-xl border border-zinc-200 bg-white px-3 py-2 text-zinc-900 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-100">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">{{ __('Email') }}</label>
                        <input name="email" type="email" value="{{ old('email') }}" required class="mt-1 w-full rounded-xl border border-zinc-200 bg-white px-3 py-2 text-zinc-900 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-100">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">{{ __('Phone') }}</label>
                        <input name="phone" value="{{ old('phone') }}" required class="mt-1 w-full rounded-xl border border-zinc-200 bg-white px-3 py-2 text-zinc-900 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-100">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">{{ __('Password') }}</label>
                        <input name="password" type="password" required class="mt-1 w-full rounded-xl border border-zinc-200 bg-white px-3 py-2 text-zinc-900 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-100">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">{{ __('Confirm password') }}</label>
                        <input name="password_confirmation" type="password" required class="mt-1 w-full rounded-xl border border-zinc-200 bg-white px-3 py-2 text-zinc-900 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-100">
                    </div>
                    <div class="flex justify-end gap-2 pt-2">
                        <button type="button" class="rounded-xl border border-zinc-200 px-4 py-2 text-sm font-medium text-zinc-700 dark:border-zinc-600 dark:text-zinc-300" @click="open = false">{{ __('Cancel') }}</button>
                        <button type="submit" class="rounded-xl bg-brand-600 px-4 py-2 text-sm font-semibold text-white hover:bg-brand-500">{{ __('Create') }}</button>
                    </div>
                </form>
            </div>
        </div>
        </div>
    </div>
</x-layouts.app>
