@props(['class' => ''])

<section {{ $attributes->merge(['class' => "rounded-2xl border border-white/10 bg-white/[0.045] shadow-xl shadow-black/20 ring-1 ring-white/[0.03] backdrop-blur {$class}"]) }}>
    {{ $slot }}
</section>
