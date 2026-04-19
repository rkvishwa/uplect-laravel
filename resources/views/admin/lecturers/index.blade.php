<x-layouts.app :header="__('Lecturers')" :title="__('Lecturers')">
    <x-slot:sidebarNav>
        <x-admin.sidebar active="lecturers" />
    </x-slot:sidebarNav>

    <div x-data="{ open: false }" @keydown.escape.window="open = false">
        <div class="flex flex-col gap-6 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-zinc-900 dark:text-zinc-50">{{ __('Lecturer Accounts') }}</h1>
                <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">{{ __('Easily add and manage permissions for platform lecturers.') }}</p>
            </div>
            <button
                type="button"
                @click="open = true"
                class="inline-flex items-center justify-center gap-2 rounded-xl bg-zinc-900 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition-all hover:bg-zinc-800 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-zinc-900 focus:ring-offset-2 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-100 dark:focus:ring-white"
            >
                <svg class="-ml-0.5 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                {{ __('Add Lecturer') }}
            </button>
        </div>

        <div class="mt-8 overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-zinc-200/50 dark:bg-zinc-900/50 dark:ring-zinc-800">
            <div class="overflow-x-auto">
                <table class="min-w-full text-left text-sm whitespace-nowrap">
                    <thead class="border-b border-zinc-200/50 bg-zinc-50/50 dark:border-zinc-800 dark:bg-zinc-900/50">
                        <tr>
                            <th class="px-6 py-4 font-semibold text-zinc-900 dark:text-zinc-100">{{ __('Lecturer') }}</th>
                            <th class="px-6 py-4 font-semibold text-zinc-900 dark:text-zinc-100">{{ __('Contact') }}</th>
                            <th class="px-6 py-4 text-right font-semibold text-zinc-900 dark:text-zinc-100">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800/80">
                        @forelse ($lecturers as $lecturer)
                            <tr class="transition-colors hover:bg-zinc-50/50 dark:hover:bg-zinc-800/30">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-violet-50 text-violet-700 dark:bg-violet-500/20 dark:text-violet-300">
                                            {{ substr($lecturer->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="font-medium text-zinc-900 dark:text-zinc-100">{{ $lecturer->name }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-zinc-600 dark:text-zinc-300">{{ $lecturer->email }}</p>
                                    <p class="mt-0.5 text-xs text-zinc-500 dark:text-zinc-400">{{ $lecturer->phone ?? __('No phone provided') }}</p>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <form method="POST" action="{{ route('admin.lecturers.destroy', $lecturer) }}" onsubmit="return confirm(@js(__('Remove this lecturer? They will no longer be able to sign in.')));">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center gap-1.5 rounded-lg px-3 py-1.5 text-xs font-medium text-red-600 transition hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-500/10">
                                            {{ __('Remove') }}
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-zinc-100 dark:bg-zinc-800">
                                            <svg class="h-6 w-6 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" /></svg>
                                        </div>
                                        <p class="mt-4 font-medium text-zinc-900 dark:text-zinc-100">{{ __('No lecturers found') }}</p>
                                        <p class="mt-1 text-sm text-zinc-500">{{ __('Get your teaching team established.') }}</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-6">
            {{ $lecturers->links() }}
        </div>

        {{-- Add Lecturer Modal --}}
        <div
            x-show="open"
            x-cloak
            x-transition.opacity.duration.300ms
            class="fixed inset-0 z-50 flex items-center justify-center bg-zinc-950/60 p-4 backdrop-blur-sm"
            @click.self="open = false"
        >
            <div 
                x-show="open"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 scale-95"
                class="max-h-[90vh] w-full max-w-lg overflow-y-auto rounded-3xl bg-white shadow-2xl ring-1 ring-zinc-200/50 dark:bg-zinc-900 dark:ring-zinc-700" 
            >
                <div class="px-8 pt-8">
                    <h3 class="text-xl font-bold tracking-tight text-zinc-900 dark:text-zinc-50">{{ __('New Lecturer Profile') }}</h3>
                    <p class="mt-2 text-sm text-zinc-500 dark:text-zinc-400">{{ __('Create an account for a new lecturer. They will be able to sign in immediately.') }}</p>
                </div>

                <form method="POST" action="{{ route('admin.lecturers.store') }}" class="mt-6 px-8 pb-8">
                    @csrf
                    <div class="space-y-5">
                        <div>
                            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">{{ __('Full Name') }}</label>
                            <input name="name" value="{{ old('name') }}" required class="mt-2 block w-full rounded-xl border-zinc-200 text-sm focus:border-brand-500 focus:ring-brand-500 dark:border-zinc-700 dark:bg-zinc-950 dark:text-white dark:focus:border-brand-500 dark:focus:ring-brand-500" placeholder="e.g. Dr. Jane Smith">
                        </div>
                        <div class="grid gap-5 sm:grid-cols-2">
                            <div>
                                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">{{ __('Email address') }}</label>
                                <input name="email" type="email" value="{{ old('email') }}" required class="mt-2 block w-full rounded-xl border-zinc-200 text-sm focus:border-brand-500 focus:ring-brand-500 dark:border-zinc-700 dark:bg-zinc-950 dark:text-white dark:focus:border-brand-500 dark:focus:ring-brand-500" placeholder="jane@example.com">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">{{ __('Phone') }}</label>
                                <input name="phone" value="{{ old('phone') }}" required class="mt-2 block w-full rounded-xl border-zinc-200 text-sm focus:border-brand-500 focus:ring-brand-500 dark:border-zinc-700 dark:bg-zinc-950 dark:text-white dark:focus:border-brand-500 dark:focus:ring-brand-500">
                            </div>
                        </div>
                        <div class="grid gap-5 sm:grid-cols-2">
                            <div>
                                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">{{ __('Password') }}</label>
                                <input name="password" type="password" required class="mt-2 block w-full rounded-xl border-zinc-200 text-sm focus:border-brand-500 focus:ring-brand-500 dark:border-zinc-700 dark:bg-zinc-950 dark:text-white dark:focus:border-brand-500 dark:focus:ring-brand-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">{{ __('Confirm Password') }}</label>
                                <input name="password_confirmation" type="password" required class="mt-2 block w-full rounded-xl border-zinc-200 text-sm focus:border-brand-500 focus:ring-brand-500 dark:border-zinc-700 dark:bg-zinc-950 dark:text-white dark:focus:border-brand-500 dark:focus:ring-brand-500">
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-8 flex justify-end gap-3 border-t border-zinc-100 pt-6 dark:border-zinc-800">
                        <button type="button" class="rounded-xl px-5 py-2.5 text-sm font-medium text-zinc-600 transition hover:bg-zinc-100 dark:text-zinc-300 dark:hover:bg-zinc-800" @click="open = false">{{ __('Cancel') }}</button>
                        <button type="submit" class="rounded-xl bg-zinc-900 px-5 py-2.5 text-sm font-medium text-white shadow-sm transition hover:bg-zinc-800 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-200">{{ __('Create Account') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.app>
