@props(['title', 'description' => null])

<div class="flex items-start justify-between gap-4">
    <div>
        <h2 class="text-sm font-semibold tracking-tight text-white">{{ $title }}</h2>
        @if ($description)
            <p class="mt-1 text-sm leading-6 text-slate-400">{{ $description }}</p>
        @endif
    </div>
    @isset($actions)
        <div class="shrink-0">{{ $actions }}</div>
    @endisset
</div>
