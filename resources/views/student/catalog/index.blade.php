<x-layouts.app :header="__('Catalog')" :title="__('Catalog')">
    <x-slot:sidebarNav>
        <x-student.sidebar active="catalog" />
    </x-slot:sidebarNav>

    <form method="GET" class="mb-6 flex flex-wrap gap-3">
        <select name="category_id" class="rounded-xl border border-zinc-200 bg-white px-3 py-2 text-sm dark:border-zinc-700 dark:bg-zinc-950">
            <option value="">{{ __('All categories') }}</option>
            @foreach ($categories as $cat)
                <option value="{{ $cat->id }}" @selected(request('category_id') == $cat->id)>{{ $cat->name }}</option>
            @endforeach
        </select>
        <button type="submit" class="rounded-xl bg-brand-600 px-4 py-2 text-sm font-semibold text-white">{{ __('Filter') }}</button>
    </form>

    <div class="grid gap-6 sm:grid-cols-2 xl:grid-cols-3">
        @foreach ($courses as $course)
            <a href="{{ route('student.courses.show', $course) }}" class="block overflow-hidden rounded-2xl border border-zinc-200/80 bg-white/90 shadow-sm transition hover:border-brand-300 dark:border-zinc-800 dark:bg-zinc-900/70 dark:hover:border-brand-600">
                @if ($course->image_path)
                    <img src="{{ asset('storage/'.$course->image_path) }}" alt="" class="h-36 w-full object-cover" />
                @else
                    <div class="flex h-36 items-center justify-center bg-zinc-100 text-sm text-zinc-500 dark:bg-zinc-800">{{ __('Course') }}</div>
                @endif
                <div class="p-4">
                    <p class="font-semibold text-zinc-900 dark:text-zinc-50">{{ $course->title }}</p>
                    <p class="mt-1 text-xs text-zinc-500">{{ $course->category?->name }}</p>
                    <p class="mt-2 text-sm font-medium text-brand-800 dark:text-brand-200">LKR {{ number_format($course->student_total_fee_lkr, 2) }}</p>
                </div>
            </a>
        @endforeach
    </div>
    <div class="mt-8">{{ $courses->links() }}</div>
</x-layouts.app>
