@props([
    'class' => '',
    'variant' => 'full',
])

@php
    $isMark = $variant === 'mark';
@endphp

<div {{ $attributes->merge(['class' => $isMark ? 'inline-flex items-center '.$class : 'inline-flex items-center gap-2.5 '.$class]) }}>
    <img src="{{ asset('images/logo.png') }}" alt="{{ config('app.name') }} Logo" class="{{ $isMark ? 'h-9 w-auto object-contain' : 'h-9 w-auto shrink-0 object-contain' }}">
    @unless ($isMark)
        <span class="text-[18px] font-bold tracking-tight text-zinc-900 dark:text-zinc-50">{{ config('app.name') }}</span>
    @endunless
</div>
