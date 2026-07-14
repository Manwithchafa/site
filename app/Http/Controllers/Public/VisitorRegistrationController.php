<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\QrCode;
use App\Models\VisitorRegistration;
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

    public function success(VisitorRegistration $registration): View
    {
        $registration->load(['visitor', 'church', 'churchService']);

        return view('visitor-registration.success', [
            'registration' => $registration,
        ]);
    }
}
