<?php

namespace App\Services\Visitors;

use App\Events\VisitorRegistered;
use App\Models\QrCode;
use App\Models\Visitor;
use App\Models\VisitorRegistration;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class RegisterVisitorService
{
    public function register(QrCode $qrCode, array $data, ?string $ipAddress = null, ?string $userAgent = null): VisitorRegistration
    {
        return DB::transaction(function () use ($qrCode, $data, $ipAddress, $userAgent) {
            $now = now();

            $visitorData = Arr::only($data, [
                'first_name',
                'last_name',
                'sex',
                'age',
                'marital_status',
                'wedding_anniversary',
                'gender',
                'date_of_birth',
                'phone',
                'email',
                'city',
                'address',
                'residential_address',
                'business_address',
                'nearest_bus_stop',
                'occupation',
                'invited_by',
                'invited_by_name',
                'invited_by_phone',
                'born_again',
                'born_again_when',
                'is_baptized',
                'wants_membership',
                'wants_counsel',
                'preferred_visit_date',
            ]);

            $visitor = Visitor::create([
                ...Arr::only($visitorData, Schema::getColumnListing('visitors')),
                'church_id' => $qrCode->church_id,
                'status' => 'new',
            ]);

            $registrationData = [
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
            ];

            $registration = VisitorRegistration::create(
                Arr::only($registrationData, Schema::getColumnListing('visitor_registrations'))
            );

            $qrCode->forceFill(['last_used_at' => $now])->save();

            $registration = $registration->load(['visitor', 'church', 'churchService', 'qrCode']);

            try {
                event(new VisitorRegistered($registration));
            } catch (\Throwable $exception) {
                report($exception);
            }

            return $registration;
        });
    }
}
