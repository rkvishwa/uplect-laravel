<x-layouts.app :header="__('New course')" :title="__('New course')">
    <x-slot:sidebarNav>
        <x-admin.sidebar active="courses" />
    </x-slot:sidebarNav>

    <div class="mx-auto max-w-2xl">
        <p class="font-serif text-xl text-zinc-900 dark:text-zinc-50">{{ __('New course') }}</p>
        <form method="POST" action="{{ route('admin.courses.store') }}" enctype="multipart/form-data" class="mt-8 space-y-6 rounded-2xl border border-zinc-200/80 bg-white/90 p-8 shadow-sm dark:border-zinc-800 dark:bg-zinc-900/70">
            @csrf
            <div class="grid gap-6 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">{{ __('Title') }}</label>
                    <input type="text" name="title" value="{{ old('title') }}" required class="mt-1 w-full rounded-xl border border-zinc-200 bg-white px-4 py-2.5 text-sm dark:border-zinc-700 dark:bg-zinc-950" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">{{ __('Category') }}</label>
                    <select name="category_id" class="mt-1 w-full rounded-xl border border-zinc-200 bg-white px-4 py-2.5 text-sm dark:border-zinc-700 dark:bg-zinc-950">
                        <option value="">{{ __('— None —') }}</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}" @selected(old('category_id') == $cat->id)>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">{{ __('Lecturer') }}</label>
                    <select name="lecturer_id" required class="mt-1 w-full rounded-xl border border-zinc-200 bg-white px-4 py-2.5 text-sm dark:border-zinc-700 dark:bg-zinc-950">
                        @foreach ($lecturers as $lec)
                            <option value="{{ $lec->id }}" @selected(old('lecturer_id') == $lec->id)>{{ $lec->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">{{ __('Description') }}</label>
                    <textarea name="description" rows="4" class="mt-1 w-full rounded-xl border border-zinc-200 bg-white px-4 py-2.5 text-sm dark:border-zinc-700 dark:bg-zinc-950">{{ old('description') }}</textarea>
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">{{ __('Course image') }}</label>
                    <input type="file" name="image" accept="image/*" class="mt-1 w-full text-sm" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">{{ __('Day of week') }}</label>
                    <select name="day_of_week" class="mt-1 w-full rounded-xl border border-zinc-200 bg-white px-4 py-2.5 text-sm dark:border-zinc-700 dark:bg-zinc-950">
                        @foreach ($weekdays as $key => $label)
                            <option value="{{ $key }}" @selected(old('day_of_week') === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">{{ __('Start') }}</label>
                        <input type="time" name="start_time" value="{{ old('start_time', '09:00') }}" required class="mt-1 w-full rounded-xl border border-zinc-200 bg-white px-4 py-2.5 text-sm dark:border-zinc-700 dark:bg-zinc-950" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">{{ __('End') }}</label>
                        <input type="time" name="end_time" value="{{ old('end_time', '10:30') }}" required class="mt-1 w-full rounded-xl border border-zinc-200 bg-white px-4 py-2.5 text-sm dark:border-zinc-700 dark:bg-zinc-950" />
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">{{ __('Lecturer pay / session (LKR)') }}</label>
                    <input type="number" step="0.01" name="lecturer_payment_per_session_lkr" value="{{ old('lecturer_payment_per_session_lkr', 0) }}" required class="mt-1 w-full rounded-xl border border-zinc-200 bg-white px-4 py-2.5 text-sm dark:border-zinc-700 dark:bg-zinc-950" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">{{ __('Student fee (LKR)') }}</label>
                    <input type="number" step="0.01" name="student_total_fee_lkr" value="{{ old('student_total_fee_lkr', 0) }}" required class="mt-1 w-full rounded-xl border border-zinc-200 bg-white px-4 py-2.5 text-sm dark:border-zinc-700 dark:bg-zinc-950" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">{{ __('Status') }}</label>
                    <select name="status" class="mt-1 w-full rounded-xl border border-zinc-200 bg-white px-4 py-2.5 text-sm dark:border-zinc-700 dark:bg-zinc-950">
                        <option value="active" @selected(old('status', 'active') === 'active')>{{ __('Active') }}</option>
                        <option value="inactive" @selected(old('status') === 'inactive')>{{ __('Inactive') }}</option>
                    </select>
                </div>
            </div>
            <div class="flex gap-3">
                <button type="submit" class="rounded-xl bg-brand-600 px-5 py-2.5 text-sm font-semibold text-white">{{ __('Save') }}</button>
                <a href="{{ route('admin.courses.index') }}" class="rounded-xl border border-zinc-200 px-5 py-2.5 text-sm font-medium dark:border-zinc-700">{{ __('Cancel') }}</a>
            </div>
        </form>
    </div>
</x-layouts.app>
