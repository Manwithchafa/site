<div class="space-y-6">
    <x-admin.loading-bar />

    <div class="grid gap-6 xl:grid-cols-3">
        <x-admin.card class="p-6 xl:col-span-1">
            <div class="flex items-center gap-4">
                <div class="flex h-16 w-16 items-center justify-center rounded-3xl bg-slate-950 text-lg font-semibold text-white">
                    {{ str($visitor->first_name)->substr(0, 1) }}{{ str($visitor->last_name)->substr(0, 1) }}
                </div>
                <div>
                    <h2 class="text-xl font-semibold text-slate-950">{{ $visitor->full_name }}</h2>
                    <p class="text-sm text-slate-500">{{ ucfirst($visitor->gender) }} · {{ $visitor->phone }}</p>
                </div>
            </div>

            <dl class="mt-6 space-y-4 text-sm">
                <div><dt class="text-slate-500">Email</dt><dd class="mt-1 font-medium text-slate-950">{{ $visitor->email ?: 'Not provided' }}</dd></div>
                <div><dt class="text-slate-500">Address</dt><dd class="mt-1 font-medium text-slate-950">{{ $visitor->address ?: 'Not provided' }}</dd></div>
                <div><dt class="text-slate-500">Nearest Bus Stop</dt><dd class="mt-1 font-medium text-slate-950">{{ $visitor->nearest_bus_stop ?: 'Not provided' }}</dd></div>
                <div><dt class="text-slate-500">Occupation</dt><dd class="mt-1 font-medium text-slate-950">{{ $visitor->occupation ?: 'Not provided' }}</dd></div>
                <div><dt class="text-slate-500">Invited By</dt><dd class="mt-1 font-medium text-slate-950">{{ $visitor->invited_by ?: 'Not provided' }}</dd></div>
            </dl>

            <div class="mt-6 flex flex-wrap gap-2">
                <x-admin.badge tone="blue">{{ $visitor->registrations_count }} registration{{ $visitor->registrations_count === 1 ? '' : 's' }}</x-admin.badge>
                @if ($visitor->born_again)<x-admin.badge tone="emerald">Born again</x-admin.badge>@endif
                @if ($visitor->wants_membership)<x-admin.badge tone="violet">Wants membership</x-admin.badge>@endif
            </div>
        </x-admin.card>

        <x-admin.card class="p-6 xl:col-span-2">
<x-admin.section-header title="First Timer Timeline" description="Registrations, assignments, and notes in chronological order." />

            <div class="mt-6 space-y-5">
                @forelse ($timeline as $item)
                    <div class="relative flex gap-4">
                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-2xl bg-slate-100 text-xs font-semibold text-slate-700">
                            {{ strtoupper(substr($item['type'], 0, 1)) }}
                        </div>
                        <div class="min-w-0 flex-1 rounded-2xl border border-slate-100 p-4">
                            <div class="flex flex-wrap items-start justify-between gap-2">
                                <p class="font-semibold text-slate-950">{{ $item['title'] }}</p>
                                <span class="text-xs text-slate-400">{{ $item['timestamp']->format('M j, Y · g:i A') }}</span>
                            </div>
                            <p class="mt-1 text-sm leading-6 text-slate-500">{{ $item['description'] }}</p>
                        </div>
                    </div>
                @empty
                    <x-admin.empty-state title="No timeline yet" description="Activity will appear when this visitor registers, receives assignments, or gets notes." />
                @endforelse
            </div>
        </x-admin.card>
    </div>

    <div class="grid gap-6 xl:grid-cols-3">
        <x-admin.card class="p-6">
            <x-admin.section-header title="Assignments" description="Follow-up ownership and status." />
            <div class="mt-5 space-y-3">
                @forelse ($visitor->assignments->sortByDesc('assigned_at') as $assignment)
                    <div class="rounded-2xl border border-slate-100 p-4">
                        <div class="flex items-center justify-between gap-3">
                            <p class="text-sm font-semibold text-slate-950">{{ $assignment->assignee?->name ?? 'Unassigned' }}</p>
                            <x-admin.badge tone="blue">{{ ucfirst($assignment->status) }}</x-admin.badge>
                        </div>
                        <p class="mt-2 text-sm text-slate-500">{{ $assignment->notes ?: 'No assignment note.' }}</p>
                    </div>
                @empty
                    <x-admin.empty-state title="No assignments" description="Follow-up assignments will appear here." />
                @endforelse
            </div>
        </x-admin.card>

        <x-admin.card class="p-6">
            <x-admin.section-header title="Notes" description="Care, admin, and follow-up notes." />
            <div class="mt-5 space-y-3">
                @forelse ($visitor->notes->sortByDesc('created_at') as $note)
                    <div class="rounded-2xl border border-slate-100 p-4">
                        <div class="flex items-center justify-between gap-3">
                            <p class="text-sm font-semibold text-slate-950">{{ ucfirst($note->type) }}</p>
                            <span class="text-xs text-slate-400">{{ $note->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="mt-2 text-sm leading-6 text-slate-600">{{ $note->body }}</p>
                    </div>
                @empty
                    <x-admin.empty-state title="No notes" description="Notes added by care teams will appear here." />
                @endforelse
            </div>
        </x-admin.card>

        <x-admin.card class="p-6">
            <x-admin.section-header title="Prayer Requests" description="Requests submitted during registrations." />
            <div class="mt-5 space-y-3">
                @forelse ($visitor->registrations->whereNotNull('prayer_request')->where('prayer_request', '!=', '') as $registration)
                    <div class="rounded-2xl border border-slate-100 p-4">
                        <p class="text-sm leading-6 text-slate-600">{{ $registration->prayer_request }}</p>
                        <p class="mt-2 text-xs text-slate-400">{{ $registration->created_at->format('M j, Y') }}</p>
                    </div>
                @empty
                    <x-admin.empty-state title="No prayer requests" description="This visitor has not submitted a prayer request." />
                @endforelse
            </div>
        </x-admin.card>
    </div>
</div>
