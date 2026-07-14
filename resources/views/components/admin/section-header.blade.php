@props(['title', 'description' => null])

<div class="flex items-start justify-between gap-4">
    <div>
        <h2 class="text-base font-semibold tracking-tight text-slate-950">{{ $title }}</h2>
        @if ($description)
            <p class="mt-1 text-sm leading-6 text-slate-500">{{ $description }}</p>
        @endif
    </div>
    @isset($actions)
        <div class="shrink-0">{{ $actions }}</div>
    @endisset
</div>
