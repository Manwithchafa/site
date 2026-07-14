@props(['href', 'active' => false])

<a
    href="{{ $href }}"
    {{ $attributes->merge(['class' => $active
        ? 'flex items-center rounded-2xl bg-white px-4 py-3 text-sm font-bold text-[#162b75] shadow-sm'
        : 'flex items-center rounded-2xl px-4 py-3 text-sm font-semibold text-white/65 transition hover:bg-white/10 hover:text-white'
    ]) }}
>
    {{ $slot }}
</a>
