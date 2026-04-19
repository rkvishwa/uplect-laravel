<x-layouts.auth :title="__('Sign in')">
    <div class="rounded-2xl border border-zinc-200/80 bg-white/90 p-8 shadow-xl shadow-zinc-900/5 backdrop-blur-md dark:border-zinc-800 dark:bg-zinc-900/80">
        <h2 class="font-serif text-2xl font-semibold text-zinc-900 dark:text-zinc-50">{{ __('Welcome back') }}</h2>
        <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-400">{{ __('Sign in to continue to your Uplect workspace.') }}</p>

        <x-flash class="mt-6" />

        <form method="POST" action="{{ route('login.store') }}" class="mt-6 space-y-5">
            @csrf
            <div>
                <label for="email" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">{{ __('Email') }}</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required autocomplete="username"
                    class="mt-1.5 w-full rounded-xl border border-zinc-200 bg-white px-4 py-2.5 text-zinc-900 shadow-sm outline-none ring-brand-500/0 transition focus:border-brand-500 focus:ring-4 focus:ring-brand-500/20 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-100">
            </div>
            <div>
                <label for="password" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">{{ __('Password') }}</label>
                <input id="password" name="password" type="password" required autocomplete="current-password"
                    class="mt-1.5 w-full rounded-xl border border-zinc-200 bg-white px-4 py-2.5 text-zinc-900 shadow-sm outline-none ring-brand-500/0 transition focus:border-brand-500 focus:ring-4 focus:ring-brand-500/20 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-100">
            </div>
            <div class="flex items-center justify-between gap-4 text-sm">
                <label class="inline-flex items-center gap-2 text-zinc-600 dark:text-zinc-400">
                    <input type="checkbox" name="remember" value="1" class="rounded border-zinc-300 text-brand-600 focus:ring-brand-500 dark:border-zinc-600 dark:bg-zinc-900">
                    {{ __('Remember me') }}
                </label>
                <a href="{{ route('password.request') }}" class="font-medium text-brand-700 hover:text-brand-600 dark:text-brand-300 dark:hover:text-brand-200">{{ __('Forgot password?') }}</a>
            </div>
            <button type="submit" class="flex w-full items-center justify-center rounded-xl bg-gradient-to-r from-brand-700 to-brand-600 px-4 py-3 text-sm font-semibold text-white shadow-lg shadow-brand-600/25 transition hover:from-brand-600 hover:to-brand-500">
                {{ __('Sign in') }}
            </button>
        </form>

        <p class="mt-6 text-center text-sm text-zinc-600 dark:text-zinc-400">
            {{ __('New to Uplect?') }}
            <a href="{{ route('register') }}" class="font-semibold text-brand-700 hover:text-brand-600 dark:text-brand-300">{{ __('Create an account') }}</a>
        </p>
    </div>
</x-layouts.auth>
