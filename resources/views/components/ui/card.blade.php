@props(['class' => ''])

<div {{ $attributes->merge(['class' => "rounded-[1.75rem] border border-slate-200/80 bg-white shadow-xl shadow-slate-200/70 {$class}"]) }}>
    {{ $slot }}
</div>
