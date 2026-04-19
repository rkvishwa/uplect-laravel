@props(['class' => ''])

<button
    type="button"
    {{ $attributes->merge(['class' => 'inline-flex h-10 w-10 items-center justify-center rounded-xl border border-zinc-200/80 bg-white/80 text-zinc-600 shadow-sm backdrop-blur-sm transition hover:border-brand-300 hover:text-brand-700 dark:border-zinc-700 dark:bg-zinc-900/80 dark:text-zinc-300 dark:hover:border-brand-500 dark:hover:text-brand-200 '.$class]) }}
    x-data="{
        dark: false,
        init() {
            this.dark = document.documentElement.classList.contains('dark');
        },
        toggle() {
            this.dark = !this.dark;
            if (this.dark) {
                document.documentElement.classList.add('dark');
                localStorage.setItem('theme', 'dark');
            } else {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('theme', 'light');
            }
        }
    }"
    @click="toggle()"
    :aria-label="dark ? 'Switch to light mode' : 'Switch to dark mode'"
>
    <span x-show="!dark" x-cloak class="text-lg" aria-hidden="true">&#9790;</span>
    <span x-show="dark" x-cloak class="text-lg" aria-hidden="true">&#9788;</span>
</button>
