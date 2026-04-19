<x-layouts.app :header="__('Categories')" :title="__('Categories')">
    <x-slot:sidebarNav>
        <x-admin.sidebar active="categories" />
    </x-slot:sidebarNav>

    <div class="flex flex-col gap-6 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-zinc-900 dark:text-zinc-50">{{ __('Categories') }}</h1>
            <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">{{ __('Create and manage course categories to organize your library.') }}</p>
        </div>
        <a href="{{ route('admin.categories.create') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-zinc-900 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition-all hover:bg-zinc-800 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-zinc-900 focus:ring-offset-2 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-100 dark:focus:ring-white">
            <svg class="-ml-0.5 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
            {{ __('Add Category') }}
        </a>
    </div>

    <div class="mt-8 overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-zinc-200/50 dark:bg-zinc-900/50 dark:ring-zinc-800">
        <div class="overflow-x-auto">
            <table class="min-w-full text-left text-sm whitespace-nowrap">
                <thead class="border-b border-zinc-200/50 bg-zinc-50/50 dark:border-zinc-800 dark:bg-zinc-900/50">
                    <tr>
                        <th class="px-6 py-4 font-semibold text-zinc-900 dark:text-zinc-100">{{ __('Name') }}</th>
                        <th class="px-6 py-4 font-semibold text-zinc-900 dark:text-zinc-100">{{ __('Slug') }}</th>
                        <th class="px-6 py-4 font-semibold text-zinc-900 dark:text-zinc-100">{{ __('Status') }}</th>
                        <th class="px-6 py-4 text-right font-semibold text-zinc-900 dark:text-zinc-100">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800/80">
                    @forelse ($categories as $category)
                        <tr class="transition-colors hover:bg-zinc-50/50 dark:hover:bg-zinc-800/30">
                            <td class="px-6 py-4 font-medium text-zinc-900 dark:text-zinc-100">{{ $category->name }}</td>
                            <td class="px-6 py-4 text-zinc-500 dark:text-zinc-400 font-mono text-xs">{{ $category->slug }}</td>
                            <td class="px-6 py-4">
                                @if ($category->is_active)
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
                                <div class="flex items-center justify-end gap-3 text-sm">
                                    @if ($category->is_active)
                                        <form method="POST" action="{{ route('admin.categories.deactivate', $category) }}">
                                            @csrf
                                            <button type="submit" class="font-medium text-zinc-500 transition hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-zinc-200">{{ __('Deactivate') }}</button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('admin.categories.activate', $category) }}">
                                            @csrf
                                            <button type="submit" class="font-medium text-emerald-600 transition hover:text-emerald-700 dark:text-emerald-400 dark:hover:text-emerald-300">{{ __('Activate') }}</button>
                                        </form>
                                    @endif
                                    
                                    <span class="text-zinc-300 dark:text-zinc-700">|</span>
                                    
                                    <a href="{{ route('admin.categories.edit', $category) }}" class="font-medium text-brand-600 transition hover:text-brand-700 dark:text-brand-400 dark:hover:text-brand-300">{{ __('Edit') }}</a>
                                    
                                    <span class="text-zinc-300 dark:text-zinc-700">|</span>
                                    
                                    <form
                                        method="POST"
                                        action="{{ route('admin.categories.destroy', $category) }}"
                                        data-confirm="{{ __('Are you sure you want to delete this category?') }}"
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
                            <td colspan="4" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-zinc-100 dark:bg-zinc-800">
                                        <svg class="h-6 w-6 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 9.776c.112-.017.227-.026.344-.026h15.812c.117 0 .232.009.344.026m-16.5 0a2.25 2.25 0 00-1.883 2.542l.857 6a2.25 2.25 0 002.227 1.932H19.05a2.25 2.25 0 002.227-1.932l.857-6a2.25 2.25 0 00-1.883-2.542m-16.5 0V6A2.25 2.25 0 016 3.75h3.879a1.5 1.5 0 011.06.44l2.122 2.12a1.5 1.5 0 001.06.44H18A2.25 2.25 0 0120.25 9v.776" /></svg>
                                    </div>
                                    <p class="mt-4 font-medium text-zinc-900 dark:text-zinc-100">{{ __('No categories found') }}</p>
                                    <p class="mt-1 text-sm text-zinc-500">{{ __('Start organizing courses by adding categories.') }}</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">
        {{ $categories->links() }}
    </div>
</x-layouts.app>
