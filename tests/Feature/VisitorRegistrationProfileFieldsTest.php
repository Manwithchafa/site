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

    public function test_registration_form_renders_as_native_post_form(): void
    {
        $qrCode = $this->createQrCode();

        $this->get(route('visitor-registration.create', $qrCode->code))
            ->assertOk()
            ->assertSee('method="POST"', false)
            ->assertSee(route('visitor-registration.store', $qrCode->code), false)
            ->assertDontSee('wire:model', false)
            ->assertDontSee('wire:submit', false);
    }

    public function test_registration_success_page_renders_after_submission(): void
    {
        $qrCode = $this->createQrCode();

        $registration = app(RegisterVisitorService::class)->register($qrCode, [
            'first_name' => 'Ada',
            'last_name' => 'Okafor',
            'phone' => '+2348123456789',
            'born_again' => true,
            'wants_membership' => true,
            'wants_counsel' => true,
            'is_baptized' => true,
        ]);

        $this->get(route('visitor-registration.success', $registration))
            ->assertOk()
            ->assertSee('Registration successful');
    }

    public function test_registration_form_accepts_native_post_fallback(): void
    {
        $qrCode = $this->createQrCode();

        $response = $this->post(route('visitor-registration.store', $qrCode->code), [
            'first_name' => 'Ada',
            'last_name' => 'Okafor',
            'sex' => 'female',
            'phone' => '+2348123456789',
            'born_again' => '1',
            'born_again_when' => '2021-01-01',
            'wants_membership' => '1',
            'wants_counsel' => '1',
            'is_baptized' => '1',
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('visitors', [
            'first_name' => 'Ada',
            'last_name' => 'Okafor',
            'born_again' => true,
            'wants_membership' => true,
            'wants_counsel' => true,
            'is_baptized' => true,
        ]);
    }

    public function test_it_persists_extended_profile_fields_for_a_visitor_registration(): void
    {
        $qrCode = $this->createQrCode();

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
        $this->assertSame('+2348123456789', $visitor->phone);
        $this->assertSame('ada@example.com', $visitor->email);
        $this->assertSame('Abuja', $visitor->city);
        $this->assertSame('No. 1 Test Road', $visitor->residential_address);
        $this->assertSame('Office Plaza', $visitor->business_address);
        $this->assertSame('Mende Bus Stop', $visitor->nearest_bus_stop);
        $this->assertSame('Teacher', $visitor->occupation);
        $this->assertSame('John Doe', $visitor->invited_by_name);
        $this->assertSame('+2348080000000', $visitor->invited_by_phone);
        $this->assertTrue($visitor->born_again);
        $this->assertSame('2021-01-01', $visitor->born_again_when->toDateString());
        $this->assertTrue($visitor->wants_membership);
        $this->assertTrue($visitor->wants_counsel);
        $this->assertSame('2026-07-20', $visitor->preferred_visit_date->toDateString());
        $this->assertTrue($visitor->is_baptized);
    }

    public function test_registration_details_are_saved_to_the_visitor_registrations_table(): void
    {
        $qrCode = $this->createQrCode();

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
            'invited_by' => 'Pastor Paul',
            'invited_by_name' => 'John Doe',
            'invited_by_phone' => '+2348080000000',
            'born_again' => true,
            'born_again_when' => '2021-01-01',
            'wants_membership' => true,
            'wants_counsel' => true,
            'preferred_visit_date' => '2026-07-20',
            'is_baptized' => true,
            'prayer_request' => 'Please pray for me.',
        ], '127.0.0.1', 'phpunit-agent');

        $this->assertDatabaseHas('visitor_registrations', [
            'id' => $registration->id,
            'church_id' => $qrCode->church_id,
            'church_service_id' => $qrCode->church_service_id,
            'qr_code_id' => $qrCode->id,
            'prayer_request' => 'Please pray for me.',
            'ip_address' => '127.0.0.1',
            'user_agent' => 'phpunit-agent',
        ]);
    }

    private function createQrCode(): QrCode
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

        return QrCode::create([
            'church_id' => $church->id,
            'church_service_id' => $service->id,
            'code' => 'welcome-service',
            'label' => 'Welcome Service',
            'status' => 'active',
        ]);
    }
}
