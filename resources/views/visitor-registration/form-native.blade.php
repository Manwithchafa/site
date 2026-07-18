<div class="grid gap-6 lg:grid-cols-[0.9fr_1.25fr] lg:items-start">
    <aside class="rounded-[1.75rem] border border-slate-200 bg-white p-6 shadow-xl shadow-slate-200/70 sm:p-8 lg:sticky lg:top-6">
        <div class="flex justify-center lg:justify-start">
            <div class="flex h-24 w-full max-w-[240px] items-center justify-center rounded-2xl border border-slate-100 bg-white p-3 shadow-sm sm:h-28 sm:max-w-[280px]">
                <img src="{{ asset('images/CHlogo.png') }}" alt="Christ Embassy" class="max-h-full max-w-full object-contain">
            </div>
        </div>

        <div class="mt-8">
            <p class="text-xs font-bold uppercase tracking-[0.24em] text-[#d29a18]">First Timer Registration</p>
            <h1 class="mt-3 text-3xl font-extrabold leading-tight tracking-tight text-[#162b75] sm:text-4xl">
                Welcome to {{ $qrCode->church->name }}
            </h1>
            <p class="mt-4 text-sm leading-7 text-slate-600 sm:text-base">
                We're delighted to have you worship with us today. Please complete this short First Timer Registration form. Your information helps our First Timers team welcome and stay connected with you.
            </p>
        </div>

        <div class="mt-8 rounded-2xl border border-[#162b75]/10 bg-[#f8f9fc] p-5">
            <p class="text-xs font-bold uppercase tracking-[0.2em] text-slate-500">Welcome</p>
            <p class="mt-2 text-base font-bold text-slate-950">A warm welcome from our church family</p>
            <p class="mt-1 text-sm text-slate-500">{{ now()->format('F j, Y') }}</p>
        </div>

        <div class="mt-6 space-y-3 text-sm font-medium text-slate-600">
            <div class="flex items-center gap-3"><span class="h-2 w-2 rounded-full bg-[#162b75]"></span>Warm welcome from our team</div>
            <div class="flex items-center gap-3"><span class="h-2 w-2 rounded-full bg-[#d29a18]"></span>Prayer and pastoral follow-up</div>
            <div class="flex items-center gap-3"><span class="h-2 w-2 rounded-full bg-[#d9232e]"></span>Guidance for your next step</div>
        </div>
    </aside>

    <x-ui.card class="overflow-hidden">
        <form method="POST" action="{{ route('visitor-registration.store', $qrCode->code) }}" class="space-y-6 px-5 py-6 sm:px-8 sm:py-8">
            @csrf

            <section class="space-y-4 rounded-2xl border border-slate-100 bg-slate-50/70 p-5 sm:p-6">
                <div>
                    <h2 class="text-base font-bold text-slate-950">Personal information</h2>
                    <p class="mt-1 text-sm text-slate-500">Tell us who you are.</p>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <x-form.input label="Name" name="first_name" required autocomplete="given-name" />
                    <x-form.input label="Last Name" name="last_name" required autocomplete="family-name" />
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <x-form.select label="Sex" name="sex">
                        <option value="">Select sex</option>
                        <option value="female">Female</option>
                        <option value="male">Male</option>
                    </x-form.select>
                    <x-form.input label="Age" name="age" type="number" min="1" max="120" />
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <x-form.input label="Date of Birth" name="date_of_birth" type="date" />
                    <x-form.input label="Marital Status" name="marital_status" placeholder="Single, Married, Divorced..." />
                </div>

                <x-form.input label="Wedding Anniversary" name="wedding_anniversary" type="date" />
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
