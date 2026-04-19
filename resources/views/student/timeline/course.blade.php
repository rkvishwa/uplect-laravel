<x-layouts.app :header="$course->title" :title="$course->title">
    <x-slot:sidebarNav>
        <x-student.sidebar active="timeline" />
    </x-slot:sidebarNav>

    @forelse ($grouped as $date => $items)
        <div class="mb-8">
            <h3 class="font-serif text-lg">{{ \Carbon\Carbon::parse($date)->format('l, M j, Y') }}</h3>
            <div class="mt-4 space-y-4">
                @foreach ($items as $item)
                    @include('partials.timeline-item', ['item' => $item, 'role' => 'student'])
                @endforeach
            </div>
        </div>
    @empty
        <p class="text-sm text-zinc-500">{{ __('No items on the timeline yet.') }}</p>
    @endforelse
</x-layouts.app>
