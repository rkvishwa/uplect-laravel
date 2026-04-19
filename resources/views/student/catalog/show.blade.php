<x-layouts.app :header="$course->title" :title="$course->title">
    <x-slot:sidebarNav>
        <x-student.sidebar active="catalog" />
    </x-slot:sidebarNav>

    <div class="grid gap-8 lg:grid-cols-2">
        <div>
            @if ($course->image_path)
                <img src="{{ asset('storage/'.$course->image_path) }}" alt="" class="w-full rounded-2xl object-cover" />
            @endif
        </div>
        <div>
            <p class="font-serif text-2xl text-zinc-900 dark:text-zinc-50">{{ $course->title }}</p>
            <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-400">{{ $course->description }}</p>
            <p class="mt-4 text-lg font-semibold text-brand-800 dark:text-brand-200">LKR {{ number_format($course->student_total_fee_lkr, 2) }}</p>
            <p class="mt-2 text-sm text-zinc-500">{{ __('Lecturer') }}: {{ $course->lecturer?->name }}</p>
            <a href="{{ route('student.enroll.create', $course) }}" class="mt-6 inline-flex rounded-xl bg-brand-600 px-6 py-3 text-sm font-semibold text-white">{{ __('Enroll') }}</a>
        </div>
    </div>
</x-layouts.app>
