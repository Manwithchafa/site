@props([
    'label',
    'name',
    'placeholder' => null,
    'rows' => 4,
])

<label class="block">
    <span class="text-sm font-semibold text-slate-700">{{ $label }}</span>
    <textarea
        name="{{ $name }}"
        rows="{{ $rows }}"
        placeholder="{{ $placeholder }}"
        {{ $attributes->merge(['class' => 'mt-2 block w-full resize-none rounded-xl border border-slate-200 bg-white px-4 py-3.5 text-base text-slate-950 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-[#162b75] focus:ring-4 focus:ring-[#162b75]/10']) }}
    ></textarea>
    @error($name)
        <span class="mt-2 block text-sm text-rose-600">{{ $message }}</span>
    @enderror
</label>
