<x-layouts.app :header="__('Edit course')" :title="__('Edit course')">
    <x-slot:sidebarNav>
        <x-admin.sidebar active="courses" />
    </x-slot:sidebarNav>

    <div class="mx-auto max-w-2xl">
        <p class="font-sans text-xl text-zinc-900 dark:text-zinc-50">{{ __('Edit course') }}</p>
        <form method="POST" action="{{ route('admin.courses.update', $course) }}" enctype="multipart/form-data" class="mt-8 space-y-6 rounded-2xl border border-zinc-200/80 bg-white/90 p-8 shadow-sm dark:border-zinc-800 dark:bg-zinc-900/70">
            @csrf
            @method('PUT')
            <div class="grid gap-6 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">{{ __('Title') }}</label>
                    <input type="text" name="title" value="{{ old('title', $course->title) }}" required class="mt-1 w-full rounded-xl border border-zinc-200 bg-white px-4 py-2.5 text-sm dark:border-zinc-700 dark:bg-zinc-950" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">{{ __('Category') }}</label>
                    <select name="category_id" class="mt-1 w-full rounded-xl border border-zinc-200 bg-white px-4 py-2.5 text-sm dark:border-zinc-700 dark:bg-zinc-950">
                        <option value="">{{ __('— None —') }}</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}" @selected(old('category_id', $course->category_id) == $cat->id)>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">{{ __('Lecturer') }}</label>
                    <select name="lecturer_id" required class="mt-1 w-full rounded-xl border border-zinc-200 bg-white px-4 py-2.5 text-sm dark:border-zinc-700 dark:bg-zinc-950">
                        @foreach ($lecturers as $lec)
                            <option value="{{ $lec->id }}" @selected(old('lecturer_id', $course->lecturer_id) == $lec->id)>{{ $lec->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">{{ __('Description') }}</label>
                    <textarea name="description" rows="4" class="mt-1 w-full rounded-xl border border-zinc-200 bg-white px-4 py-2.5 text-sm dark:border-zinc-700 dark:bg-zinc-950">{{ old('description', $course->description) }}</textarea>
                </div>
                @if ($course->image_path)
                    <div class="sm:col-span-2 text-sm text-zinc-500">{{ __('Current image') }}: <a class="text-brand-600" href="{{ asset('storage/'.$course->image_path) }}" target="_blank">{{ __('View') }}</a></div>
                @endif
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">{{ __('Replace image') }}</label>
                    <input type="file" name="image" accept="image/*" class="mt-1 w-full text-sm" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">{{ __('Day of week') }}</label>
                    <select name="day_of_week" class="mt-1 w-full rounded-xl border border-zinc-200 bg-white px-4 py-2.5 text-sm dark:border-zinc-700 dark:bg-zinc-950">
                        @foreach ($weekdays as $key => $label)
                            <option value="{{ $key }}" @selected(old('day_of_week', $course->day_of_week) === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">{{ __('Start') }}</label>
                        <input type="time" name="start_time" value="{{ old('start_time', \Illuminate\Support\Str::substr($course->start_time, 0, 5)) }}" required class="mt-1 w-full rounded-xl border border-zinc-200 bg-white px-4 py-2.5 text-sm dark:border-zinc-700 dark:bg-zinc-950" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">{{ __('End') }}</label>
                        <input type="time" name="end_time" value="{{ old('end_time', \Illuminate\Support\Str::substr($course->end_time, 0, 5)) }}" required class="mt-1 w-full rounded-xl border border-zinc-200 bg-white px-4 py-2.5 text-sm dark:border-zinc-700 dark:bg-zinc-950" />
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">{{ __('Lecturer pay / session (LKR)') }}</label>
                    <input type="number" step="0.01" name="lecturer_payment_per_session_lkr" value="{{ old('lecturer_payment_per_session_lkr', $course->lecturer_payment_per_session_lkr) }}" required class="mt-1 w-full rounded-xl border border-zinc-200 bg-white px-4 py-2.5 text-sm dark:border-zinc-700 dark:bg-zinc-950" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">{{ __('Student fee (LKR)') }}</label>
                    <input type="number" step="0.01" name="student_total_fee_lkr" value="{{ old('student_total_fee_lkr', $course->student_total_fee_lkr) }}" required class="mt-1 w-full rounded-xl border border-zinc-200 bg-white px-4 py-2.5 text-sm dark:border-zinc-700 dark:bg-zinc-950" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">{{ __('Status') }}</label>
                    <select name="status" class="mt-1 w-full rounded-xl border border-zinc-200 bg-white px-4 py-2.5 text-sm dark:border-zinc-700 dark:bg-zinc-950">
                        <option value="active" @selected(old('status', $course->status) === 'active')>{{ __('Active') }}</option>
                        <option value="inactive" @selected(old('status', $course->status) === 'inactive')>{{ __('Inactive') }}</option>
                    </select>
                </div>
            </div>
            <div class="flex flex-wrap gap-3">
                <button type="submit" class="rounded-xl bg-brand-600 px-5 py-2.5 text-sm font-semibold text-white">{{ __('Update') }}</button>
                <a href="{{ route('admin.courses.timeline.index', $course) }}" class="rounded-xl border border-zinc-200 px-5 py-2.5 text-sm font-medium dark:border-zinc-700">{{ __('Timeline') }}</a>
            </div>
        </form>
        <form
            method="POST"
            action="{{ route('admin.courses.destroy', $course) }}"
            class="mt-4"
            data-confirm="{{ __('Delete this course?') }}"
            data-confirm-ok="{{ __('Delete') }}"
            data-confirm-cancel="{{ __('Cancel') }}"
        >
            @csrf
            @method('DELETE')
            <button type="submit" class="rounded-xl border border-red-200 px-5 py-2.5 text-sm font-medium text-red-600 dark:border-red-900">{{ __('Delete course') }}</button>
        </form>
        <div class="mt-6 flex gap-3">
            @if ($course->status === \App\Models\Course::STATUS_ACTIVE)
                <form method="POST" action="{{ route('admin.courses.inactivate', $course) }}">@csrf<button class="text-sm text-amber-700">{{ __('Deactivate') }}</button></form>
            @else
                <form method="POST" action="{{ route('admin.courses.activate', $course) }}">@csrf<button class="text-sm text-emerald-700">{{ __('Activate') }}</button></form>
            @endif
        </div>
    </div>
</x-layouts.app>
