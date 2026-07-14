@props([
    'label',
    'name',
    'required' => false,
])

<label class="block">
    <span class="flex items-center gap-1 text-sm font-semibold text-slate-700">
        {{ $label }}
        @if ($required)
            <span class="text-rose-500">*</span>
        @endif
    </span>
    <select
        name="{{ $name }}"
        data-old-value="{{ old($name) }}"
        {{ $attributes->merge(['class' => 'mt-2 block w-full rounded-xl border border-slate-200 bg-white px-4 py-3.5 text-base text-slate-950 shadow-sm outline-none transition focus:border-[#162b75] focus:ring-4 focus:ring-[#162b75]/10']) }}
    >
        {{ $slot }}
    </select>
    @error($name)
        <span class="mt-2 block text-sm text-rose-600">{{ $message }}</span>
    @enderror
</label>
