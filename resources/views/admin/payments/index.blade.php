<x-layouts.app :header="__('Payments')" :title="__('Payments')">
    <x-slot:sidebarNav>
        <x-admin.sidebar active="payments" />
    </x-slot:sidebarNav>

    <div class="flex flex-col gap-6 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-zinc-900 dark:text-zinc-50">{{ __('Payments') }}</h1>
            <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">{{ __('Review bank slips and payment-backed course access requests.') }}</p>
        </div>
    </div>

    {{-- Tabs/Filters --}}
    <div class="mt-8 flex flex-wrap gap-2">
        @php
            $currentStatus = request('status', \App\Models\Enrollment::STATUS_PENDING);
        @endphp

        <a href="{{ route('admin.payments.index', ['status' => \App\Models\Enrollment::STATUS_PENDING]) }}"
            @class([
                'inline-flex items-center rounded-full px-4 py-2 text-sm font-medium transition-colors',
                'bg-zinc-900 text-white shadow-sm dark:bg-white dark:text-zinc-900' => $currentStatus === \App\Models\Enrollment::STATUS_PENDING,
                'bg-white text-zinc-600 ring-1 ring-zinc-200 hover:bg-zinc-50 dark:bg-zinc-900/50 dark:text-zinc-400 dark:ring-zinc-800 dark:hover:bg-zinc-800/80 hover:text-zinc-900 dark:hover:text-zinc-200' => $currentStatus !== \App\Models\Enrollment::STATUS_PENDING,
            ])>
            {{ __('Pending Review') }}
            @if($currentStatus === \App\Models\Enrollment::STATUS_PENDING)
                <span class="ml-2 flex h-5 w-5 items-center justify-center rounded-full bg-white/20 text-[10px] text-white dark:bg-zinc-900/20 dark:text-zinc-900">{{ $enrollments->total() }}</span>
            @endif
        </a>

        <a href="{{ route('admin.payments.index', ['status' => \App\Models\Enrollment::STATUS_ACTIVE]) }}"
            @class([
                'inline-flex items-center rounded-full px-4 py-2 text-sm font-medium transition-colors',
                'bg-zinc-900 text-white shadow-sm dark:bg-white dark:text-zinc-900' => $currentStatus === \App\Models\Enrollment::STATUS_ACTIVE,
                'bg-white text-zinc-600 ring-1 ring-zinc-200 hover:bg-zinc-50 dark:bg-zinc-900/50 dark:text-zinc-400 dark:ring-zinc-800 dark:hover:bg-zinc-800/80 hover:text-zinc-900 dark:hover:text-zinc-200' => $currentStatus !== \App\Models\Enrollment::STATUS_ACTIVE,
            ])>
            {{ __('Active') }}
        </a>

        <a href="{{ route('admin.payments.index', ['status' => \App\Models\Enrollment::STATUS_DECLINED]) }}"
            @class([
                'inline-flex items-center rounded-full px-4 py-2 text-sm font-medium transition-colors',
                'bg-zinc-900 text-white shadow-sm dark:bg-white dark:text-zinc-900' => $currentStatus === \App\Models\Enrollment::STATUS_DECLINED,
                'bg-white text-zinc-600 ring-1 ring-zinc-200 hover:bg-zinc-50 dark:bg-zinc-900/50 dark:text-zinc-400 dark:ring-zinc-800 dark:hover:bg-zinc-800/80 hover:text-zinc-900 dark:hover:text-zinc-200' => $currentStatus !== \App\Models\Enrollment::STATUS_DECLINED,
            ])>
            {{ __('Declined') }}
        </a>
    </div>

    {{-- Data Table --}}
    <div class="mt-6 overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-zinc-200/50 dark:bg-zinc-900/50 dark:ring-zinc-800">
        <div class="overflow-x-auto">
            <table class="min-w-full text-left text-sm whitespace-nowrap">
                <thead class="border-b border-zinc-200/50 bg-zinc-50/50 dark:border-zinc-800 dark:bg-zinc-900/50">
                    <tr>
                        <th class="px-6 py-4 font-semibold text-zinc-900 dark:text-zinc-100">{{ __('Student') }}</th>
                        <th class="px-6 py-4 font-semibold text-zinc-900 dark:text-zinc-100">{{ __('Course') }}</th>
                        <th class="px-6 py-4 font-semibold text-zinc-900 dark:text-zinc-100">{{ __('Method') }}</th>
                        <th class="px-6 py-4 font-semibold text-zinc-900 dark:text-zinc-100">{{ __('Status') }}</th>
                        <th class="px-6 py-4 text-right font-semibold text-zinc-900 dark:text-zinc-100">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800/80">
                    @forelse ($enrollments as $e)
                        <tr class="transition-colors hover:bg-zinc-50/50 dark:hover:bg-zinc-800/30">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-brand-50 text-brand-700 dark:bg-brand-500/20 dark:text-brand-300">
                                        {{ substr($e->student->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="font-medium text-zinc-900 dark:text-zinc-100">{{ $e->student->name }}</p>
                                        <p class="mt-0.5 text-xs text-zinc-500 dark:text-zinc-400">{{ $e->student->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="max-w-[250px] truncate font-medium text-zinc-700 dark:text-zinc-300">
                                    {{ $e->course->title }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center gap-1.5 rounded-md text-[13px] text-zinc-600 dark:text-zinc-400">
                                    @if($e->payment_method === \App\Models\Enrollment::PAYMENT_BANK_TRANSFER)
                                        <svg class="h-4 w-4 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z" /></svg>
                                        {{ __('Bank Transfer') }}
                                    @else
                                        <svg class="h-4 w-4 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z" /></svg>
                                        {{ ucfirst($e->payment_method) }}
                                    @endif
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @if($e->status === 'pending')
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-50 px-2.5 py-1 text-xs font-semibold text-amber-700 dark:bg-amber-500/10 dark:text-amber-400">
                                        <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>
                                        {{ __('Pending') }}
                                    </span>
                                @elseif($e->status === 'active')
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400">
                                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                        {{ __('Active') }}
                                    </span>
                                @elseif($e->status === 'declined')
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-red-50 px-2.5 py-1 text-xs font-semibold text-red-700 dark:bg-red-500/10 dark:text-red-400">
                                        <span class="h-1.5 w-1.5 rounded-full bg-red-500"></span>
                                        {{ __('Declined') }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-zinc-100 px-2.5 py-1 text-xs font-medium text-zinc-600 dark:bg-zinc-800 dark:text-zinc-400">
                                        {{ ucfirst($e->status) }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    @if ($e->payment_method === \App\Models\Enrollment::PAYMENT_BANK_TRANSFER && $e->bank_slip_path)
                                        <a href="{{ route('admin.payments.slip', $e) }}" target="_blank" class="rounded-lg px-3 py-1.5 text-xs font-semibold text-brand-700 transition hover:bg-brand-50 dark:text-brand-400 dark:hover:bg-brand-500/10">
                                            {{ __('View Slip') }} &nearr;
                                        </a>
                                    @endif

                                    @if ($e->status === \App\Models\Enrollment::STATUS_PENDING)
                                        <div class="flex items-center gap-1 border-l border-zinc-200 pl-2 dark:border-zinc-700">
                                            <form method="POST" action="{{ route('admin.payments.approve', $e) }}">
                                                @csrf
                                                <button type="submit" x-data x-on:click.prevent="$el.closest('form').submit()" class="rounded-lg bg-emerald-50 px-3 py-1.5 text-xs font-bold text-emerald-700 transition hover:bg-emerald-100 dark:bg-emerald-500/10 dark:text-emerald-400 dark:hover:bg-emerald-500/20">
                                                    {{ __('Approve') }}
                                                </button>
                                            </form>

                                            <form
                                                method="POST"
                                                action="{{ route('admin.payments.decline', $e) }}"
                                                data-confirm="{{ __('Decline this payment request?') }}"
                                                data-confirm-ok="{{ __('Decline') }}"
                                                data-confirm-cancel="{{ __('Cancel') }}"
                                            >
                                                @csrf
                                                <button type="submit" class="rounded-lg px-3 py-1.5 text-xs font-semibold text-red-600 transition hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-500/10">
                                                    {{ __('Decline') }}
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-zinc-100 dark:bg-zinc-800">
                                        <svg class="h-6 w-6 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m6.75 12l-3-3m0 0l-3 3m3-3v6m-1.5-15H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" /></svg>
                                    </div>
                                    <p class="mt-4 font-medium text-zinc-900 dark:text-zinc-100">{{ __('No payments found') }}</p>
                                    <p class="mt-1 text-sm text-zinc-500">{{ __('There are no records matching the current filter.') }}</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">
        {{ $enrollments->links() }}
    </div>
</x-layouts.app>
