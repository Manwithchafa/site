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

    public string $sex = '';

    public ?int $age = null;

    public ?string $marital_status = null;

    public ?string $wedding_anniversary = null;

    public ?string $date_of_birth = null;

    public string $phone = '';

    public ?string $email = null;

    public ?string $city = null;

    public ?string $residential_address = null;

    public ?string $business_address = null;

    public ?string $nearest_bus_stop = null;

    public ?string $occupation = null;

    public ?string $invited_by = null;

    public ?string $invited_by_name = null;

    public ?string $invited_by_phone = null;

    public string $born_again = '0';

    public ?string $born_again_when = null;

    public string $is_baptized = '0';

    public string $wants_membership = '0';

    public string $wants_counsel = '0';

    public ?string $preferred_visit_date = null;

    public ?string $prayer_request = null;

    public function mount(QrCode $qrCode): void
    {
        $this->qrCode = $qrCode->loadMissing(['church']);
    }

    public function submit(RegisterVisitorService $service)
    {
        $data = $this->all();

        $data['born_again'] = $data['born_again'] === '1';
        $data['wants_membership'] = $data['wants_membership'] === '1';
        $data['wants_counsel'] = $data['wants_counsel'] === '1';
        $data['is_baptized'] = $data['is_baptized'] === '1';

        $validated = $this->validate(
            StoreVisitorRegistrationRequest::visitorRegistrationRules(),
            StoreVisitorRegistrationRequest::visitorRegistrationMessages()
        );

        $validated = array_merge($validated, [
            'born_again' => $data['born_again'],
            'wants_membership' => $data['wants_membership'],
            'wants_counsel' => $data['wants_counsel'],
            'is_baptized' => $data['is_baptized'],
        ]);

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

