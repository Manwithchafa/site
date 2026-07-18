<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\Visitors\StoreVisitorRegistrationRequest;
use App\Models\QrCode;
use App\Models\VisitorRegistration;
use App\Services\Visitors\RegisterVisitorService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class VisitorRegistrationController extends Controller
{
    public function create(string $code): View
    {
        $qrCode = QrCode::query()
            ->with(['church', 'churchService'])
            ->where('code', $code)
            ->where('status', 'active')
            ->firstOrFail();

        return view('visitor-registration.create', [
            'qrCode' => $qrCode,
        ]);
    }

    public function store(StoreVisitorRegistrationRequest $request, string $code, RegisterVisitorService $service): RedirectResponse
    {
        $qrCode = QrCode::query()
            ->with(['church', 'churchService'])
            ->where('code', $code)
            ->where('status', 'active')
            ->firstOrFail();

        $data = $request->validated();

        $data['born_again'] = $request->boolean('born_again');
        $data['wants_membership'] = $request->boolean('wants_membership');
        $data['wants_counsel'] = $request->boolean('wants_counsel');
        $data['is_baptized'] = $request->boolean('is_baptized');

        $registration = $service->register(
            qrCode: $qrCode,
            data: $data,
            ipAddress: $request->ip(),
            userAgent: $request->userAgent()
        );

        return redirect()->route('visitor-registration.success', $registration);
    }

    public function success(VisitorRegistration $registration): View
    {
        $registration->load(['visitor', 'church']);

        return view('visitor-registration.success', [
            'registration' => $registration,
        ]);
    }
}
