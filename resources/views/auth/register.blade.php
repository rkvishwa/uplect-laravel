<x-layouts.auth :title="__('Register')">
    <div class="rounded-2xl border border-zinc-200/80 bg-white/90 p-8 shadow-xl shadow-zinc-900/5 backdrop-blur-md dark:border-zinc-800 dark:bg-zinc-900/80">
        <h2 class="font-serif text-2xl font-semibold text-zinc-900 dark:text-zinc-50">{{ __('Create your account') }}</h2>
        <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-400">{{ __('Students register here. You’ll verify your email with a one-time code.') }}</p>

        <x-flash class="mt-6" />

        <form method="POST" action="{{ route('register.store') }}" class="mt-6 space-y-4">
            @csrf
            <div>
                <label for="name" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">{{ __('Full name') }}</label>
                <input id="name" name="name" type="text" value="{{ old('name') }}" required autocomplete="name"
                    class="mt-1.5 w-full rounded-xl border border-zinc-200 bg-white px-4 py-2.5 text-zinc-900 shadow-sm outline-none transition focus:border-brand-500 focus:ring-4 focus:ring-brand-500/20 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-100">
            </div>
            <div>
                <label for="email" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">{{ __('Email') }}</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required autocomplete="email"
                    class="mt-1.5 w-full rounded-xl border border-zinc-200 bg-white px-4 py-2.5 text-zinc-900 shadow-sm outline-none transition focus:border-brand-500 focus:ring-4 focus:ring-brand-500/20 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-100">
            </div>
            <div>
                <label for="phone" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">{{ __('Phone') }}</label>
                <input id="phone" name="phone" type="tel" value="{{ old('phone') }}" required autocomplete="tel"
                    class="mt-1.5 w-full rounded-xl border border-zinc-200 bg-white px-4 py-2.5 text-zinc-900 shadow-sm outline-none transition focus:border-brand-500 focus:ring-4 focus:ring-brand-500/20 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-100">
            </div>
            <div>
                <label for="password" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">{{ __('Password') }}</label>
                <input id="password" name="password" type="password" required autocomplete="new-password"
                    class="mt-1.5 w-full rounded-xl border border-zinc-200 bg-white px-4 py-2.5 text-zinc-900 shadow-sm outline-none transition focus:border-brand-500 focus:ring-4 focus:ring-brand-500/20 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-100">
            </div>
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">{{ __('Confirm password') }}</label>
                <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password"
                    class="mt-1.5 w-full rounded-xl border border-zinc-200 bg-white px-4 py-2.5 text-zinc-900 shadow-sm outline-none transition focus:border-brand-500 focus:ring-4 focus:ring-brand-500/20 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-100">
            </div>
            <button type="submit" class="mt-2 flex w-full items-center justify-center rounded-xl bg-gradient-to-r from-brand-700 to-brand-600 px-4 py-3 text-sm font-semibold text-white shadow-lg shadow-brand-600/25 transition hover:from-brand-600 hover:to-brand-500">
                {{ __('Continue') }}
            </button>
        </form>

        <p class="mt-6 text-center text-sm text-zinc-600 dark:text-zinc-400">
            {{ __('Already have an account?') }}
            <a href="{{ route('login') }}" class="font-semibold text-brand-700 hover:text-brand-600 dark:text-brand-300">{{ __('Sign in') }}</a>
        </p>
    </div>
</x-layouts.auth>
