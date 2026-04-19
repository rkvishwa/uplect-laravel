<x-layouts.app :header="__('Enrollments')" :title="__('Enrollments')">
    <x-slot:sidebarNav>
        <x-admin.sidebar active="enrollments" />
    </x-slot:sidebarNav>

    <div class="flex flex-wrap gap-4">
        <a href="{{ route('admin.enrollments.index', ['status' => \App\Models\Enrollment::STATUS_PENDING]) }}" @class(['text-sm font-semibold', 'text-brand-700' => request('status', \App\Models\Enrollment::STATUS_PENDING) === \App\Models\Enrollment::STATUS_PENDING])>{{ __('Pending') }}</a>
        <a href="{{ route('admin.enrollments.index', ['status' => \App\Models\Enrollment::STATUS_ACTIVE]) }}" @class(['text-sm font-semibold', 'text-brand-700' => request('status') === \App\Models\Enrollment::STATUS_ACTIVE])>{{ __('Active') }}</a>
        <a href="{{ route('admin.enrollments.index', ['status' => \App\Models\Enrollment::STATUS_DECLINED]) }}" @class(['text-sm font-semibold', 'text-brand-700' => request('status') === \App\Models\Enrollment::STATUS_DECLINED])>{{ __('Declined') }}</a>
    </div>

    <div class="mt-8 overflow-hidden rounded-2xl border border-zinc-200/80 bg-white/90 shadow-sm dark:border-zinc-800 dark:bg-zinc-900/70">
        <table class="min-w-full divide-y divide-zinc-200 text-sm dark:divide-zinc-800">
            <thead class="bg-zinc-50/80 dark:bg-zinc-950/50">
                <tr>
                    <th class="px-4 py-3 text-left font-semibold">{{ __('Student') }}</th>
                    <th class="px-4 py-3 text-left font-semibold">{{ __('Course') }}</th>
                    <th class="px-4 py-3 text-left font-semibold">{{ __('Method') }}</th>
                    <th class="px-4 py-3 text-left font-semibold">{{ __('Status') }}</th>
                    <th class="px-4 py-3 text-right font-semibold">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-200 dark:divide-zinc-800">
                @forelse ($enrollments as $e)
                    <tr>
                        <td class="px-4 py-3">{{ $e->student->name }}<br><span class="text-xs text-zinc-500">{{ $e->student->email }}</span></td>
                        <td class="px-4 py-3">{{ $e->course->title }}</td>
                        <td class="px-4 py-3">{{ $e->payment_method }}</td>
                        <td class="px-4 py-3">{{ $e->status }}</td>
                        <td class="px-4 py-3 text-right">
                            @if ($e->payment_method === \App\Models\Enrollment::PAYMENT_BANK_TRANSFER && $e->bank_slip_path)
                                <a href="{{ route('admin.enrollments.slip', $e) }}" class="text-xs font-medium text-brand-700">{{ __('Slip') }}</a>
                            @endif
                            @if ($e->status === \App\Models\Enrollment::STATUS_PENDING)
                                <form method="POST" action="{{ route('admin.enrollments.approve', $e) }}" class="inline">@csrf<button class="ml-2 text-xs font-medium text-emerald-700">{{ __('Approve') }}</button></form>
                                <form method="POST" action="{{ route('admin.enrollments.decline', $e) }}" class="mt-2 space-y-1 text-left">
                                    @csrf
                                    <textarea name="decline_reason" rows="2" required class="w-full rounded border border-zinc-200 px-2 py-1 text-xs dark:border-zinc-700 dark:bg-zinc-950" placeholder="{{ __('Reason') }}"></textarea>
                                    <button type="submit" class="text-xs font-medium text-red-600">{{ __('Decline') }}</button>
                                </form>
                            @endif
                            @if ($e->status === \App\Models\Enrollment::STATUS_DECLINED && $e->decline_reason)
                                <p class="mt-1 text-xs text-red-600">{{ $e->decline_reason }}</p>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-8 text-center text-zinc-500">{{ __('No enrollments.') }}</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-6">{{ $enrollments->links() }}</div>
</x-layouts.app>
