<div {{ $attributes->merge(['class' => 'overflow-hidden rounded-[1.5rem] border border-white/80 bg-white/95 shadow-xl shadow-slate-200/80 ring-1 ring-slate-900/5']) }}>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-200">
            {{ $slot }}
        </table>
    </div>
</div>
