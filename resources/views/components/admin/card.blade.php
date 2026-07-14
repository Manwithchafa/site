@props(['class' => ''])

<section {{ $attributes->merge(['class' => "rounded-[1.5rem] border border-white/80 bg-white/95 shadow-xl shadow-slate-200/80 ring-1 ring-slate-900/5 backdrop-blur {$class}"]) }}>
    {{ $slot }}
</section>
