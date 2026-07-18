<?php

namespace Tests\Feature;

use App\Models\Church;
use App\Models\ChurchService;
use App\Models\QrCode;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QrCodeGenerationTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_generates_a_qr_code_png_for_welcome_service(): void
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

        QrCode::create([
            'church_id' => $church->id,
            'church_service_id' => $service->id,
            'code' => 'welcome-service',
            'label' => 'Welcome Service',
            'status' => 'active',
        ]);

        $response = $this->get(route('visitor-registration.qr-image', 'welcome-service'));

        $response->assertOk();
        $response->assertHeader('Content-Type', 'image/png');
        $this->assertStringStartsWith("\x89PNG", $response->getContent());
    }

    public function test_it_downloads_a_qr_code_png_for_welcome_service(): void
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

        QrCode::create([
            'church_id' => $church->id,
            'church_service_id' => $service->id,
            'code' => 'welcome-service',
            'label' => 'Welcome Service',
            'status' => 'active',
        ]);

        $response = $this->get(route('visitor-registration.qr-image.download', 'welcome-service'));

        $response->assertOk();
        $response->assertHeader('Content-Type', 'image/png');
        $response->assertHeader('Content-Disposition', 'attachment; filename="welcome-service-qr.png"');
        $this->assertStringStartsWith("\x89PNG", $response->getContent());
    }
}
