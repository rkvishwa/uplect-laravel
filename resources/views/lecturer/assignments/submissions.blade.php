<x-layouts.app :header="$assignment->title" :title="$assignment->title">
    <x-slot:sidebarNav>
        <x-lecturer.sidebar active="courses" />
    </x-slot:sidebarNav>

    <p class="text-sm text-zinc-500">{{ $assignment->timelineItem->course->title }}</p>

    <div class="mt-6 space-y-6">
        @foreach ($assignment->submissions as $sub)
            <div class="rounded-2xl border border-zinc-200 bg-white/90 p-5 dark:border-zinc-800 dark:bg-zinc-900/70">
                <p class="font-semibold">{{ $sub->student->name }}</p>
                <p class="text-xs text-zinc-500">{{ $sub->student->email }}</p>
                @if ($sub->submission_text)
                    <p class="mt-3 whitespace-pre-wrap text-sm text-zinc-700 dark:text-zinc-300">{{ $sub->submission_text }}</p>
                @endif
                @if ($sub->submission_file_path)
                    <a href="{{ route('lecturer.submissions.download', $sub) }}" class="mt-2 inline-block text-sm font-medium text-brand-700">{{ __('Download file') }}</a>
                @endif
                <p class="mt-2 text-xs text-zinc-500">{{ __('Status') }}: {{ $sub->status }} @if($sub->marks !== null) · {{ $sub->marks }} @endif</p>

                <form method="POST" action="{{ route('lecturer.submissions.grade', $sub) }}" class="mt-4 grid gap-2 sm:grid-cols-3">
                    @csrf
                    <input type="number" step="0.01" name="marks" placeholder="{{ __('Marks') }}" class="rounded-lg border border-zinc-200 px-2 py-1 text-sm dark:border-zinc-700 dark:bg-zinc-950" />
                    <input type="text" name="feedback" placeholder="{{ __('Feedback') }}" class="rounded-lg border border-zinc-200 px-2 py-1 text-sm dark:border-zinc-700 dark:bg-zinc-950 sm:col-span-2" />
                    <button type="submit" class="rounded-lg bg-brand-600 px-3 py-1 text-sm font-semibold text-white">{{ __('Grade') }}</button>
                </form>
                <form method="POST" action="{{ route('lecturer.submissions.return', $sub) }}" class="mt-2">
                    @csrf
                    <button class="text-xs text-amber-700">{{ __('Return for resubmission') }}</button>
                </form>
            </div>
        @endforeach
    </div>
</x-layouts.app>
