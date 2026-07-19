@props(['tone' => 'slate'])

@php
    $tones = [
        'slate' => 'bg-white/10 text-slate-300 ring-white/10',
        'blue' => 'bg-blue-500/10 text-blue-200 ring-blue-400/20',
        'emerald' => 'bg-emerald-500/10 text-emerald-200 ring-emerald-400/20',
        'violet' => 'bg-[#f5b82e]/10 text-[#f8d884] ring-[#f5b82e]/25',
        'rose' => 'bg-rose-500/10 text-rose-200 ring-rose-400/20',
    ];
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center rounded-full px-2.5 py-1 text-xs font-bold ring-1 '.($tones[$tone] ?? $tones['slate'])]) }}>
    {{ $slot }}
</span>
