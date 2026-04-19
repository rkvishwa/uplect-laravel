<x-layouts.app :header="__('My enrollments')" :title="__('My enrollments')">
    <x-slot:sidebarNav>
        <x-student.sidebar active="enrollments" />
    </x-slot:sidebarNav>

    <div class="space-y-4">
        @forelse ($enrollments as $e)
            <div class="rounded-2xl border border-zinc-200/80 bg-white/90 p-5 dark:border-zinc-800 dark:bg-zinc-900/70">
                <p class="font-semibold">{{ $e->course->title }}</p>
                <p class="text-sm text-zinc-500">{{ __('Status') }}: {{ $e->status }} · {{ $e->payment_method }}</p>
                @if ($e->status === \App\Models\Enrollment::STATUS_DECLINED && $e->decline_reason)
                    <p class="mt-2 text-sm text-red-600">{{ $e->decline_reason }}</p>
                @endif
                @if ($e->status === \App\Models\Enrollment::STATUS_ACTIVE)
                    <div class="mt-3 flex flex-wrap gap-3 text-sm">
                        <a href="{{ route('student.courses.timeline', $e->course) }}" class="font-medium text-brand-700">{{ __('Course timeline') }}</a>
                    </div>
                @endif
            </div>
        @empty
            <p class="text-sm text-zinc-500">{{ __('No enrollments yet.') }}</p>
        @endforelse
    </div>
    <div class="mt-6">{{ $enrollments->links() }}</div>
</x-layouts.app>
