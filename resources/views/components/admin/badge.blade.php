@props(['tone' => 'slate'])

@php
    $tones = [
        'slate' => 'bg-slate-100 text-slate-700',
        'blue' => 'bg-[#eef2ff] text-[#162b75]',
        'emerald' => 'bg-emerald-50 text-emerald-700',
        'violet' => 'bg-[#fff8e7] text-[#9a6a09]',
        'rose' => 'bg-rose-50 text-rose-700',
    ];
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center rounded-full px-2.5 py-1 text-xs font-bold '.($tones[$tone] ?? $tones['slate'])]) }}>
    {{ $slot }}
</span>
