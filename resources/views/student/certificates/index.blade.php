<x-layouts.app :header="__('Certificates')" :title="__('Certificates')">
    <x-slot:sidebarNav>
        <x-student.sidebar active="certificates" />
    </x-slot:sidebarNav>

    <div class="space-y-4">
        @forelse ($certificates as $cert)
            <div class="flex flex-wrap items-center justify-between gap-4 rounded-2xl border border-zinc-200 bg-white/90 p-4 dark:border-zinc-800 dark:bg-zinc-900/70">
                <div>
                    <p class="font-semibold">{{ $cert->course->title }}</p>
                    <p class="text-xs text-zinc-500">{{ $cert->certificate_number }} · {{ $cert->issued_at->format('Y-m-d') }}</p>
                </div>
                <a href="{{ route('student.certificates.download', $cert) }}" class="rounded-xl bg-brand-600 px-4 py-2 text-sm font-semibold text-white">{{ __('Download PDF') }}</a>
            </div>
        @empty
            <p class="text-sm text-zinc-500">{{ __('No certificates yet.') }}</p>
        @endforelse
    </div>
    <div class="mt-6">{{ $certificates->links() }}</div>
</x-layouts.app>
