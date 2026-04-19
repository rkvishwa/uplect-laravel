<x-auth.split
    :page-title="__('Forgot password')"
    :eyebrow="__('RECOVERY')"
    :heading="__('Reset link')"
    :description="__('Enter your email and we’ll send a link to choose a new password.')"
>
    <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
        @csrf

        <div>
            <label for="email" class="mb-1.5 block text-[13px] font-semibold text-zinc-900">{{ __('Email address') }}</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" required autocomplete="username" placeholder="Enter your email address"
                class="w-full rounded-xl border border-zinc-200 bg-white px-4 py-3 text-[14px] text-zinc-900 outline-none transition placeholder:text-zinc-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20">
            @error('email')
                <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="mt-5 inline-flex w-full items-center justify-center gap-2 rounded-full bg-brand-600 px-4 py-[14px] text-[14px] font-semibold text-white shadow-sm transition hover:bg-brand-700 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2">
            {{ __('Email reset link') }}
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M5 12h14M13 5l7 7-7 7"/>
            </svg>
        </button>
    </form>

    <p class="mt-8 text-center text-[12px] text-zinc-500">
        <a href="{{ route('login') }}" class="font-semibold text-brand-600 hover:text-brand-700">{{ __('Back to sign in') }}</a>
    </p>
</x-auth.split>
