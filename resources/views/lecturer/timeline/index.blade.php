<x-layouts.app :header="__('Timeline')" :title="__('Timeline')">
    <x-slot:sidebarNav>
        <x-lecturer.sidebar active="timeline" />
    </x-slot:sidebarNav>

    <form method="GET" class="mb-8 grid gap-4 rounded-2xl border border-zinc-200 bg-white/90 p-4 dark:border-zinc-800 dark:bg-zinc-900/70 md:grid-cols-4">
        <div>
            <label class="text-xs font-medium text-zinc-600">{{ __('From') }}</label>
            <input type="date" name="from" value="{{ $from ?? '' }}" class="mt-1 w-full rounded-lg border border-zinc-200 px-2 py-1 text-sm dark:border-zinc-700 dark:bg-zinc-950" />
        </div>
        <div>
            <label class="text-xs font-medium text-zinc-600">{{ __('To') }}</label>
            <input type="date" name="to" value="{{ $to ?? '' }}" class="mt-1 w-full rounded-lg border border-zinc-200 px-2 py-1 text-sm dark:border-zinc-700 dark:bg-zinc-950" />
        </div>
        <div class="md:col-span-2">
            <label class="text-xs font-medium text-zinc-600">{{ __('Courses') }}</label>
            <select name="courses[]" multiple class="mt-1 h-24 w-full rounded-lg border border-zinc-200 px-2 py-1 text-sm dark:border-zinc-700 dark:bg-zinc-950">
                @foreach ($courses as $c)
                    <option value="{{ $c->id }}" @selected(collect($courseIds ?? [])->contains($c->id))>{{ $c->title }}</option>
                @endforeach
            </select>
        </div>
        <div class="md:col-span-4">
            <button type="submit" class="rounded-xl bg-brand-600 px-4 py-2 text-sm font-semibold text-white">{{ __('Apply filters') }}</button>
        </div>
    </form>

    @php($byDate = $items->groupBy(fn ($i) => $i->scheduled_date->format('Y-m-d')))
    @forelse ($byDate as $date => $dayItems)
        <div class="mb-8">
            <h3 class="font-sans text-lg text-zinc-800 dark:text-zinc-100">{{ \Carbon\Carbon::parse($date)->format('l, M j') }}</h3>
            <div class="mt-4 space-y-4">
                @foreach ($dayItems as $item)
                    @include('partials.timeline-item', ['item' => $item, 'role' => 'lecturer'])
                @endforeach
            </div>
        </div>
    @empty
        <p class="text-sm text-zinc-500">{{ __('No timeline items in this range.') }}</p>
    @endforelse
</x-layouts.app>
