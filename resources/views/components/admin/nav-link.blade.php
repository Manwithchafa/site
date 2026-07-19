@props(['href', 'active' => false])

<a
    href="{{ $href }}"
    {{ $attributes->merge(['class' => $active
        ? 'flex items-center rounded-xl border border-[#f5b82e]/20 bg-[#f5b82e]/12 px-3 py-2.5 text-sm font-bold text-[#f8d884] shadow-sm'
        : 'flex items-center rounded-xl px-3 py-2.5 text-sm font-semibold text-slate-400 transition hover:bg-white/[0.06] hover:text-white'
    ]) }}
>
    {{ $slot }}
</a>
