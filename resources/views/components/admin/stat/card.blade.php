@props(['label', 'value', 'hint' => null, 'tone' => 'slate'])

@php
    $tones = [
        'slate' => 'bg-slate-400',
        'blue' => 'bg-blue-400',
        'emerald' => 'bg-emerald-400',
        'violet' => 'bg-[#f5b82e]',
    ];
@endphp

<x-admin.card class="overflow-hidden p-4">
    <div class="flex items-start justify-between gap-3">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.16em] text-slate-500">{{ $label }}</p>
            <p class="mt-2 text-2xl font-bold tracking-tight text-white">{{ $value }}</p>
            @if ($hint)
                <p class="mt-1 text-xs leading-5 text-slate-400">{{ $hint }}</p>
            @endif
        </div>
        <span class="mt-1 h-2.5 w-2.5 shrink-0 rounded-full {{ $tones[$tone] ?? $tones['slate'] }}"></span>
    </div>
</x-admin.card>
