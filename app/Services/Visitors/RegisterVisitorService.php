<?php

namespace App\Services\Visitors;

use App\Models\QrCode;
use App\Models\Visitor;
use App\Models\VisitorRegistration;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Events\VisitorRegistered;

class RegisterVisitorService
{
    public function register(QrCode $qrCode, array $data, ?string $ipAddress = null, ?string $userAgent = null): VisitorRegistration
    {
        return DB::transaction(function () use ($qrCode, $data, $ipAddress, $userAgent) {
            $now = now();

            $visitor = Visitor::create([
                ...Arr::only($data, [
                    'first_name',
                    'last_name',
                    'gender',
                    'date_of_birth',
                    'phone',
                    'email',
                    'address',
                    'nearest_bus_stop',
                    'occupation',
                    'invited_by',
                    'born_again',
                    'wants_membership',
                ]),
                'church_id' => $qrCode->church_id,
                'status' => 'new',
            ]);

            $registration = VisitorRegistration::create([
                'public_uuid' => (string) Str::uuid(),
                'visitor_id' => $visitor->id,
                'church_id' => $qrCode->church_id,
                'church_service_id' => $qrCode->church_service_id,
                'qr_code_id' => $qrCode->id,
                'registered_on' => $now->toDateString(),
                'registered_at' => $now->format('H:i:s'),
                'prayer_request' => $data['prayer_request'] ?? null,
                'ip_address' => $ipAddress,
                'user_agent' => $userAgent,
            ]);

            $qrCode->forceFill(['last_used_at' => $now])->save();

            $registration = $registration->load(['visitor', 'church', 'churchService', 'qrCode']);

            // Dispatch domain event for visitor registration so listeners can queue follow-ups and welcome SMS
            event(new VisitorRegistered($registration));

            return $registration;
        });
    }
}
