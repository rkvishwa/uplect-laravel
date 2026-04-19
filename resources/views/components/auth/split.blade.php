@props([
    'pageTitle' => null,
    'eyebrow' => null,
    'heading' => '',
    'description' => null,
    /** center: vertically center form on tall viewports; start: align to top (long forms) */
    'contentVerticalAlign' => 'center',
    /** Override default bottom padding on the scroll column (e.g. register needs more space) */
    'contentBottomClass' => null,
])

@php
    $leftBottom = $contentBottomClass ?? 'pb-12 lg:pb-16';
@endphp

<x-layouts.auth :title="$pageTitle ?? $heading">
    <div class="flex min-h-[100dvh] w-full flex-col lg:h-[100dvh] lg:max-h-[100dvh] lg:flex-row lg:items-stretch lg:overflow-hidden">

        {{-- Left: scrolls; scrollbar hidden; logo + content share same offsets on every page --}}
        <div @class([
            'flex min-h-0 w-full flex-col overflow-y-auto scrollbar-none bg-white px-6 sm:px-12 lg:w-1/2 lg:flex-1 lg:px-16 xl:px-24',
            'pt-10 lg:pt-12',
            $leftBottom,
        ])>
            {{-- Logo: identical top offset on all auth pages --}}
            <div class="shrink-0">
                <x-logo />
            </div>

            {{-- Same gap under logo before title (all pages) --}}
            <div @class([
                'flex min-h-0 flex-1 flex-col pt-8',
                'justify-center' => $contentVerticalAlign === 'center',
                'justify-start' => $contentVerticalAlign === 'start',
            ])>
                <div class="w-full max-w-[420px]">
                    @if ($eyebrow)
                        <p class="mb-3 text-[12px] font-bold uppercase tracking-[0.18em] text-zinc-900">{{ $eyebrow }}</p>
                    @endif

                    <h1 class="{{ $description ? 'mb-2' : 'mb-8' }} text-[36px] font-extrabold leading-[1.1] tracking-tight text-zinc-900">
                        {{ $heading }}
                    </h1>

                    @if ($description)
                        <p class="mb-8 text-[15px] text-zinc-500">{{ $description }}</p>
                    @endif

                    <x-flash class="mb-5" />

                    {{ $slot }}
                </div>
            </div>
        </div>

        {{-- Right: fixed height; does not scroll --}}
        <div class="hidden min-h-0 lg:flex lg:h-full lg:w-1/2 lg:flex-shrink-0 lg:flex-col lg:overflow-hidden">
            @include('partials.auth-split-visual')
        </div>
    </div>
</x-layouts.auth>
