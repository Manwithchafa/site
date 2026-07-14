<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_redirects_to_the_default_qr_registration_flow(): void
    {
        $response = $this->get('/');

        $response->assertRedirect(route('visitor-registration.create', 'welcome-service'));
    }
}
