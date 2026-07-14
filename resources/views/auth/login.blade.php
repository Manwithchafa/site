<x-layouts.public title="Admin Login">
    <div class="flex flex-1 items-center justify-center py-8">
        <div class="w-full max-w-md rounded-[1.75rem] border border-slate-200 bg-white p-6 shadow-xl shadow-slate-200/70 sm:p-8">
            <div class="flex justify-center">
                <div class="flex h-24 w-full max-w-[240px] items-center justify-center rounded-2xl border border-slate-100 bg-white p-3 shadow-sm">
                    <img src="{{ asset('images/CHlogo.png') }}" alt="Christ Embassy" class="max-h-full max-w-full object-contain">
                </div>
            </div>

            <div class="mt-8 text-center">
                <p class="text-xs font-bold uppercase tracking-[0.24em] text-[#d29a18]">Admin access</p>
                <h1 class="mt-3 text-2xl font-extrabold tracking-tight text-[#162b75]">Welcome back</h1>
                <p class="mt-2 text-sm leading-6 text-slate-500">Sign in to manage visitor registrations and reports.</p>
            </div>

            <form method="POST" action="{{ route('login.store') }}" class="mt-8 space-y-5">
                @csrf

                <x-form.input label="Email" name="email" type="email" value="{{ old('email') }}" required autocomplete="email" />
                <x-form.input label="Password" name="password" type="password" required autocomplete="current-password" />

                <label class="flex items-center gap-3 text-sm font-medium text-slate-600">
                    <input type="checkbox" name="remember" value="1" class="h-4 w-4 rounded border-slate-300 text-[#162b75] focus:ring-[#162b75]/20">
                    Remember me
                </label>

                <x-ui.button type="submit">Sign in</x-ui.button>
            </form>

            <p class="mt-6 text-center text-sm text-slate-500">
                Need an account?
                <a href="{{ route('register') }}" class="font-bold text-[#162b75] hover:text-[#10215d]">Create one</a>
            </p>
        </div>
    </div>
</x-layouts.public>
