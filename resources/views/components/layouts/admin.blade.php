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
    <body class="bg-[#eef1f7] font-sans text-slate-950 antialiased">
        <div class="relative min-h-screen overflow-hidden">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_rgba(22,43,117,0.12),_transparent_30rem),radial-gradient(circle_at_bottom_right,_rgba(245,184,46,0.16),_transparent_28rem),linear-gradient(180deg,_#f8fafc_0%,_#eef1f7_100%)]"></div>
            <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-[#162b75] via-[#f5b82e] to-[#d9232e]"></div>

            <div class="relative min-h-screen lg:flex">
            <div id="admin-mobile-menu" class="fixed inset-0 z-40 hidden lg:hidden">
                <button type="button" data-admin-menu-close class="absolute inset-0 bg-slate-950/40 backdrop-blur-sm" aria-label="Close menu"></button>
                <aside class="relative flex h-full w-[min(20rem,85vw)] flex-col bg-[#08133f] px-5 py-6 text-white shadow-2xl">
                    <div class="flex items-start justify-between gap-4">
                        <a href="{{ route('admin.dashboard') }}" class="block min-w-0 flex-1 rounded-3xl border border-white/10 bg-white/10 p-4 shadow-sm">
                            <span class="flex h-16 items-center justify-center rounded-2xl bg-white p-2">
                                <img src="{{ asset('images/CHlogo.png') }}" alt="Christ Embassy" class="max-h-full max-w-full object-contain">
                            </span>
                            <span class="mt-3 block">
                                <span class="block truncate text-sm font-bold text-white">{{ $currentChurch->name ?? 'Church ERP' }}</span>
                                <span class="block text-xs font-medium text-white/55">Visitor Management</span>
                            </span>
                        </a>
                        <button type="button" data-admin-menu-close class="rounded-xl border border-white/15 px-3 py-2 text-sm font-bold text-white/80">Close</button>
                    </div>

                    <nav class="mt-8 space-y-1">
                        <x-admin.nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">Dashboard Home</x-admin.nav-link>
                        <x-admin.nav-link :href="route('admin.visitors.index')" :active="request()->routeIs('admin.visitors.*')">Visitor List</x-admin.nav-link>
                        <x-admin.nav-link :href="route('visitor-registration.create', 'welcome-service')">QR Registration</x-admin.nav-link>
                        <x-admin.nav-link :href="route('admin.reports.index')" :active="request()->routeIs('admin.reports.*')">Reports</x-admin.nav-link>
                    </nav>

                    <form method="POST" action="{{ route('logout') }}" class="mt-auto">
                        @csrf
                        <button type="submit" class="w-full rounded-2xl border border-white/15 bg-white/10 px-4 py-3 text-sm font-bold text-white/80 transition hover:bg-white/15">
                            Sign out
                        </button>
                    </form>
                </aside>
            </div>

            <aside class="hidden w-72 shrink-0 bg-[#08133f] px-5 py-6 text-white shadow-2xl shadow-[#08133f]/25 lg:sticky lg:top-0 lg:flex lg:h-screen lg:flex-col">
                <a href="{{ route('admin.dashboard') }}" class="block rounded-3xl border border-white/10 bg-white/10 p-4 shadow-sm">
                    <span class="flex h-20 items-center justify-center rounded-2xl bg-white p-2">
                        <img src="{{ asset('images/CHlogo.png') }}" alt="Christ Embassy" class="max-h-full max-w-full object-contain">
                    </span>
                    <span class="mt-4 block">
                        <span class="block text-sm font-bold text-white">{{ $currentChurch->name ?? 'Church ERP' }}</span>
                        <span class="block text-xs font-medium text-white/55">Visitor Management</span>
                    </span>
                </a>

                <nav class="mt-8 space-y-1">
                    <x-admin.nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">Dashboard Home</x-admin.nav-link>
                    <x-admin.nav-link :href="route('admin.visitors.index')" :active="request()->routeIs('admin.visitors.*')">Visitor List</x-admin.nav-link>
                    <x-admin.nav-link :href="route('visitor-registration.create', 'welcome-service')">QR Registration</x-admin.nav-link>
                    <x-admin.nav-link :href="route('admin.reports.index')" :active="request()->routeIs('admin.reports.*')">Reports</x-admin.nav-link>
                </nav>

                <div class="mt-auto space-y-4">
                    <div class="rounded-3xl border border-white/10 bg-white/10 p-4">
                        <p class="text-sm font-bold text-white">{{ auth()->user()->name }}</p>
                        <p class="mt-1 truncate text-xs text-white/55">{{ auth()->user()->email }}</p>
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
                <header class="sticky top-0 z-20 border-b border-white/70 bg-white/80 backdrop-blur-xl">
                    <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-4 sm:px-6 lg:px-8">
                        <div class="min-w-0">
                            <p class="text-xs font-bold uppercase tracking-[0.2em] text-[#d29a18]">Admin</p>
                            <h1 class="truncate text-xl font-extrabold tracking-tight text-[#162b75] sm:text-2xl">{{ $pageTitle ?? $title ?? 'Dashboard' }}</h1>
                            @isset($pageDescription)
                                <p class="mt-1 hidden text-sm text-slate-500 sm:block">{{ $pageDescription }}</p>
                            @endisset
                        </div>

                        <div class="flex items-center gap-2">
                            <button type="button" data-admin-menu-open class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm font-bold text-slate-700 shadow-sm lg:hidden">Menu</button>
                            <a href="{{ route('admin.dashboard') }}" class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm font-bold text-slate-700 shadow-sm lg:hidden">Home</a>
                            <a href="{{ route('admin.visitors.index') }}" class="rounded-xl bg-[#162b75] px-3 py-2 text-sm font-bold text-white shadow-sm lg:hidden">Visitors</a>
                            <form method="POST" action="{{ route('logout') }}" class="lg:hidden">
                                @csrf
                                <button type="submit" class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm font-bold text-slate-700 shadow-sm">Logout</button>
                            </form>
                        </div>
                    </div>
                </header>

                <main class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
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
