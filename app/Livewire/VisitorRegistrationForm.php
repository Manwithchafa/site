<?php

namespace App\Livewire;

use App\Http\Requests\Visitors\StoreVisitorRegistrationRequest;
use App\Models\QrCode;
use App\Services\Visitors\RegisterVisitorService;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class VisitorRegistrationForm extends Component
{
    public QrCode $qrCode;

    public string $first_name = '';

    public string $last_name = '';

    public string $gender = '';

    public ?string $date_of_birth = null;

    public string $phone = '';

    public ?string $email = null;

    public ?string $address = null;

    public ?string $nearest_bus_stop = null;

    public ?string $occupation = null;

    public ?string $invited_by = null;

    public bool $born_again = false;

    public bool $wants_membership = false;

    public ?string $prayer_request = null;

    public function mount(QrCode $qrCode): void
    {
        $this->qrCode = $qrCode->loadMissing(['church', 'churchService']);
    }

    public function submit(RegisterVisitorService $service)
    {
        $validated = $this->validate(
            StoreVisitorRegistrationRequest::validationRules(),
            StoreVisitorRegistrationRequest::validationMessages()
        );

        $registration = $service->register(
            qrCode: $this->qrCode,
            data: $validated,
            ipAddress: request()->ip(),
            userAgent: request()->userAgent()
        );

        return $this->redirectRoute('visitor-registration.success', $registration, navigate: true);
    }

    public function render(): View
    {
        return view('livewire.visitor-registration-form');
    }
}
