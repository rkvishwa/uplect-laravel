<x-layouts.app :header="__('Categories')" :title="__('Categories')">
    <x-slot:sidebarNav>
        <x-admin.sidebar active="categories" />
    </x-slot:sidebarNav>

    <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
        <div>
            <p class="font-serif text-xl text-zinc-900 dark:text-zinc-50">{{ __('Categories') }}</p>
            <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-400">{{ __('Create and manage course categories.') }}</p>
        </div>
        <a
            href="{{ route('admin.categories.create') }}"
            class="inline-flex items-center justify-center rounded-xl bg-gradient-to-r from-brand-700 to-brand-600 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-brand-600/20 transition hover:from-brand-600 hover:to-brand-500"
        >{{ __('Add category') }}</a>
    </div>

    <div class="mt-8 overflow-hidden rounded-2xl border border-zinc-200/80 bg-white/90 shadow-sm dark:border-zinc-800 dark:bg-zinc-900/70">
        <table class="min-w-full divide-y divide-zinc-200 text-sm dark:divide-zinc-800">
            <thead class="bg-zinc-50/80 dark:bg-zinc-950/50">
                <tr>
                    <th class="px-6 py-3 text-left font-semibold text-zinc-700 dark:text-zinc-300">{{ __('Name') }}</th>
                    <th class="px-6 py-3 text-left font-semibold text-zinc-700 dark:text-zinc-300">{{ __('Slug') }}</th>
                    <th class="px-6 py-3 text-left font-semibold text-zinc-700 dark:text-zinc-300">{{ __('Status') }}</th>
                    <th class="px-6 py-3 text-right font-semibold text-zinc-700 dark:text-zinc-300">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-200 dark:divide-zinc-800">
                @forelse ($categories as $category)
                    <tr class="hover:bg-zinc-50/50 dark:hover:bg-zinc-800/30">
                        <td class="px-6 py-4 font-medium text-zinc-900 dark:text-zinc-100">{{ $category->name }}</td>
                        <td class="px-6 py-4 text-zinc-600 dark:text-zinc-400">{{ $category->slug }}</td>
                        <td class="px-6 py-4">
                            @if ($category->is_active)
                                <span class="rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-medium text-emerald-800 dark:bg-emerald-950/50 dark:text-emerald-200">{{ __('Active') }}</span>
                            @else
                                <span class="rounded-full bg-zinc-200 px-2.5 py-0.5 text-xs font-medium text-zinc-700 dark:bg-zinc-700 dark:text-zinc-200">{{ __('Inactive') }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex flex-wrap items-center justify-end gap-2">
                                @if ($category->is_active)
                                    <form method="POST" action="{{ route('admin.categories.deactivate', $category) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="text-sm font-medium text-zinc-600 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-zinc-100">{{ __('Deactivate') }}</button>
                                    </form>
                                @else
                                    <form method="POST" action="{{ route('admin.categories.activate', $category) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="text-sm font-medium text-brand-700 hover:text-brand-600 dark:text-brand-400">{{ __('Activate') }}</button>
                                    </form>
                                @endif
                                <a href="{{ route('admin.categories.edit', $category) }}" class="text-sm font-medium text-brand-700 hover:text-brand-600 dark:text-brand-400">{{ __('Edit') }}</a>
                                <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" class="inline" onsubmit="return confirm(@js(__('Delete this category?')));">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-sm font-medium text-red-600 hover:text-red-500 dark:text-red-400">{{ __('Delete') }}</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-zinc-500 dark:text-zinc-400">{{ __('No categories yet.') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $categories->links() }}
    </div>
</x-layouts.app>
