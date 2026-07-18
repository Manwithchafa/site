<div class="space-y-6">
    <x-admin.loading-bar />

    @if (session('status'))
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700">
            {{ session('status') }}
        </div>
    @endif

    <div class="overflow-hidden rounded-[1.75rem] bg-[#08133f] p-6 text-white shadow-2xl shadow-[#08133f]/20">
        <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.24em] text-[#f5b82e]">Visitor database</p>
                <h2 class="mt-3 text-2xl font-extrabold tracking-tight sm:text-3xl">Manage members and visitors</h2>
                <p class="mt-2 max-w-2xl text-sm leading-6 text-white/65">Filter records, update profiles, remove duplicates, and export a spreadsheet of the exact list currently on screen.</p>
            </div>

            <a
                href="{{ route('admin.visitors.export', array_filter([
                    'q' => $search,
                    'sex' => $sex,
                    'membership' => $membership,
                    'bornAgain' => $bornAgain,
                    'registered' => $registered,
                ], fn ($value) => $value !== null && $value !== '')) }}"
                class="inline-flex items-center justify-center rounded-2xl bg-white px-5 py-3 text-sm font-extrabold text-[#162b75] shadow-lg shadow-black/10 transition hover:bg-[#f5b82e]"
            >
                Download spreadsheet
            </a>
        </div>
    </div>

    <x-admin.card class="p-5">
        <div class="grid gap-4 lg:grid-cols-12">
            <div class="lg:col-span-4">
                <label class="text-sm font-semibold text-slate-700">Search</label>
                <input wire:model.live.debounce.350ms="search" type="search" placeholder="Name, phone, email, bus stop..." class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none transition focus:border-[#162b75] focus:ring-4 focus:ring-[#162b75]/10">
            </div>

            <div class="lg:col-span-2">
                <label class="text-sm font-semibold text-slate-700">Gender</label>
                <select wire:model.live="sex" class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none focus:border-[#162b75] focus:ring-4 focus:ring-[#162b75]/10">
                    <option value="">All</option>
                    <option value="female">Female</option>
                    <option value="male">Male</option>
                </select>
            </div>

            <div class="lg:col-span-2">
                <label class="text-sm font-semibold text-slate-700">Membership</label>
                <select wire:model.live="membership" class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none focus:border-[#162b75] focus:ring-4 focus:ring-[#162b75]/10">
                    <option value="">All</option>
                    <option value="yes">Interested</option>
                    <option value="no">Not selected</option>
                </select>
            </div>

            <div class="lg:col-span-2">
                <label class="text-sm font-semibold text-slate-700">Born Again</label>
                <select wire:model.live="bornAgain" class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none focus:border-[#162b75] focus:ring-4 focus:ring-[#162b75]/10">
                    <option value="">All</option>
                    <option value="yes">Yes</option>
                    <option value="no">No</option>
                </select>
            </div>

            <div class="lg:col-span-2">
                <label class="text-sm font-semibold text-slate-700">Registered</label>
                <select wire:model.live="registered" class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none focus:border-[#162b75] focus:ring-4 focus:ring-[#162b75]/10">
                    <option value="">Any time</option>
                    <option value="today">Today</option>
                    <option value="week">This week</option>
                    <option value="month">This month</option>
                </select>
            </div>
        </div>

        <div class="mt-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <p class="text-sm font-semibold text-slate-500">{{ $visitors->total() }} visitor{{ $visitors->total() === 1 ? '' : 's' }} found</p>
            <div class="flex flex-col gap-2 sm:flex-row">
                <a
                    href="{{ route('admin.visitors.export', array_filter([
                        'q' => $search,
                        'gender' => $gender,
                        'membership' => $membership,
                        'bornAgain' => $bornAgain,
                        'registered' => $registered,
                    ], fn ($value) => $value !== null && $value !== '')) }}"
                    class="rounded-xl bg-[#162b75] px-4 py-2 text-center text-sm font-bold text-white hover:bg-[#10215d]"
                >
                    Export filtered CSV
                </a>
                <button wire:click="clearFilters" type="button" class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-bold text-slate-700 hover:bg-slate-50">Clear filters</button>
            </div>
        </div>
    </x-admin.card>

    @if ($visitors->isEmpty())
        <x-admin.empty-state title="No visitors match your filters" description="Adjust search terms or filters to see visitor records." />
    @else
        <x-admin.table.wrapper>
            <thead class="bg-[#f8f9fc]">
                <tr>
                    <x-admin.table.th>Visitor</x-admin.table.th>
                    <x-admin.table.th>Contact</x-admin.table.th>
                    <x-admin.table.th>Profile</x-admin.table.th>
                    <x-admin.table.th>Registrations</x-admin.table.th>
                    <x-admin.table.th>Status</x-admin.table.th>
                    <x-admin.table.th>Actions</x-admin.table.th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 bg-white">
                @foreach ($visitors as $visitor)
                    <tr class="transition hover:bg-[#f8f9fc]">
                        <x-admin.table.td>
                            <div class="flex items-center gap-3">
                                <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-[#eef2ff] text-sm font-extrabold text-[#162b75]">
                                    {{ str($visitor->first_name)->substr(0, 1) }}{{ str($visitor->last_name)->substr(0, 1) }}
                                </div>
                                <div>
                                    <p class="font-semibold text-slate-950">{{ $visitor->full_name }}</p>
                                    <p class="text-xs text-slate-500">{{ $visitor->church->name }}</p>
                                </div>
                            </div>
                        </x-admin.table.td>
                        <x-admin.table.td>
                            <p>{{ $visitor->phone }}</p>
                            <p class="text-xs text-slate-400">{{ $visitor->email ?: 'No email' }}</p>
                        </x-admin.table.td>
                        <x-admin.table.td>
                            <div class="flex flex-wrap gap-2">
                                <x-admin.badge>{{ ucfirst($visitor->sex) }}</x-admin.badge>
                                @if ($visitor->wants_membership)
                                    <x-admin.badge tone="emerald">Membership</x-admin.badge>
                                @endif
                                @if ($visitor->born_again)
                                    <x-admin.badge tone="blue">Born again</x-admin.badge>
                                @endif
                            </div>
                        </x-admin.table.td>
                        <x-admin.table.td>
                            <p class="font-medium text-slate-950">{{ $visitor->registrations_count }}</p>
                            <p class="text-xs text-slate-400">{{ optional($visitor->registrations->sortByDesc('created_at')->first())->created_at?->diffForHumans() ?? 'No visit yet' }}</p>
                        </x-admin.table.td>
                        <x-admin.table.td>
                            <x-admin.badge tone="violet">{{ ucfirst($visitor->status) }}</x-admin.badge>
                        </x-admin.table.td>
                        <x-admin.table.td>
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.visitors.show', $visitor) }}" class="rounded-xl border border-slate-200 px-3 py-2 text-xs font-bold text-slate-700 hover:bg-white">View</a>
                                <button wire:click="editVisitor({{ $visitor->id }})" type="button" class="rounded-xl bg-[#162b75] px-3 py-2 text-xs font-bold text-white hover:bg-[#10215d]">Edit</button>
                                <button
                                    wire:click="deleteVisitor({{ $visitor->id }})"
                                    wire:confirm="Delete {{ $visitor->full_name }}? This will remove their registrations, notes, and assignments."
                                    type="button"
                                    class="rounded-xl border border-rose-200 px-3 py-2 text-xs font-bold text-rose-600 hover:bg-rose-50"
                                >
                                    Delete
                                </button>
                            </div>
                        </x-admin.table.td>
                    </tr>
                @endforeach
            </tbody>
        </x-admin.table.wrapper>

        <div>
            {{ $visitors->links() }}
        </div>
    @endif

    @if ($editingVisitorId)
        <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-950/40 px-4 py-6 backdrop-blur-sm sm:py-10">
            <div class="mx-auto max-w-3xl rounded-[1.5rem] border border-slate-200 bg-white shadow-2xl">
                <div class="flex items-start justify-between gap-4 border-b border-slate-100 px-5 py-4 sm:px-6">
                    <div>
                        <h2 class="text-lg font-extrabold text-[#162b75]">Edit visitor</h2>
                        <p class="mt-1 text-sm text-slate-500">Update member profile and follow-up status.</p>
                    </div>
                    <button wire:click="cancelEdit" type="button" class="rounded-xl border border-slate-200 px-3 py-2 text-sm font-bold text-slate-500 hover:bg-slate-50">Close</button>
                </div>

                <form wire:submit="updateVisitor" class="space-y-6 px-5 py-5 sm:px-6">
                    <section class="grid gap-4 sm:grid-cols-2">
                        <x-form.input label="First Name" name="edit_first_name" wire:model.blur="edit_first_name" required />
                        <x-form.input label="Last Name" name="edit_last_name" wire:model.blur="edit_last_name" required />
                        <x-form.select label="Gender" name="edit_sex" wire:model.blur="edit_sex" required>
                            <option value="">Select gender</option>
                            <option value="female">Female</option>
                            <option value="male">Male</option>
                        </x-form.select>
                        <x-form.input label="Date of Birth" name="edit_date_of_birth" type="date" wire:model.blur="edit_date_of_birth" />
                        <x-form.input label="Phone" name="edit_phone" type="tel" wire:model.blur="edit_phone" required />
                        <x-form.input label="Email" name="edit_email" type="email" wire:model.blur="edit_email" />
                    </section>

                    <section class="space-y-4">
                        <x-form.textarea label="Residential Address" name="edit_residential_address" wire:model.blur="edit_residential_address" rows="3" />
                        <div class="grid gap-4 sm:grid-cols-2">
                            <x-form.input label="Nearest Bus Stop" name="edit_nearest_bus_stop" wire:model.blur="edit_nearest_bus_stop" />
                            <x-form.input label="Occupation" name="edit_occupation" wire:model.blur="edit_occupation" />
                            <x-form.input label="Invited By" name="edit_invited_by" wire:model.blur="edit_invited_by" />
                            <x-form.select label="Status" name="edit_status" wire:model.blur="edit_status" required>
                                <option value="new">New</option>
                                <option value="contacted">Contacted</option>
                                <option value="assigned">Assigned</option>
                                <option value="member">Member</option>
                                <option value="inactive">Inactive</option>
                            </x-form.select>
                        </div>
                    </section>

                    <section class="grid gap-3 sm:grid-cols-2">
                        <x-form.checkbox-card
                            name="edit_born_again"
                            wire:model="edit_born_again"
                            title="Born again"
                            description="Visitor has received Jesus Christ as Lord and Saviour."
                        />
                        <x-form.checkbox-card
                            name="edit_wants_membership"
                            wire:model="edit_wants_membership"
                            title="Wants membership"
                            description="Visitor wants to become a church member."
                        />
                    </section>

                    <div class="flex flex-col-reverse gap-3 border-t border-slate-100 pt-5 sm:flex-row sm:justify-end">
                        <button wire:click="cancelEdit" type="button" class="rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-bold text-slate-600 hover:bg-slate-50">Cancel</button>
                        <button type="submit" class="rounded-xl bg-[#162b75] px-5 py-3 text-sm font-bold text-white shadow-lg shadow-[#162b75]/20 hover:bg-[#10215d]">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
