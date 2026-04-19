<x-layouts.auth :title="__('Verify email')">
    <div
        class="rounded-2xl border border-zinc-200/80 bg-white/90 p-8 shadow-xl shadow-zinc-900/5 backdrop-blur-md dark:border-zinc-800 dark:bg-zinc-900/80"
        x-data="{
            d: ['', '', '', '', '', ''],
            get code() { return this.d.join(''); },
            focus(i) { this.$refs['b'+i]?.focus(); },
            onInput(i, e) {
                const v = e.target.value.replace(/\D/g, '').slice(-1);
                this.d[i] = v;
                if (v && i < 5) this.focus(i + 1);
            },
            onKeydown(i, e) {
                if (e.key === 'Backspace' && !this.d[i] && i > 0) {
                    this.focus(i - 1);
                }
            },
            onPaste(e) {
                e.preventDefault();
                const t = (e.clipboardData.getData('text') || '').replace(/\D/g, '').slice(0, 6);
                for (let k = 0; k < 6; k++) this.d[k] = t[k] || '';
                this.focus(Math.min(t.length, 5));
            }
        }"
    >
        <h2 class="font-serif text-2xl font-semibold text-zinc-900 dark:text-zinc-50">{{ __('Check your inbox') }}</h2>
        <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-400">
            {{ __('We sent a 6-digit code to') }} <span class="font-medium text-zinc-900 dark:text-zinc-200">{{ $email }}</span>.
        </p>

        <x-flash class="mt-6" />

        <form method="POST" action="{{ route('otp.verify') }}" class="mt-6 space-y-6">
            @csrf
            <input type="hidden" name="email" value="{{ $email }}">
            <input type="hidden" name="otp" x-bind:value="code">

            <div>
                <p class="mb-3 text-center text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400">{{ __('Verification code') }}</p>
                <div class="flex justify-center gap-2 sm:gap-3" @paste="onPaste($event)">
                    @foreach (range(0, 5) as $i)
                        <input
                            x-ref="b{{ $i }}"
                            type="text"
                            inputmode="numeric"
                            maxlength="1"
                            autocomplete="one-time-code"
                            class="h-12 w-10 rounded-xl border border-zinc-200 bg-white text-center font-mono text-lg font-semibold text-zinc-900 shadow-sm outline-none transition focus:border-brand-500 focus:ring-4 focus:ring-brand-500/20 sm:h-14 sm:w-12 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-100"
                            x-model="d[{{ $i }}]"
                            @input="onInput({{ $i }}, $event)"
                            @keydown="onKeydown({{ $i }}, $event)"
                        />
                    @endforeach
                </div>
                @error('otp')
                    <p class="mt-2 text-center text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="flex w-full items-center justify-center rounded-xl bg-gradient-to-r from-brand-700 to-brand-600 px-4 py-3 text-sm font-semibold text-white shadow-lg shadow-brand-600/25 transition hover:from-brand-600 hover:to-brand-500">
                {{ __('Verify & continue') }}
            </button>
        </form>

        <form method="POST" action="{{ route('otp.resend') }}" class="mt-4">
            @csrf
            <input type="hidden" name="email" value="{{ $email }}">
            <button type="submit" class="w-full text-center text-sm font-medium text-brand-700 hover:text-brand-600 dark:text-brand-300">
                {{ __('Resend code') }}
            </button>
        </form>

        <p class="mt-6 text-center text-sm text-zinc-600 dark:text-zinc-400">
            <a href="{{ route('login') }}" class="font-semibold text-brand-700 hover:text-brand-600 dark:text-brand-300">{{ __('Back to sign in') }}</a>
        </p>
    </div>
</x-layouts.auth>
