<x-layouts.public :title="'First Timer Registration · '.$qrCode->church->name">
    <div class="flex flex-1 flex-col justify-center py-4 sm:py-8">
        @include('visitor-registration.form-native', ['qrCode' => $qrCode])
    </div>
</x-layouts.public>
