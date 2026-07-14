@props([
    'type' => 'button',
    'variant' => 'primary',
])

@php
    $classes = [
        'primary' => 'bg-[#162b75] text-white shadow-lg shadow-[#162b75]/20 hover:bg-[#10215d] focus-visible:outline-[#162b75]',
        'secondary' => 'bg-white text-[#162b75] ring-1 ring-slate-200 hover:bg-slate-50 focus-visible:outline-[#162b75]',
    ][$variant];
@endphp

<button
    type="{{ $type }}"
    {{ $attributes->merge(['class' => "inline-flex w-full items-center justify-center rounded-xl px-5 py-4 text-sm font-bold transition disabled:cursor-not-allowed disabled:opacity-60 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 {$classes}"]) }}
>
    {{ $slot }}
</button>
