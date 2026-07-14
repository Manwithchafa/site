<x-layouts.public title="Create Admin Account">
    <div class="flex flex-1 items-center justify-center py-8">
        <div class="w-full max-w-md rounded-[1.75rem] border border-slate-200 bg-white p-6 shadow-xl shadow-slate-200/70 sm:p-8">
            <div class="flex justify-center">
                <div class="flex h-24 w-full max-w-[240px] items-center justify-center rounded-2xl border border-slate-100 bg-white p-3 shadow-sm">
                    <img src="{{ asset('images/CHlogo.png') }}" alt="Christ Embassy" class="max-h-full max-w-full object-contain">
                </div>
            </div>

            <div class="mt-8 text-center">
                <p class="text-xs font-bold uppercase tracking-[0.24em] text-[#d29a18]">Admin setup</p>
                <h1 class="mt-3 text-2xl font-extrabold tracking-tight text-[#162b75]">Create your account</h1>
                <p class="mt-2 text-sm leading-6 text-slate-500">Use this account to access the visitor management dashboard.</p>
            </div>

            <form method="POST" action="{{ route('register.store') }}" class="mt-8 space-y-5">
                @csrf

                <x-form.input label="Name" name="name" value="{{ old('name') }}" required autocomplete="name" />
                <x-form.input label="Email" name="email" type="email" value="{{ old('email') }}" required autocomplete="email" />
                <x-form.input label="Password" name="password" type="password" required autocomplete="new-password" />
                <x-form.input label="Confirm Password" name="password_confirmation" type="password" required autocomplete="new-password" />

                <x-ui.button type="submit">Create account</x-ui.button>
            </form>

            <p class="mt-6 text-center text-sm text-slate-500">
                Already have an account?
                <a href="{{ route('login') }}" class="font-bold text-[#162b75] hover:text-[#10215d]">Sign in</a>
            </p>
        </div>
    </div>
</x-layouts.public>
