@php
    $user = auth()->user();
    $nameParts = explode(' ', $user->name, 2);
    $firstName = $nameParts[0] ?? $user->name;
    $lastName = $nameParts[1] ?? '-';
    $phone = $user->phone;
    if ($phone === null || $phone === '' || ! preg_match('/^[0-9+\-\s]{8,}$/', (string) $phone)) {
        $phone = '0770000000';
    }
@endphp

<x-layouts.app :header="__('Pay with PayHere')" :title="__('Pay with PayHere')">
    <x-slot:sidebarNav>
        <x-student.sidebar active="enrollments" />
    </x-slot:sidebarNav>

    <x-slot:scripts>
        <script type="text/javascript" src="https://www.payhere.lk/lib/payhere.js"></script>
        <script>
            (function () {
                var enrollmentsUrl = @json(route('student.enrollments.index'));
                var returnUrl = @json(route('payhere.return'));
                var payment = {
                    sandbox: @json((bool) config('payhere.sandbox')),
                    merchant_id: @json((string) config('payhere.merchant_id')),
                    return_url: returnUrl,
                    cancel_url: enrollmentsUrl,
                    notify_url: @json(url(route('payhere.notify', [], false))),
                    order_id: @json($orderId),
                    items: @json($enrollment->course->title),
                    amount: @json($amount),
                    currency: @json($currency),
                    hash: @json($hash),
                    first_name: @json($firstName),
                    last_name: @json($lastName),
                    email: @json($user->email),
                    phone: @json($phone),
                    address: @json(__('N/A')),
                    city: @json('Colombo'),
                    country: @json('Sri Lanka'),
                    custom_1: @json('enrollment:'.$enrollment->id),
                    custom_2: '',
                };

                payhere.onCompleted = function onCompleted(orderId) {
                    window.location.href = returnUrl;
                };

                payhere.onDismissed = function onDismissed() {
                    window.location.href = enrollmentsUrl;
                };

                payhere.onError = function onError(error) {
                    alert(error);
                };

                document.addEventListener('DOMContentLoaded', function () {
                    var btn = document.getElementById('payhere-payment');
                    if (btn) {
                        btn.addEventListener('click', function (e) {
                            e.preventDefault();
                            payhere.startPayment(payment);
                        });
                    }
                });
            })();
        </script>
    </x-slot:scripts>

    <div class="mx-auto max-w-lg rounded-2xl border border-zinc-200/80 bg-white/90 p-8 shadow-sm dark:border-zinc-800 dark:bg-zinc-900/70">
        <p class="font-serif text-xl text-zinc-900 dark:text-zinc-50">{{ __('Complete payment') }}</p>
        <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-400">
            {{ __('Course') }}: <span class="font-medium text-zinc-800 dark:text-zinc-200">{{ $enrollment->course->title }}</span>
        </p>
        <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-400">
            {{ __('Amount') }}: <span class="font-semibold text-brand-800 dark:text-brand-200">{{ $currency }} {{ $amount }}</span>
        </p>
        <p class="mt-4 text-xs leading-relaxed text-zinc-500 dark:text-zinc-400">
            {{ __('Payment opens in a secure PayHere window. Your enrollment activates after PayHere confirms payment to our server.') }}
        </p>
        <button
            type="button"
            id="payhere-payment"
            class="mt-6 w-full rounded-xl bg-gradient-to-r from-brand-700 to-brand-600 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-brand-600/20 transition hover:from-brand-600 hover:to-brand-500"
        >
            {{ __('Pay with PayHere') }}
        </button>
        <a href="{{ route('student.enrollments.index') }}" class="mt-4 block text-center text-sm font-medium text-zinc-600 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-zinc-100">
            {{ __('Cancel and back to enrollments') }}
        </a>
    </div>
</x-layouts.app>
