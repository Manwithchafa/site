<div class="grid gap-6 lg:grid-cols-[0.9fr_1.25fr] lg:items-start">
    <aside class="rounded-[1.75rem] border border-slate-200 bg-white p-6 shadow-xl shadow-slate-200/70 sm:p-8 lg:sticky lg:top-6">
        <div class="flex justify-center lg:justify-start">
            <div class="flex h-24 w-full max-w-[240px] items-center justify-center rounded-2xl border border-slate-100 bg-white p-3 shadow-sm sm:h-28 sm:max-w-[280px]">
                <img src="{{ asset('images/CHlogo.png') }}" alt="Christ Embassy" class="max-h-full max-w-full object-contain">
            </div>
        </div>

        <div class="mt-8">
            <p class="text-xs font-bold uppercase tracking-[0.24em] text-[#d29a18]">Visitor registration</p>
            <h1 class="mt-3 text-3xl font-extrabold leading-tight tracking-tight text-[#162b75] sm:text-4xl">
                Welcome to {{ $qrCode->church->name }}
            </h1>
            <p class="mt-4 text-sm leading-7 text-slate-600 sm:text-base">
                We are delighted to receive you. Please share a few details so our welcome team can stay connected with you after service.
            </p>
        </div>

        <div class="mt-8 rounded-2xl border border-[#162b75]/10 bg-[#f8f9fc] p-5">
            <p class="text-xs font-bold uppercase tracking-[0.2em] text-slate-500">Service</p>
            <p class="mt-2 text-base font-bold text-slate-950">{{ $qrCode->churchService->name }}</p>
            <p class="mt-1 text-sm text-slate-500">{{ now()->format('F j, Y') }}</p>
        </div>

        <div class="mt-6 space-y-3 text-sm font-medium text-slate-600">
            <div class="flex items-center gap-3"><span class="h-2 w-2 rounded-full bg-[#162b75]"></span>Warm welcome from our team</div>
            <div class="flex items-center gap-3"><span class="h-2 w-2 rounded-full bg-[#d29a18]"></span>Prayer and pastoral follow-up</div>
            <div class="flex items-center gap-3"><span class="h-2 w-2 rounded-full bg-[#d9232e]"></span>Guidance for your next step</div>
        </div>
    </aside>

    <x-ui.card class="overflow-hidden">
        <form wire:submit="submit" class="space-y-6 px-5 py-6 sm:px-8 sm:py-8">
            <section class="space-y-4 rounded-2xl border border-slate-100 bg-slate-50/70 p-5 sm:p-6">
                <div>
                    <h2 class="text-base font-bold text-slate-950">Personal information</h2>
                    <p class="mt-1 text-sm text-slate-500">Tell us who you are.</p>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <x-form.input label="Name" name="first_name" wire:model="first_name" required autocomplete="given-name" />
                    <x-form.input label="Last Name" name="last_name" wire:model="last_name" required autocomplete="family-name" />
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <x-form.select label="Sex" name="sex" wire:model="sex">
                        <option value="">Select sex</option>
                        <option value="female">Female</option>
                        <option value="male">Male</option>
                    </x-form.select>
                    <x-form.input label="Age" name="age" wire:model="age" type="number" min="1" max="120" />
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <x-form.input label="Date of Birth" name="date_of_birth" wire:model="date_of_birth" type="date" />
                    <x-form.input label="Marital Status" name="marital_status" wire:model="marital_status" placeholder="Single, Married, Divorced..." />
                </div>

                <x-form.input label="Wedding Anniversary" name="wedding_anniversary" wire:model="wedding_anniversary" type="date" />
            </section>

            <section class="space-y-4 rounded-2xl border border-slate-100 bg-slate-50/70 p-5 sm:p-6">
                <div>
                    <h2 class="text-base font-bold text-slate-950">Contact details</h2>
                    <p class="mt-1 text-sm text-slate-500">We will only use this for welcome and follow-up.</p>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <x-form.input label="Phone Number" name="phone" wire:model="phone" type="tel" required autocomplete="tel" placeholder="+234..." />
                    <x-form.input label="Email" name="email" wire:model="email" type="email" autocomplete="email" placeholder="you@example.com" />
                </div>

                <x-form.input label="Which City Do You Reside" name="city" wire:model="city" placeholder="e.g. Lagos" />

                <x-form.textarea label="Residential Address" name="residential_address" wire:model="residential_address" rows="3" placeholder="House number, street, area" />
                <x-form.textarea label="Business/Office/School Address" name="business_address" wire:model="business_address" rows="3" placeholder="Company, school, office address" />

                <div class="grid gap-4 sm:grid-cols-2">
                    <x-form.input label="Nearest Bus Stop" name="nearest_bus_stop" wire:model="nearest_bus_stop" />
                    <x-form.input label="Occupation" name="occupation" wire:model="occupation" />
                </div>
            </section>

            <section class="space-y-4 rounded-2xl border border-slate-100 bg-slate-50/70 p-5 sm:p-6">
                <div>
                    <h2 class="text-base font-bold text-slate-950">Church connection</h2>
                    <p class="mt-1 text-sm text-slate-500">Help us support your next step.</p>
                </div>

                <x-form.input label="Who Invited You?" name="invited_by" wire:model="invited_by" placeholder="Please give a specific name" />
                <div class="grid gap-4 sm:grid-cols-2">
                    <x-form.input label="Inviter Phone Number" name="invited_by_phone" wire:model="invited_by_phone" type="tel" placeholder="+234..." />
                    <x-form.input label="Inviter Name" name="invited_by_name" wire:model="invited_by_name" placeholder="Name of the person who invited you" />
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <x-form.select label="Would you like to be a member?" name="wants_membership" wire:model="wants_membership">
                        <option value="0">No</option>
                        <option value="1">Yes</option>
                    </x-form.select>

                    <x-form.select label="Are you born again?" name="born_again" wire:model="born_again">
                        <option value="0">No</option>
                        <option value="1">Yes</option>
                    </x-form.select>
                </div>

                <div id="born-again-when-field" class="{{ $this->born_again === '1' ? '' : 'hidden' }}">
                    <x-form.input label="If yes, when were you born again?" name="born_again_when" wire:model="born_again_when" type="date" />
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <x-form.select label="Do you want counseling?" name="wants_counsel" wire:model="wants_counsel">
                        <option value="0">No</option>
                        <option value="1">Yes</option>
                    </x-form.select>

                    <x-form.input label="When can we visit you?" name="preferred_visit_date" wire:model="preferred_visit_date" type="date" />
                </div>

                <x-form.select label="Are you baptized?" name="is_baptized" wire:model="is_baptized">
                    <option value="0">No</option>
                    <option value="1">Yes</option>
                </x-form.select>

                <x-form.textarea
                    label="Prayer Request"
                    name="prayer_request"
                    wire:model="prayer_request"
                    rows="5"
                    placeholder="Share anything you would like us to pray about."
                />
            </section>

            <div class="space-y-4 border-t border-slate-100 pt-6">
                <x-ui.button type="submit">
                    <span>Complete my registration</span>
                </x-ui.button>

                <p class="text-center text-xs leading-5 text-slate-500">
                    By submitting this form, you agree that the church may contact you for welcome and follow-up purposes.
                </p>
            </div>
        </form>
    </x-ui.card>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const bornAgain = document.querySelector('select[name="born_again"]');
        const bornAgainWhen = document.getElementById('born-again-when-field');

        if (! bornAgain || ! bornAgainWhen) {
            return;
        }

        const syncBornAgainWhen = () => {
            bornAgainWhen.classList.toggle('hidden', bornAgain.value !== '1');
        };

        bornAgain.addEventListener('change', syncBornAgainWhen);
        syncBornAgainWhen();
    });
</script>

