@props(['title' => 'No records found', 'description' => 'There is nothing to show yet.'])

<div {{ $attributes->merge(['class' => 'rounded-2xl border border-dashed border-white/15 bg-white/[0.03] px-6 py-8 text-center']) }}>
    <div class="mx-auto flex h-10 w-10 items-center justify-center rounded-xl bg-white/10 text-slate-400">
        <span class="text-xl">∅</span>
    </div>
    <h3 class="mt-4 text-sm font-semibold text-white">{{ $title }}</h3>
    <p class="mx-auto mt-2 max-w-sm text-sm leading-6 text-slate-400">{{ $description }}</p>
</div>
