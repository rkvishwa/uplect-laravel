<x-layouts.app :header="$course->title" :title="$course->title">
    <x-slot:sidebarNav>
        <x-admin.sidebar active="courses" />
    </x-slot:sidebarNav>

    <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
        <div>
            <p class="font-serif text-xl text-zinc-900 dark:text-zinc-50">{{ __('Timeline') }}: {{ $course->title }}</p>
            <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-400">{{ __('Drag cards to reorder. Add sessions, assignments, or certificate milestones.') }}</p>
        </div>
        <a href="{{ route('admin.courses.edit', $course) }}" class="text-sm font-medium text-brand-700 dark:text-brand-400">{{ __('Edit course') }}</a>
    </div>

    <div x-data="{ open: false, card: 'session' }" class="mt-8 space-y-6">
        <div class="flex flex-wrap gap-3">
            <button type="button" @click="open = true; card = 'session'" class="rounded-xl bg-brand-600 px-4 py-2 text-sm font-semibold text-white">{{ __('+ Session') }}</button>
            <button type="button" @click="open = true; card = 'assignment'" class="rounded-xl border border-zinc-200 bg-white px-4 py-2 text-sm font-semibold dark:border-zinc-700 dark:bg-zinc-900">{{ __('+ Assignment') }}</button>
            <button type="button" @click="open = true; card = 'certificate'" class="rounded-xl border border-zinc-200 bg-white px-4 py-2 text-sm font-semibold dark:border-zinc-700 dark:bg-zinc-900">{{ __('+ Certificate') }}</button>
        </div>

        <div x-show="open" x-cloak class="rounded-2xl border border-zinc-200 bg-white p-6 dark:border-zinc-800 dark:bg-zinc-900/80">
            <form method="POST" action="{{ route('admin.courses.timeline.store', $course) }}" class="space-y-4">
                @csrf
                <input type="hidden" name="card_type" :value="card" />
                <div class="grid gap-4 sm:grid-cols-3">
                    <div>
                        <label class="block text-xs font-medium text-zinc-600">{{ __('Date') }}</label>
                        <input type="date" name="scheduled_date" required class="mt-1 w-full rounded-lg border border-zinc-200 px-3 py-2 text-sm dark:border-zinc-700 dark:bg-zinc-950" value="{{ now()->toDateString() }}" />
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-zinc-600">{{ __('Start (optional)') }}</label>
                        <input type="time" name="scheduled_start_time" class="mt-1 w-full rounded-lg border border-zinc-200 px-3 py-2 text-sm dark:border-zinc-700 dark:bg-zinc-950" />
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-zinc-600">{{ __('End (optional)') }}</label>
                        <input type="time" name="scheduled_end_time" class="mt-1 w-full rounded-lg border border-zinc-200 px-3 py-2 text-sm dark:border-zinc-700 dark:bg-zinc-950" />
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-medium text-zinc-600">{{ __('Title') }}</label>
                    <input type="text" name="title" required class="mt-1 w-full rounded-lg border border-zinc-200 px-3 py-2 text-sm dark:border-zinc-700 dark:bg-zinc-950" />
                </div>
                <div>
                    <label class="block text-xs font-medium text-zinc-600">{{ __('Description') }}</label>
                    <textarea name="description" rows="3" class="mt-1 w-full rounded-lg border border-zinc-200 px-3 py-2 text-sm dark:border-zinc-700 dark:bg-zinc-950"></textarea>
                </div>
                <div x-show="card === 'session'">
                    <label class="block text-xs font-medium text-zinc-600">{{ __('Resource link') }}</label>
                    <input type="url" name="resource_link" class="mt-1 w-full rounded-lg border border-zinc-200 px-3 py-2 text-sm dark:border-zinc-700 dark:bg-zinc-950" />
                    <label class="mt-3 flex items-center gap-2 text-sm">
                        <input type="checkbox" name="is_makeup" value="1" class="rounded border-zinc-300" /> {{ __('Makeup session') }}
                    </label>
                </div>
                <div x-show="card === 'assignment'" x-cloak>
                    <div class="grid gap-3 sm:grid-cols-2">
                        <div>
                            <label class="block text-xs font-medium text-zinc-600">{{ __('Submission') }}</label>
                            <select name="submission_type" class="mt-1 w-full rounded-lg border border-zinc-200 px-3 py-2 text-sm dark:border-zinc-700 dark:bg-zinc-950">
                                <option value="text">{{ __('Text') }}</option>
                                <option value="file">{{ __('File') }}</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-zinc-600">{{ __('Due (optional)') }}</label>
                            <input type="datetime-local" name="due_at" class="mt-1 w-full rounded-lg border border-zinc-200 px-3 py-2 text-sm dark:border-zinc-700 dark:bg-zinc-950" />
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-zinc-600">{{ __('Pass mark') }}</label>
                            <input type="number" name="pass_mark" value="50" class="mt-1 w-full rounded-lg border border-zinc-200 px-3 py-2 text-sm dark:border-zinc-700 dark:bg-zinc-950" />
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-zinc-600">{{ __('Max mark') }}</label>
                            <input type="number" name="max_mark" value="100" class="mt-1 w-full rounded-lg border border-zinc-200 px-3 py-2 text-sm dark:border-zinc-700 dark:bg-zinc-950" />
                        </div>
                    </div>
                    <label class="mt-3 flex items-center gap-2 text-sm">
                        <input type="checkbox" name="is_required_for_certification" value="1" class="rounded border-zinc-300" /> {{ __('Required for certification') }}
                    </label>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="rounded-xl bg-brand-600 px-4 py-2 text-sm font-semibold text-white">{{ __('Add to timeline') }}</button>
                    <button type="button" @click="open = false" class="rounded-xl border border-zinc-200 px-4 py-2 text-sm dark:border-zinc-700">{{ __('Close') }}</button>
                </div>
            </form>
        </div>
    </div>

    @foreach ($grouped as $date => $items)
        <div class="mt-10">
            <h3 class="font-serif text-lg text-zinc-800 dark:text-zinc-100">{{ \Carbon\Carbon::parse($date)->format('l, M j, Y') }}</h3>
            <div class="sortable-list mt-4 space-y-4" id="sort-{{ $date }}" data-date="{{ $date }}">
                @foreach ($items as $item)
                    <div data-id="{{ $item->id }}" class="rounded-2xl border border-zinc-200/80 bg-white/90 p-5 shadow-sm dark:border-zinc-800 dark:bg-zinc-900/70">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex items-center gap-2">
                                <span class="drag-handle cursor-grab text-zinc-400">⠿</span>
                                @if ($item->isSession())
                                    <span class="rounded-full bg-brand-100 px-2 py-0.5 text-xs font-semibold text-brand-800 dark:bg-brand-950/50 dark:text-brand-200">{{ __('Session') }}</span>
                                @elseif($item->isAssignment())
                                    <span class="rounded-full bg-accent-400/30 px-2 py-0.5 text-xs font-semibold text-zinc-900">{{ __('Assignment') }}</span>
                                @else
                                    <span class="rounded-full bg-emerald-100 px-2 py-0.5 text-xs font-semibold text-emerald-900 dark:bg-emerald-950/40 dark:text-emerald-100">{{ __('Certificate') }}</span>
                                @endif
                            </div>
                            @if ($item->status === \App\Models\TimelineItem::STATUS_CANCELLED)
                                <span class="text-xs font-semibold text-red-600">{{ __('Cancelled') }}</span>
                            @endif
                        </div>

                        @if ($item->isSession())
                            @php($s = $item->cardable)
                            <div class="mt-3 space-y-2">
                                <p class="font-semibold text-zinc-900 dark:text-zinc-50">{{ $s->title }}</p>
                                <p class="text-xs text-zinc-500">{{ $item->scheduled_start_time }} – {{ $item->scheduled_end_time }}</p>
                                <div x-data="{ expanded: false }" class="text-sm text-zinc-600 dark:text-zinc-400">
                                    <p :class="expanded ? '' : 'line-clamp-3'">{{ $s->description }}</p>
                                    @if ($s->description)
                                        <button type="button" class="mt-1 text-xs font-medium text-brand-700" @click="expanded = !expanded" x-text="expanded ? @js(__('Show less')) : @js(__('Read more'))"></button>
                                    @endif
                                </div>
                                @if ($item->status === \App\Models\TimelineItem::STATUS_CANCELLED)
                                    <p class="text-sm text-red-600">{{ $item->cancellation_reason }}</p>
                                @endif
                                <div class="flex flex-wrap gap-2 border-t border-zinc-100 pt-3 dark:border-zinc-800">
                                    <form method="POST" action="{{ route('admin.timeline-items.meta', $item) }}" class="flex flex-1 flex-col gap-2 sm:flex-row sm:items-end">
                                        @csrf
                                        @method('PATCH')
                                        <input type="text" name="title" value="{{ $s->title }}" class="rounded-lg border border-zinc-200 px-2 py-1 text-sm dark:border-zinc-700 dark:bg-zinc-950" />
                                        <button class="text-xs font-medium text-brand-700">{{ __('Save title/desc') }}</button>
                                        <textarea name="description" rows="2" class="w-full rounded-lg border border-zinc-200 px-2 py-1 text-sm dark:border-zinc-700 dark:bg-zinc-950">{{ $s->description }}</textarea>
                                    </form>
                                    <form method="POST" action="{{ route('admin.timeline-items.time', $item) }}" class="flex flex-wrap items-end gap-2">
                                        @csrf
                                        @method('PATCH')
                                        <input type="time" name="scheduled_start_time" value="{{ \Illuminate\Support\Str::substr((string) $item->scheduled_start_time, 0, 5) }}" class="rounded-lg border border-zinc-200 px-2 py-1 text-sm dark:border-zinc-700 dark:bg-zinc-950" />
                                        <input type="time" name="scheduled_end_time" value="{{ \Illuminate\Support\Str::substr((string) $item->scheduled_end_time, 0, 5) }}" class="rounded-lg border border-zinc-200 px-2 py-1 text-sm dark:border-zinc-700 dark:bg-zinc-950" />
                                        <button class="text-xs font-medium text-brand-700">{{ __('Update time') }}</button>
                                    </form>
                                </div>
                                @if ($item->status !== \App\Models\TimelineItem::STATUS_CANCELLED)
                                    <form method="POST" action="{{ route('admin.timeline-items.cancel', $item) }}" class="mt-2 flex flex-col gap-2 sm:flex-row sm:items-center">
                                        @csrf
                                        <input type="text" name="cancellation_reason" placeholder="{{ __('Cancellation reason') }}" class="flex-1 rounded-lg border border-zinc-200 px-2 py-1 text-sm dark:border-zinc-700 dark:bg-zinc-950" />
                                        <button class="text-xs font-medium text-red-600" onclick="return confirm(@js(__('Cancel this session?')))">{{ __('Cancel session') }}</button>
                                    </form>
                                @endif
                                <form method="POST" action="{{ route('admin.timeline-items.recording', $item) }}" class="mt-2 flex gap-2">
                                    @csrf
                                    <input type="url" name="recording_url" placeholder="Vimeo URL" class="flex-1 rounded-lg border border-zinc-200 px-2 py-1 text-sm dark:border-zinc-700 dark:bg-zinc-950" />
                                    <button class="text-xs font-medium text-brand-700">{{ __('Save recording') }}</button>
                                </form>
                                @php($zm = $s->zoomMeeting)
                                <div class="mt-3 rounded-xl bg-zinc-50 p-3 text-sm dark:bg-zinc-950/50">
                                    <p class="font-medium text-zinc-800 dark:text-zinc-200">{{ __('Zoom') }}</p>
                                    @if ($zm)
                                        <p class="text-xs text-zinc-500">{{ $zm->topic }} · {{ $zm->start_at }}</p>
                                        <div class="mt-2 flex flex-wrap gap-2">
                                            <a href="{{ route('admin.timeline-items.zoom.host', $item) }}" target="_blank" class="text-xs font-semibold text-brand-700">{{ __('Open host link') }}</a>
                                            <form method="POST" action="{{ route('admin.timeline-items.zoom.destroy', $item) }}" onsubmit="return confirm(@js(__('Delete Zoom link?')))">
                                                @csrf
                                                @method('DELETE')
                                                <button class="text-xs text-red-600">{{ __('Delete Zoom link') }}</button>
                                            </form>
                                        </div>
                                    @else
                                        @php($past = $item->scheduled_date->lt(now()->startOfDay()))
                                        <form method="POST" action="{{ route('admin.timeline-items.zoom.store', $item) }}">
                                            @csrf
                                            <button type="submit" @disabled($past) @class(['rounded-lg px-3 py-1 text-xs font-semibold text-white', 'bg-brand-600' => ! $past, 'cursor-not-allowed bg-zinc-400' => $past])>
                                                {{ __('Create Zoom link') }}
                                            </button>
                                            @if ($past)
                                                <span class="ml-2 text-xs text-zinc-500">{{ __('Date has passed') }}</span>
                                            @endif
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @elseif($item->isAssignment())
                            @php($a = $item->cardable)
                            <div class="mt-3">
                                <p class="font-semibold">{{ $a->title }}</p>
                                <p class="text-xs text-zinc-500">{{ __('Pass') }}: {{ $a->pass_mark }} / {{ $a->max_mark }} @if($a->is_required_for_certification) · <span class="font-medium text-amber-700">{{ __('Required for cert') }}</span> @endif</p>
                                <p class="mt-2 line-clamp-3 text-sm text-zinc-600 dark:text-zinc-400">{{ $a->description }}</p>
                            </div>
                        @else
                            @php($c = $item->cardable)
                            <div class="mt-3">
                                <p class="font-semibold">{{ $c->title }}</p>
                                <p class="text-sm text-zinc-600 dark:text-zinc-400">{{ $c->description }}</p>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (!window.Sortable) return;
            document.querySelectorAll('.sortable-list').forEach((list) => {
                window.Sortable.create(list, {
                    animation: 150,
                    handle: '.drag-handle',
                    onEnd: () => {
                        const ids = [...list.querySelectorAll('[data-id]')].map((el) => Number(el.dataset.id));
                        fetch(@json(route('admin.timeline-items.reorder')), {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Content-Type': 'application/json',
                                Accept: 'application/json',
                            },
                            body: JSON.stringify({
                                course_id: {{ $course->id }},
                                scheduled_date: list.dataset.date,
                                order: ids,
                            }),
                        });
                    },
                });
            });
        });
    </script>
</x-layouts.app>
