{{-- RIGHT: Visual presentation panel (shared across auth pages); parent controls width / visibility --}}
<div class="h-full min-h-0 w-full p-4 xl:p-6">
    <div class="relative h-full w-full overflow-hidden rounded-[32px]"
         style="background: linear-gradient(180deg, #EAF0FB 0%, #DCE4F6 45%, #C7D4F0 100%);">

        {{-- Total Revenue card (top-left) --}}
        <div class="absolute left-8 top-8 z-20 w-[300px] rounded-2xl border border-white/70 p-5 shadow-[0_8px_32px_rgba(31,38,135,0.08)] xl:left-12 xl:top-12"
             style="background: rgba(255,255,255,0.55); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);">
            <p class="mb-1 text-[14px] font-medium text-zinc-700">{{ __('Total revenue') }}</p>
            <div class="mb-5 flex items-baseline gap-2">
                <h3 class="text-[26px] font-bold text-zinc-900">$354,320</h3>
                <span class="inline-flex items-center gap-0.5 text-[12px] font-semibold text-emerald-600">
                    <svg width="11" height="11" viewBox="0 0 20 20" fill="currentColor"><path d="M10 3l7 7H3l7-7z"/></svg>
                    2.5%
                </span>
            </div>

            <div class="flex items-center gap-6">
                <svg width="110" height="110" viewBox="0 0 100 100" aria-hidden="true">
                    <path d="M 50 50 L 50 0 A 50 50 0 0 1 95 30 Z" fill="#BFD4F2"/>
                    <path d="M 50 50 L 95 30 A 50 50 0 0 1 50 100 Z" fill="#5B7FE8"/>
                    <path d="M 50 50 L 50 100 A 50 50 0 0 1 10 78 Z" fill="#A8C0EC"/>
                    <path d="M 50 50 L 10 78 A 50 50 0 0 1 50 0 Z" fill="#0F172A"/>
                </svg>

                <div class="space-y-2 text-[13px] font-medium text-zinc-700">
                    <div class="flex items-center gap-2"><span class="inline-block" style="width:7px;height:7px;border-radius:9999px;background:#BFD4F2"></span>{{ __('May') }}</div>
                    <div class="flex items-center gap-2"><span class="inline-block" style="width:7px;height:7px;border-radius:9999px;background:#5B7FE8"></span>{{ __('June') }}</div>
                    <div class="flex items-center gap-2"><span class="inline-block" style="width:7px;height:7px;border-radius:9999px;background:#A8C0EC"></span>{{ __('July') }}</div>
                    <div class="flex items-center gap-2"><span class="inline-block" style="width:7px;height:7px;border-radius:9999px;background:#0F172A"></span>{{ __('August') }}</div>
                </div>
            </div>
        </div>

        {{-- Notification cards (top-right) --}}
        <div class="absolute right-8 top-8 z-20 w-[250px] space-y-2.5 xl:right-12 xl:top-12">
            <div class="flex items-center gap-3 rounded-xl border border-white/80 p-2.5 shadow-[0_4px_16px_rgba(31,38,135,0.06)]"
                 style="background: rgba(255,255,255,0.78); backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px);">
                <span class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-full text-[12px] font-bold text-white shadow-inner"
                      style="background: linear-gradient(135deg, #F472B6 0%, #BE185D 100%);"
                      aria-label="Anna Peterson">AP</span>
                <div class="min-w-0 flex-1">
                    <p class="truncate text-[12px] font-bold leading-tight text-zinc-900">{{ __('Anna Peterson') }}</p>
                    <p class="mt-0.5 flex items-center gap-1 truncate text-[10px] text-zinc-500">
                        <svg width="10" height="10" viewBox="0 0 20 20" fill="currentColor" style="flex-shrink:0;color:#a1a1aa">
                            <path fill-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.84 8.84 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z" clip-rule="evenodd"/>
                        </svg>
                        <span class="truncate">{{ __('Sent a message to') }} <span class="font-semibold text-zinc-700">{{ __('UXTank Inc.') }}</span></span>
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-3 rounded-xl border border-white/80 p-2.5 shadow-[0_4px_16px_rgba(31,38,135,0.06)]"
                 style="background: rgba(255,255,255,0.78); backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px);">
                <span class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-full text-[12px] font-bold text-white shadow-inner"
                      style="background: linear-gradient(135deg, #60A5FA 0%, #1E40AF 100%);"
                      aria-label="Chris Meadow">CM</span>
                <div class="min-w-0 flex-1">
                    <p class="truncate text-[12px] font-bold leading-tight text-zinc-900">{{ __('Chris Meadow') }}</p>
                    <p class="mt-0.5 flex items-center gap-1 truncate text-[10px] text-zinc-500">
                        <svg width="10" height="10" viewBox="0 0 20 20" fill="currentColor" style="flex-shrink:0;color:#3b82f6">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span class="truncate">{{ __('Accepted a new project') }} <span class="font-semibold text-zinc-700">{{ __('Cascade') }}</span></span>
                    </p>
                </div>
            </div>
        </div>

        {{-- 3D blue glass blocks (bottom) --}}
        <div class="absolute bottom-0 left-0 right-0" style="height:62%;overflow:hidden;">
            <svg viewBox="0 0 600 400" preserveAspectRatio="xMidYMax slice" style="width:100%;height:100%;display:block;" aria-hidden="true">
                <defs>
                    <linearGradient id="gA" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" stop-color="#FFFFFF" stop-opacity="0.65"/>
                        <stop offset="100%" stop-color="#7B9CE8" stop-opacity="0.4"/>
                    </linearGradient>
                    <linearGradient id="gB" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" stop-color="#A8C0F0" stop-opacity="0.7"/>
                        <stop offset="100%" stop-color="#3B5FCC" stop-opacity="0.6"/>
                    </linearGradient>
                    <linearGradient id="gC" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" stop-color="#5577D9" stop-opacity="0.7"/>
                        <stop offset="100%" stop-color="#1E3A8A" stop-opacity="0.85"/>
                    </linearGradient>
                    <linearGradient id="gD" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" stop-color="#3B5FCC" stop-opacity="0.85"/>
                        <stop offset="100%" stop-color="#0F1E5C" stop-opacity="0.95"/>
                    </linearGradient>
                    <linearGradient id="gE" x1="0%" y1="0%" x2="0%" y2="100%">
                        <stop offset="0%" stop-color="#E8EEFB" stop-opacity="0.55"/>
                        <stop offset="100%" stop-color="#7B9CE8" stop-opacity="0.3"/>
                    </linearGradient>
                </defs>

                <g>
                    <polygon points="220,100 260,90 260,400 220,400" fill="url(#gA)" stroke="#FFFFFF" stroke-width="0.5" stroke-opacity="0.5"/>
                    <polygon points="260,90 300,100 300,400 260,400" fill="url(#gE)" stroke="#FFFFFF" stroke-width="0.5" stroke-opacity="0.5"/>
                </g>
                <g>
                    <polygon points="290,140 340,125 340,400 290,400" fill="url(#gB)" stroke="#FFFFFF" stroke-width="0.5" stroke-opacity="0.5"/>
                    <polygon points="340,125 390,140 390,400 340,400" fill="url(#gA)" stroke="#FFFFFF" stroke-width="0.5" stroke-opacity="0.5"/>
                </g>
                <g>
                    <polygon points="365,180 420,160 420,400 365,400" fill="url(#gC)" stroke="#FFFFFF" stroke-width="0.5" stroke-opacity="0.5"/>
                    <polygon points="420,160 470,180 470,400 420,400" fill="url(#gB)" stroke="#FFFFFF" stroke-width="0.5" stroke-opacity="0.5"/>
                </g>
                <g>
                    <polygon points="440,220 510,195 510,400 440,400" fill="url(#gD)" stroke="#FFFFFF" stroke-width="0.5" stroke-opacity="0.5"/>
                    <polygon points="510,195 580,220 580,400 510,400" fill="url(#gC)" stroke="#FFFFFF" stroke-width="0.5" stroke-opacity="0.5"/>
                </g>

                <polygon points="220,100 260,90 260,140 220,150" fill="#FFFFFF" fill-opacity="0.28"/>
                <polygon points="290,140 340,125 340,175 290,190" fill="#FFFFFF" fill-opacity="0.22"/>
            </svg>
        </div>

        <div class="pointer-events-none absolute inset-0" style="opacity:0.35;background-image:radial-gradient(circle at 30% 15%, rgba(255,255,255,0.55), transparent 50%);"></div>
    </div>
</div>
