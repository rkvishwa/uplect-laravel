{{-- RIGHT: Visual presentation panel (shared across auth pages); parent controls width / visibility --}}
<div class="h-full min-h-0 w-full p-4 xl:p-6">
    <div class="relative h-full w-full overflow-hidden rounded-[32px]"
         style="background: linear-gradient(180deg, #EAF0FB 0%, #DCE4F6 45%, #C7D4F0 100%);">


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
