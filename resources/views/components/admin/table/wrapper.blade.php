<div {{ $attributes->merge(['class' => 'overflow-hidden rounded-2xl border border-white/10 bg-white/[0.045] shadow-xl shadow-black/20 ring-1 ring-white/[0.03]']) }}>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-white/10">
            {{ $slot }}
        </table>
    </div>
</div>
