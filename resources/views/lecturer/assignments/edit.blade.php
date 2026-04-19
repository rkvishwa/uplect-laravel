<x-layouts.app :header="$assignment->title" :title="$assignment->title">
    <x-slot:sidebarNav>
        <x-lecturer.sidebar active="courses" />
    </x-slot:sidebarNav>

    <form method="POST" action="{{ route('lecturer.assignments.update', $assignment) }}" class="mx-auto max-w-xl space-y-4 rounded-2xl border border-zinc-200 bg-white/90 p-6 dark:border-zinc-800 dark:bg-zinc-900/70">
        @csrf
        @method('PATCH')
        <div>
            <label class="text-sm font-medium">{{ __('Instructions (lecturer)') }}</label>
            <textarea name="instructions" rows="6" class="mt-1 w-full rounded-xl border border-zinc-200 px-3 py-2 text-sm dark:border-zinc-700 dark:bg-zinc-950">{{ old('instructions', $assignment->instructions) }}</textarea>
        </div>
        <div>
            <label class="text-sm font-medium">{{ __('Pass mark') }}</label>
            <input type="number" name="pass_mark" value="{{ old('pass_mark', $assignment->pass_mark) }}" class="mt-1 w-full rounded-xl border border-zinc-200 px-3 py-2 text-sm dark:border-zinc-700 dark:bg-zinc-950" />
        </div>
        <div>
            <label class="text-sm font-medium">{{ __('Submission type') }}</label>
            <select name="submission_type" class="mt-1 w-full rounded-xl border border-zinc-200 px-3 py-2 text-sm dark:border-zinc-700 dark:bg-zinc-950">
                <option value="text" @selected(old('submission_type', $assignment->submission_type) === 'text')>{{ __('Text') }}</option>
                <option value="file" @selected(old('submission_type', $assignment->submission_type) === 'file')>{{ __('File') }}</option>
            </select>
        </div>
        <button type="submit" class="rounded-xl bg-brand-600 px-5 py-2.5 text-sm font-semibold text-white">{{ __('Save') }}</button>
    </form>
</x-layouts.app>
