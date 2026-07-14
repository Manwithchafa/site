<x-layouts.public :title="'Visitor Registration · '.$qrCode->church->name">
    <div class="flex flex-1 flex-col justify-center py-4 sm:py-8">
        <livewire:visitor-registration-form :qr-code="$qrCode" />
    </div>
</x-layouts.public>
