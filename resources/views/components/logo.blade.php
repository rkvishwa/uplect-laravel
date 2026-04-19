@props(['class' => ''])

<div {{ $attributes->merge(['class' => 'inline-flex items-center gap-3 '.$class]) }}>
    <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-gradient-to-br from-brand-600 to-brand-400 text-lg font-bold text-white shadow-lg shadow-brand-600/25 ring-1 ring-white/20 font-serif">
        U
    </span>
    <span class="font-serif text-2xl font-semibold tracking-tight text-zinc-900 dark:text-zinc-50">Uplect</span>
</div>
