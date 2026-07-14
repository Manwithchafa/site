@props(['label', 'value', 'hint' => null, 'tone' => 'slate'])

@php
    $tones = [
        'slate' => 'bg-[#162b75] text-white',
        'blue' => 'bg-[#233f91] text-white',
        'emerald' => 'bg-emerald-600 text-white',
        'violet' => 'bg-[#d29a18] text-white',
    ];
@endphp

<x-admin.card class="overflow-hidden p-5">
    <div class="flex items-start justify-between gap-4">
        <div>
            <p class="text-sm font-semibold text-slate-500">{{ $label }}</p>
            <p class="mt-3 text-3xl font-extrabold tracking-tight text-[#162b75]">{{ $value }}</p>
            @if ($hint)
                <p class="mt-2 text-xs leading-5 text-slate-500">{{ $hint }}</p>
            @endif
        </div>
        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl {{ $tones[$tone] ?? $tones['slate'] }}">
            <span class="h-2.5 w-2.5 rounded-full bg-current opacity-80"></span>
        </div>
    </div>
</x-admin.card>
