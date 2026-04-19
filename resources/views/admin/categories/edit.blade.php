<x-layouts.app :header="__('Edit category')" :title="__('Edit category')">
    <x-slot:sidebarNav>
        <x-admin.sidebar active="categories" />
    </x-slot:sidebarNav>

    <div class="mx-auto max-w-xl">
        <p class="font-sans text-xl text-zinc-900 dark:text-zinc-50">{{ __('Edit category') }}</p>
        <form method="POST" action="{{ route('admin.categories.update', $category) }}" class="mt-8 space-y-6 rounded-2xl border border-zinc-200/80 bg-white/90 p-8 shadow-sm dark:border-zinc-800 dark:bg-zinc-900/70">
            @csrf
            @method('PUT')
            <div>
                <label for="name" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">{{ __('Name') }}</label>
                <input type="text" name="name" id="name" value="{{ old('name', $category->name) }}" required class="mt-1 w-full rounded-xl border border-zinc-200 bg-white px-4 py-2.5 text-sm dark:border-zinc-700 dark:bg-zinc-950" />
            </div>
            <div>
                <label for="slug" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">{{ __('Slug') }}</label>
                <input type="text" name="slug" id="slug" value="{{ old('slug', $category->slug) }}" class="mt-1 w-full rounded-xl border border-zinc-200 bg-white px-4 py-2.5 text-sm dark:border-zinc-700 dark:bg-zinc-950" />
            </div>
            <div>
                <label for="description" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">{{ __('Description') }}</label>
                <textarea name="description" id="description" rows="4" class="mt-1 w-full rounded-xl border border-zinc-200 bg-white px-4 py-2.5 text-sm dark:border-zinc-700 dark:bg-zinc-950">{{ old('description', $category->description) }}</textarea>
            </div>
            <div class="flex items-center gap-2">
                <input type="hidden" name="is_active" value="0" />
                <input type="checkbox" name="is_active" id="is_active" value="1" @checked(old('is_active', $category->is_active)) class="rounded border-zinc-300 text-brand-600 focus:ring-brand-500" />
                <label for="is_active" class="text-sm text-zinc-700 dark:text-zinc-300">{{ __('Active') }}</label>
            </div>
            <div class="flex gap-3">
                <button type="submit" class="rounded-xl bg-brand-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-brand-500">{{ __('Update') }}</button>
                <a href="{{ route('admin.categories.index') }}" class="rounded-xl border border-zinc-200 px-5 py-2.5 text-sm font-medium text-zinc-700 hover:bg-zinc-50 dark:border-zinc-700 dark:text-zinc-200 dark:hover:bg-zinc-800">{{ __('Cancel') }}</a>
            </div>
        </form>
    </div>
</x-layouts.app>
