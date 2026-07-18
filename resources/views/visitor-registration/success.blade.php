<x-layouts.public :title="'Registration Successful · '.($registration->church->name ?? 'Registration Successful')">
    <div class="flex flex-1 flex-col justify-center py-8">
        <x-ui.card class="overflow-hidden">
            <div class="bg-gradient-to-br from-emerald-500 to-teal-600 px-6 py-10 text-center text-white sm:px-10">
                <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-white/20 ring-1 ring-white/30">
                    <svg class="h-10 w-10" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M20 6 9 17l-5-5" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <h1 class="mt-6 text-3xl font-semibold tracking-tight">Registration successful</h1>
                <p class="mt-3 text-base leading-7 text-emerald-50">
                    Thank you, {{ $registration->visitor->first_name }}. We are glad to have you with us today.
                </p>
            </div>

            <div class="space-y-6 px-6 py-7 sm:px-10">
                <div class="rounded-3xl bg-slate-50 p-5">
                    <dl class="grid gap-4 text-sm">
                        <div class="flex items-start justify-between gap-4">
                            <dt class="text-slate-500">Church</dt>
                            <dd class="text-right font-medium text-slate-950">{{ $registration->church->name }}</dd>
                        </div>
                        <div class="flex items-start justify-between gap-4">
                            <dt class="text-slate-500">Service</dt>
                            <dd class="text-right font-medium text-slate-950">{{ $registration->churchService->name }}</dd>
                        </div>
                        <div class="flex items-start justify-between gap-4">
                            <dt class="text-slate-500">Date</dt>
                            <dd class="text-right font-medium text-slate-950">{{ $registration->registered_on->format('l, M j, Y') }}</dd>
                        </div>
                        <div class="flex items-start justify-between gap-4">
                            <dt class="text-slate-500">Time</dt>
                            <dd class="text-right font-medium text-slate-950">{{ $registration->created_at->format('g:i A') }}</dd>
                        </div>
                    </dl>
                </div>

                <p class="text-center text-sm leading-6 text-slate-500">
                    A member of our welcome team may reach out to you after the service.
                </p>
            </div>
        </x-ui.card>
    </div>
</x-layouts.public>
