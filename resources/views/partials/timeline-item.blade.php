@props([
    'item',
    'role' => 'student',
])

@php($course = $item->course)

<div class="rounded-2xl border border-zinc-200/80 bg-white/90 p-5 shadow-sm dark:border-zinc-800 dark:bg-zinc-900/70">
    <div class="flex flex-wrap items-center justify-between gap-2">
        <div class="flex flex-wrap items-center gap-2">
            @if ($item->isSession())
                <span class="rounded-full bg-brand-100 px-2 py-0.5 text-xs font-semibold text-brand-800 dark:bg-brand-950/50 dark:text-brand-200">{{ __('Session') }}</span>
            @elseif($item->isAssignment())
                <span class="rounded-full bg-accent-400/30 px-2 py-0.5 text-xs font-semibold text-zinc-900">{{ __('Assignment') }}</span>
            @else
                <span class="rounded-full bg-emerald-100 px-2 py-0.5 text-xs font-semibold text-emerald-900 dark:bg-emerald-950/40 dark:text-emerald-100">{{ __('Certificate') }}</span>
            @endif
            <span class="text-xs text-zinc-500">{{ $course->title }}</span>
        </div>
        @if ($item->status === \App\Models\TimelineItem::STATUS_CANCELLED)
            <span class="text-xs font-semibold text-red-600">{{ __('Cancelled') }}</span>
        @endif
    </div>

    @if ($item->isSession())
        @php($s = $item->cardable)
        <p class="mt-2 font-semibold text-zinc-900 dark:text-zinc-50">{{ $s->title }}</p>
        <p class="text-xs text-zinc-500">{{ $item->scheduled_start_time }} – {{ $item->scheduled_end_time }}</p>
        <div x-data="{ open: false }" class="mt-2 text-sm text-zinc-600 dark:text-zinc-400">
            @if ($s->description)
                <p :class="open ? '' : 'line-clamp-3'">{{ $s->description }}</p>
                <button type="button" class="mt-1 text-xs font-medium text-brand-700" @click="open = !open" x-text="open ? @js(__('Show less')) : @js(__('Read more'))"></button>
            @endif
        </div>
        @if ($item->status === \App\Models\TimelineItem::STATUS_CANCELLED)
            <p class="mt-2 text-sm text-red-600">{{ $item->cancellation_reason }}</p>
        @endif
        @if ($s->resource_link)
            <a href="{{ $s->resource_link }}" target="_blank" class="mt-2 inline-block text-xs font-medium text-brand-700">{{ __('Resources') }}</a>
        @endif
        @if ($s->recording_url)
            <a href="{{ $s->recording_url }}" target="_blank" class="mt-2 inline-block text-xs font-medium text-brand-700">{{ __('Recording') }}</a>
        @endif
        @if ($role === 'student' && $s->zoomMeeting && $item->status !== \App\Models\TimelineItem::STATUS_CANCELLED)
            <a href="{{ route('student.timeline.zoom.join', $item) }}" class="mt-3 inline-flex rounded-xl bg-brand-600 px-4 py-2 text-xs font-semibold text-white">{{ __('Join meeting') }}</a>
        @endif
        @if ($role === 'lecturer' && $s->zoomMeeting && $item->status !== \App\Models\TimelineItem::STATUS_CANCELLED)
            <a href="{{ route('lecturer.timeline.zoom.start', $item) }}" target="_blank" class="mt-3 inline-flex rounded-xl bg-brand-600 px-4 py-2 text-xs font-semibold text-white">{{ __('Start as host') }}</a>
        @endif
    @elseif($item->isAssignment())
        @php($a = $item->cardable)
        <p class="mt-2 font-semibold">{{ $a->title }}</p>
        <p class="text-xs text-zinc-500">{{ __('Due') }}: {{ $a->due_at?->format('Y-m-d H:i') ?? '—' }}</p>
        @if ($role === 'student')
            <a href="{{ route('student.assignments.show', $a) }}" class="mt-2 inline-block text-xs font-medium text-brand-700">{{ __('Open assignment') }}</a>
        @endif
        @if ($role === 'lecturer')
            <a href="{{ route('lecturer.assignments.submissions', $a) }}" class="mt-2 inline-block text-xs font-medium text-brand-700">{{ __('Submissions') }}</a>
            <a href="{{ route('lecturer.assignments.edit', $a) }}" class="mt-2 ml-2 inline-block text-xs font-medium text-zinc-600">{{ __('Customize') }}</a>
        @endif
    @else
        @php($c = $item->cardable)
        <p class="mt-2 font-semibold">{{ $c->title }}</p>
        <p class="text-sm text-zinc-600 dark:text-zinc-400">{{ $c->description }}</p>
    @endif
</div>
