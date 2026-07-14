@props(['title' => 'No records found', 'description' => 'There is nothing to show yet.'])

<div {{ $attributes->merge(['class' => 'rounded-3xl border border-dashed border-slate-300 bg-slate-50 px-6 py-10 text-center']) }}>
    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-white text-slate-400 shadow-sm">
        <span class="text-xl">∅</span>
    </div>
    <h3 class="mt-4 text-sm font-semibold text-slate-950">{{ $title }}</h3>
    <p class="mx-auto mt-2 max-w-sm text-sm leading-6 text-slate-500">{{ $description }}</p>
</div>
