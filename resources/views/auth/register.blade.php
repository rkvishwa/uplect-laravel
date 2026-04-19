<x-auth.split
    :page-title="__('Register')"
    :heading="__('Create your account')"
    :description="__('Students register here. You’ll verify your email with a one-time code.')"
    content-vertical-align="start"
    content-bottom-class="pb-28 lg:pb-36"
>
    <form
        method="POST"
        action="{{ route('register.store') }}"
        class="space-y-4"
        x-data="{ accepted: {{ old('accept_policies') ? 'true' : 'false' }} }"
    >
        @csrf

        <div>
            <label for="name" class="mb-1.5 block text-[13px] font-semibold text-zinc-900">{{ __('Full name') }}</label>
            <input id="name" name="name" type="text" value="{{ old('name') }}" required autocomplete="name" placeholder="Enter your full name"
                class="w-full rounded-xl border border-zinc-200 bg-white px-4 py-3 text-[14px] text-zinc-900 outline-none transition placeholder:text-zinc-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20">
            @error('name')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="email" class="mb-1.5 block text-[13px] font-semibold text-zinc-900">{{ __('Email address') }}</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" required autocomplete="email" placeholder="Enter your email address"
                class="w-full rounded-xl border border-zinc-200 bg-white px-4 py-3 text-[14px] text-zinc-900 outline-none transition placeholder:text-zinc-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20">
            @error('email')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="phone" class="mb-1.5 block text-[13px] font-semibold text-zinc-900">{{ __('Phone number') }}</label>
            <input id="phone" name="phone" type="tel" value="{{ old('phone') }}" required autocomplete="tel" placeholder="Enter your phone number"
                class="w-full rounded-xl border border-zinc-200 bg-white px-4 py-3 text-[14px] text-zinc-900 outline-none transition placeholder:text-zinc-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20">
            @error('phone')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="password" class="mb-1.5 block text-[13px] font-semibold text-zinc-900">{{ __('Password') }}</label>
            <input id="password" name="password" type="password" required autocomplete="new-password" placeholder="Create a password"
                class="w-full rounded-xl border border-zinc-200 bg-white px-4 py-3 text-[14px] text-zinc-900 outline-none transition placeholder:text-zinc-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20">
            @error('password')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="password_confirmation" class="mb-1.5 block text-[13px] font-semibold text-zinc-900">{{ __('Confirm password') }}</label>
            <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password" placeholder="Confirm your password"
                class="w-full rounded-xl border border-zinc-200 bg-white px-4 py-3 text-[14px] text-zinc-900 outline-none transition placeholder:text-zinc-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20">
        </div>

        <div class="pt-1">
            <label class="flex cursor-pointer gap-3 text-[13px] leading-snug text-zinc-700">
                <input
                    type="checkbox"
                    name="accept_policies"
                    value="1"
                    class="mt-0.5 shrink-0 rounded border-zinc-300 text-brand-600 focus:ring-brand-500"
                    x-model="accepted"
                    required
                >
                <span>
                    {{ __('I have read and agree to the') }}
                    <a href="{{ route('legal.terms') }}" target="_blank" rel="noopener noreferrer" class="font-medium text-brand-600 underline decoration-brand-600/30 underline-offset-2 hover:text-brand-700">{{ __('Terms and Conditions') }}</a>,
                    <a href="{{ route('legal.privacy') }}" target="_blank" rel="noopener noreferrer" class="font-medium text-brand-600 underline decoration-brand-600/30 underline-offset-2 hover:text-brand-700">{{ __('Privacy Policy') }}</a>,
                    {{ __('and') }}
                    <a href="{{ route('legal.returns') }}" target="_blank" rel="noopener noreferrer" class="font-medium text-brand-600 underline decoration-brand-600/30 underline-offset-2 hover:text-brand-700">{{ __('Return Policy') }}</a>.
                </span>
            </label>
            @error('accept_policies')
                <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <button
            type="submit"
            class="mt-5 inline-flex w-full items-center justify-center gap-2 rounded-full bg-brand-600 px-4 py-[14px] text-[14px] font-semibold text-white shadow-sm transition hover:bg-brand-700 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2 disabled:pointer-events-none disabled:opacity-45"
            x-bind:disabled="!accepted"
        >
            {{ __('Continue') }}
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M5 12h14M13 5l7 7-7 7"/>
            </svg>
        </button>
    </form>

    <p class="mt-8 pb-8 text-center text-[12px] text-zinc-500 lg:pb-10">
        {{ __('Already have an account?') }}
        <a href="{{ route('login') }}" class="font-semibold text-brand-600 hover:text-brand-700">{{ __('Sign in') }}</a>
    </p>
</x-auth.split>
