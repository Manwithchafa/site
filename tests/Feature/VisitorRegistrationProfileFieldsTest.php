<?php

namespace Tests\Feature;

use App\Models\Church;
use App\Models\ChurchService;
use App\Models\QrCode;
use App\Services\Visitors\RegisterVisitorService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VisitorRegistrationProfileFieldsTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_persists_extended_profile_fields_for_a_visitor_registration(): void
    {
        $church = Church::create([
            'name' => 'Christ Embassy Test',
            'slug' => 'christ-embassy-test',
            'code' => 'CET',
            'email' => 'hello@example.com',
            'phone' => '+2348000000000',
            'address' => 'Test address',
            'city' => 'Lagos',
            'state' => 'Lagos',
            'country' => 'NG',
            'status' => 'active',
        ]);

        $service = ChurchService::create([
            'church_id' => $church->id,
            'name' => 'Sunday Service',
            'slug' => 'sunday-service',
            'status' => 'active',
        ]);

        $qrCode = QrCode::create([
            'church_id' => $church->id,
            'church_service_id' => $service->id,
            'code' => 'welcome-service',
            'label' => 'Welcome Service',
            'status' => 'active',
        ]);

        $registration = app(RegisterVisitorService::class)->register($qrCode, [
            'first_name' => 'Ada',
            'last_name' => 'Okafor',
            'sex' => 'female',
            'age' => 28,
            'marital_status' => 'Married',
            'wedding_anniversary' => '2020-08-14',
            'phone' => '+2348123456789',
            'email' => 'ada@example.com',
            'city' => 'Abuja',
            'residential_address' => 'No. 1 Test Road',
            'business_address' => 'Office Plaza',
            'nearest_bus_stop' => 'Mende Bus Stop',
            'occupation' => 'Teacher',
            'invited_by_name' => 'John Doe',
            'invited_by_phone' => '+2348080000000',
            'born_again' => true,
            'born_again_when' => '2021-01-01',
            'wants_membership' => true,
            'wants_counsel' => true,
            'preferred_visit_date' => '2026-07-20',
            'prayer_request' => 'Please pray for me.',
        ], '127.0.0.1', 'phpunit');

        $visitor = $registration->visitor;

        $this->assertSame('female', $visitor->sex);
        $this->assertSame(28, $visitor->age);
        $this->assertSame('Married', $visitor->marital_status);
        $this->assertSame('2020-08-14', $visitor->wedding_anniversary->toDateString());
        $this->assertSame('Abuja', $visitor->city);
        $this->assertSame('No. 1 Test Road', $visitor->residential_address);
        $this->assertSame('Office Plaza', $visitor->business_address);
        $this->assertSame('John Doe', $visitor->invited_by_name);
        $this->assertSame('+2348080000000', $visitor->invited_by_phone);
        $this->assertTrue($visitor->born_again);
        $this->assertSame('2021-01-01', $visitor->born_again_when->toDateString());
        $this->assertTrue($visitor->wants_counsel);
        $this->assertSame('2026-07-20', $visitor->preferred_visit_date->toDateString());
    }
}
