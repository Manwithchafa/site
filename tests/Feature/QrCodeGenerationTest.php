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

    public function test_it_returns_a_qr_code_image_for_a_valid_code(): void
    {
        $qrCode = $this->createQrCode();

        $response = $this->get(route('qr-code.show', ['code' => $qrCode->code]));

        $response->assertOk();
        $response->assertHeader('Content-Type', 'image/svg+xml');
        $response->assertSee('<svg', false);
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
