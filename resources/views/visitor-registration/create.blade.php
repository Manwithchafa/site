<x-layouts.public :title="'Visitor Registration · '.(optional($qrCode->church)->name ?? 'Visitor Registration')">
    <div class="flex flex-1 flex-col justify-center py-4 sm:py-8">
        <livewire:visitor-registration-form :qr-code="$qrCode" />
    </div>
</x-layouts.public>
