<x-layouts.app :header="$assignment->title" :title="$assignment->title">
    <x-slot:sidebarNav>
        <x-student.sidebar active="timeline" />
    </x-slot:sidebarNav>

    <p class="text-sm text-zinc-500">{{ $course->title }}</p>
    <p class="mt-4 font-semibold">{{ __('Instructions') }}</p>
    <div class="prose prose-sm mt-2 max-w-none text-zinc-700 dark:text-zinc-300">{!! nl2br(e($assignment->instructions ?? $assignment->description ?? '')) !!}</div>

    <div class="mt-8 rounded-2xl border border-zinc-200 bg-white/90 p-6 dark:border-zinc-800 dark:bg-zinc-900/70">
        <p class="text-sm font-medium">{{ __('Your submission') }}</p>
        <p class="text-xs text-zinc-500">{{ __('Status') }}: {{ $submission->exists ? $submission->status : __('none') }} @if($submission->exists && $submission->marks !== null) · {{ $submission->marks }} / {{ $assignment->max_mark }} @endif</p>

        @if ($submission->exists && $submission->status === \App\Models\AssignmentSubmission::STATUS_GRADED)
            <p class="mt-4 text-sm text-zinc-600">{{ __('Graded. Contact your lecturer if you need changes.') }}</p>
        @else
            <form method="POST" action="{{ route('student.assignments.submit', $assignment) }}" enctype="multipart/form-data" class="mt-4 space-y-4">
                @csrf
                @if ($assignment->submission_type === \App\Models\CourseAssignment::SUBMISSION_FILE)
                    <input type="file" name="file" required class="block w-full text-sm" />
                @else
                    <textarea name="submission_text" rows="6" class="w-full rounded-xl border border-zinc-200 px-3 py-2 text-sm dark:border-zinc-700 dark:bg-zinc-950" required>{{ old('submission_text', $submission->submission_text) }}</textarea>
                @endif
                <button type="submit" class="rounded-xl bg-brand-600 px-5 py-2.5 text-sm font-semibold text-white">{{ __('Submit') }}</button>
            </form>
        @endif
    </div>
</x-layouts.app>
