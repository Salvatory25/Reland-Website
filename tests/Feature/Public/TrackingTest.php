<?php

namespace Tests\Feature\Public;

use App\Models\Enquiry;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TrackingTest extends TestCase
{
    use RefreshDatabase;

    public function test_track_page_loads(): void
    {
        $response = $this->get('/track');
        $response->assertStatus(200);
    }

    public function test_can_check_status_with_valid_reference_and_phone(): void
    {
        $enquiry = Enquiry::factory()->create([
            'tracking_reference' => 'REQ-ABC123',
            'phone' => '+255754000111',
            'name' => 'Michael Mwita',
            'status' => 'survey_in_progress',
        ]);

        $response = $this->post('/track', [
            'tracking_reference' => 'REQ-ABC123',
            'phone' => '+255754000111',
        ]);

        $response->assertStatus(200);
        $response->assertSee('REQ-ABC123');
        $response->assertSee('Michael Mwita');
    }

    public function test_check_status_with_invalid_credentials_returns_error(): void
    {
        $response = $this->post('/track', [
            'tracking_reference' => 'REQ-INVALID',
            'phone' => '+255000000000',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
    }
}
