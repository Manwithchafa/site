@props([
    'name',
    'title',
    'description' => null,
])

<label class="flex cursor-pointer gap-3 rounded-xl border border-slate-200 bg-white p-4 shadow-sm transition hover:border-[#162b75]/40 hover:bg-[#f8f9fc]">
    <input
        type="checkbox"
        name="{{ $name }}"
        {{ $attributes->merge(['class' => 'mt-1 h-5 w-5 rounded border-slate-300 text-[#162b75] focus:ring-[#162b75]/20']) }}
    >
    <span>
        <span class="block text-sm font-semibold text-slate-900">{{ $title }}</span>
        @if ($description)
            <span class="mt-1 block text-sm leading-6 text-slate-500">{{ $description }}</span>
        @endif
    </span>
</label>
