<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? ($currentChurch->name ?? config('app.name', 'Church Management Platform')) }}</title>

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
        @livewireStyles
    </head>
    <body class="min-h-screen bg-[#f7f4ed] font-sans text-slate-900 antialiased">
        <div class="relative min-h-screen overflow-hidden">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_rgba(22,43,117,0.08),_transparent_30rem),radial-gradient(circle_at_bottom_right,_rgba(245,184,46,0.14),_transparent_30rem),linear-gradient(180deg,_#fbfaf7_0%,_#f7f4ed_100%)]"></div>
            <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-[#162b75] via-[#f5b82e] to-[#d9232e]"></div>

            <main class="relative mx-auto flex min-h-screen w-full max-w-5xl flex-col px-4 py-6 sm:px-6 lg:px-8">
                {{ $slot }}
            </main>
        </div>

        @livewireScripts
    </body>
</html>
