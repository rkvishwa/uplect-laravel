<x-layouts.app :header="__('Enroll')" :title="__('Enroll')">
    <x-slot:sidebarNav>
        <x-student.sidebar active="enrollments" />
    </x-slot:sidebarNav>

    <p class="font-serif text-xl">{{ $course->title }}</p>
    <p class="mt-2 text-sm text-zinc-600">{{ __('Choose how you want to pay.') }}</p>

    <div class="mt-8 grid gap-8 lg:grid-cols-2">
        <form method="POST" action="{{ route('student.enroll.payhere') }}" class="rounded-2xl border border-zinc-200 bg-white/90 p-6 dark:border-zinc-800 dark:bg-zinc-900/70">
            @csrf
            <input type="hidden" name="course_id" value="{{ $course->id }}" />
            <p class="font-semibold">{{ __('Pay with PayHere') }}</p>
            <p class="mt-2 text-sm text-zinc-600">{{ __('You will be redirected to PayHere to complete payment.') }}</p>
            <button type="submit" class="mt-4 rounded-xl bg-brand-600 px-5 py-2.5 text-sm font-semibold text-white">{{ __('Continue') }}</button>
        </form>
        <form method="POST" action="{{ route('student.enroll.bank') }}" enctype="multipart/form-data" class="rounded-2xl border border-zinc-200 bg-white/90 p-6 dark:border-zinc-800 dark:bg-zinc-900/70">
            @csrf
            <input type="hidden" name="course_id" value="{{ $course->id }}" />
            <p class="font-semibold">{{ __('Bank transfer') }}</p>
            <p class="mt-2 text-sm text-zinc-600">{{ __('Upload your slip. An admin will approve or decline.') }}</p>
            <input type="file" name="slip" required class="mt-4 block w-full text-sm" />
            <button type="submit" class="mt-4 rounded-xl border border-zinc-200 px-5 py-2.5 text-sm font-semibold dark:border-zinc-700">{{ __('Submit slip') }}</button>
        </form>
    </div>
</x-layouts.app>
