<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? 'Admin' }} · {{ $currentChurch->name ?? config('app.name', 'Church ERP') }}</title>

        <link rel="icon" type="image/png" href="{{ asset('images/CHlogo.png') }}">
        <link rel="apple-touch-icon" href="{{ asset('images/CHlogo.png') }}">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
        @endif

        <script defer src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
        @livewireStyles
    </head>
    <body class="bg-[#070b16] font-sans text-slate-100 antialiased">
        <div class="relative min-h-screen overflow-hidden">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_rgba(59,130,246,0.14),_transparent_28rem),radial-gradient(circle_at_bottom_right,_rgba(245,184,46,0.10),_transparent_30rem),linear-gradient(180deg,_#0b1020_0%,_#070b16_55%,_#05070d_100%)]"></div>
            <div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-[#f5b82e]/70 to-transparent"></div>

            <div class="relative min-h-screen lg:flex">
            <div id="admin-mobile-menu" class="fixed inset-0 z-40 hidden lg:hidden">
                <button type="button" data-admin-menu-close class="absolute inset-0 bg-slate-950/40 backdrop-blur-sm" aria-label="Close menu"></button>
                <aside class="relative flex h-full w-[min(19rem,85vw)] flex-col border-r border-white/10 bg-[#090f1f] px-4 py-5 text-white shadow-2xl">
                    <div class="flex items-start justify-between gap-4">
                        <a href="{{ route('admin.dashboard') }}" class="block min-w-0 flex-1 rounded-2xl border border-white/10 bg-white/[0.04] p-3">
                            <span class="flex h-12 items-center justify-center rounded-xl bg-white p-2">
                                <img src="{{ asset('images/CHlogo.png') }}" alt="Christ Embassy" class="max-h-full max-w-full object-contain">
                            </span>
                            <span class="mt-3 block">
                                <span class="block truncate text-sm font-bold text-white">{{ $currentChurch->name ?? 'Church ERP' }}</span>
                                <span class="block text-xs font-medium text-slate-400">Admin workspace</span>
                            </span>
                        </a>
                        <button type="button" data-admin-menu-close class="rounded-xl border border-white/15 px-3 py-2 text-sm font-bold text-white/80">Close</button>
                    </div>

                    <nav class="mt-6 space-y-1">
                        <x-admin.nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">Overview</x-admin.nav-link>
                        <x-admin.nav-link :href="route('admin.visitors.index')" :active="request()->routeIs('admin.visitors.*')">Visitor Directory</x-admin.nav-link>
                        <x-admin.nav-link :href="route('visitor-registration.create', 'welcome-service')">Registration Form</x-admin.nav-link>
                        <x-admin.nav-link :href="route('admin.reports.index')" :active="request()->routeIs('admin.reports.*')">Reports & Exports</x-admin.nav-link>
                    </nav>

                    <form method="POST" action="{{ route('logout') }}" class="mt-auto">
                        @csrf
                        <button type="submit" class="w-full rounded-2xl border border-white/15 bg-white/10 px-4 py-3 text-sm font-bold text-white/80 transition hover:bg-white/15">
                            Sign out
                        </button>
                    </form>
                </aside>
            </div>

            <aside class="hidden w-64 shrink-0 border-r border-white/10 bg-[#090f1f]/95 px-4 py-5 text-white shadow-2xl shadow-black/30 backdrop-blur lg:sticky lg:top-0 lg:flex lg:h-screen lg:flex-col">
                <a href="{{ route('admin.dashboard') }}" class="block rounded-2xl border border-white/10 bg-white/[0.04] p-3">
                    <span class="flex h-14 items-center justify-center rounded-xl bg-white p-2">
                        <img src="{{ asset('images/CHlogo.png') }}" alt="Christ Embassy" class="max-h-full max-w-full object-contain">
                    </span>
                    <span class="mt-4 block">
                        <span class="block text-sm font-bold text-white">{{ $currentChurch->name ?? 'Church ERP' }}</span>
                        <span class="block text-xs font-medium text-slate-400">Admin workspace</span>
                    </span>
                </a>

                <nav class="mt-6 space-y-1">
                    <p class="px-3 pb-2 text-[0.68rem] font-bold uppercase tracking-[0.22em] text-slate-500">Main Menu</p>
                    <x-admin.nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">Overview</x-admin.nav-link>
                    <x-admin.nav-link :href="route('admin.visitors.index')" :active="request()->routeIs('admin.visitors.*')">Visitor Directory</x-admin.nav-link>
                    <x-admin.nav-link :href="route('visitor-registration.create', 'welcome-service')">Registration Form</x-admin.nav-link>
                    <x-admin.nav-link :href="route('admin.reports.index')" :active="request()->routeIs('admin.reports.*')">Reports & Exports</x-admin.nav-link>
                </nav>

                <div class="mt-auto space-y-4">
                    <div class="rounded-2xl border border-white/10 bg-white/[0.04] p-3">
                        <p class="text-sm font-bold text-white">{{ auth()->user()->name }}</p>
                        <p class="mt-1 truncate text-xs text-slate-400">{{ auth()->user()->email }}</p>
                    </div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full rounded-2xl border border-white/15 bg-white/10 px-4 py-3 text-sm font-bold text-white/80 transition hover:bg-white/15">
                            Sign out
                        </button>
                    </form>
                </div>
            </aside>

            <div class="min-w-0 flex-1">
                <header class="sticky top-0 z-20 border-b border-white/10 bg-[#070b16]/78 backdrop-blur-xl">
                    <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-3 sm:px-6 lg:px-8">
                        <div class="min-w-0">
                            <p class="text-xs font-bold uppercase tracking-[0.2em] text-[#f5b82e]">Admin</p>
                            <h1 class="truncate text-lg font-bold tracking-tight text-white sm:text-xl">{{ $pageTitle ?? $title ?? 'Dashboard' }}</h1>
                            @isset($pageDescription)
                                <p class="mt-1 hidden text-sm text-slate-400 sm:block">{{ $pageDescription }}</p>
                            @endisset
                        </div>

                        <div class="flex items-center gap-2">
                            <button type="button" data-admin-menu-open class="rounded-xl border border-white/10 bg-white/10 px-3 py-2 text-sm font-bold text-white shadow-sm lg:hidden">Menu</button>
                            <a href="{{ route('admin.dashboard') }}" class="rounded-xl border border-white/10 bg-white/10 px-3 py-2 text-sm font-bold text-white shadow-sm lg:hidden">Overview</a>
                            <a href="{{ route('admin.visitors.index') }}" class="rounded-xl bg-[#f5b82e] px-3 py-2 text-sm font-bold text-slate-950 shadow-sm lg:hidden">Visitors</a>
                            <form method="POST" action="{{ route('logout') }}" class="lg:hidden">
                                @csrf
                                <button type="submit" class="rounded-xl border border-white/10 bg-white/10 px-3 py-2 text-sm font-bold text-white shadow-sm">Logout</button>
                            </form>
                        </div>
                    </div>
                </header>

                <main class="mx-auto max-w-7xl px-4 py-5 sm:px-6 lg:px-8">
                    {{ $slot }}
                </main>
            </div>
            </div>
        </div>

        @livewireScripts
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const menu = document.getElementById('admin-mobile-menu');
                const openButtons = document.querySelectorAll('[data-admin-menu-open]');
                const closeButtons = document.querySelectorAll('[data-admin-menu-close]');

                openButtons.forEach((button) => {
                    button.addEventListener('click', () => menu?.classList.remove('hidden'));
                });

                closeButtons.forEach((button) => {
                    button.addEventListener('click', () => menu?.classList.add('hidden'));
                });

                document.addEventListener('keydown', (event) => {
                    if (event.key === 'Escape') {
                        menu?.classList.add('hidden');
                    }
                });
            });
        </script>
    </body>
</html>
