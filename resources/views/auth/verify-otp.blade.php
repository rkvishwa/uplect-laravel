<x-auth.split
    :page-title="__('Verify email')"
    :eyebrow="__('VERIFICATION')"
    :heading="__('Check your inbox')"
    :description="__('We sent a 6-digit code to :email. Please enter it below.', ['email' => $email])"
>
    <div
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
        <form method="POST" action="{{ route('otp.verify') }}" class="space-y-6">
            @csrf
            <input type="hidden" name="email" value="{{ $email }}">
            <input type="hidden" name="otp" x-bind:value="code">

            <div>
                <p class="mb-3 text-[13px] font-semibold text-zinc-900">{{ __('Verification code') }}</p>
                <div class="flex justify-between gap-2" @paste="onPaste($event)">
                    @foreach (range(0, 5) as $i)
                        <input
                            x-ref="b{{ $i }}"
                            type="text"
                            inputmode="numeric"
                            maxlength="1"
                            autocomplete="one-time-code"
                            class="h-14 w-full rounded-xl border border-zinc-200 bg-white text-center font-mono text-xl font-semibold text-zinc-900 outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20"
                            x-model="d[{{ $i }}]"
                            @input="onInput({{ $i }}, $event)"
                            @keydown="onKeydown({{ $i }}, $event)"
                        />
                    @endforeach
                </div>
                @error('otp')
                    <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-full bg-brand-600 px-4 py-[14px] text-[14px] font-semibold text-white shadow-sm transition hover:bg-brand-700 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2">
                {{ __('Verify & continue') }}
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M5 12h14M13 5l7 7-7 7"/>
                </svg>
            </button>
        </form>

        <form method="POST" action="{{ route('otp.resend') }}" class="mt-4">
            @csrf
            <input type="hidden" name="email" value="{{ $email }}">
            <button type="submit" class="w-full text-center text-[13px] font-medium text-brand-600 hover:text-brand-700">
                {{ __('Resend code') }}
            </button>
        </form>

        <p class="mt-8 text-center text-[12px] text-zinc-500">
            <a href="{{ route('login') }}" class="font-semibold text-brand-600 hover:text-brand-700">{{ __('Back to sign in') }}</a>
        </p>
    </div>
</x-auth.split>
