<?php

namespace Tests\Feature\Public;

use App\Models\Plot;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EnquirySubmissionTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_submit_general_enquiry(): void
    {
        $response = $this->post('/enquiry', [
            'name' => 'Baraka John',
            'phone' => '+255754123456',
            'email' => 'baraka@example.com',
            'service_type' => 'Land Surveying',
            'preferred_contact_method' => 'phone',
            'message' => 'I would like to survey my farm in Arusha.',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('enquiries', [
            'name' => 'Baraka John',
            'phone' => '+255754123456',
            'service_type' => 'Land Surveying',
            'status' => 'new',
        ]);
    }

    public function test_user_can_submit_plot_specific_enquiry(): void
    {
        $plot = Plot::factory()->create();

        $response = $this->post('/enquiry', [
            'name' => 'Amina Hassan',
            'phone' => '+255712345678',
            'plot_id' => $plot->id,
            'preferred_contact_method' => 'whatsapp',
            'message' => 'Interested in buying this plot.',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('enquiries', [
            'name' => 'Amina Hassan',
            'plot_id' => $plot->id,
        ]);
    }

    public function test_enquiry_validation_fails_for_invalid_data(): void
    {
        $response = $this->post('/enquiry', [
            'name' => '',
            'phone' => '',
            'preferred_contact_method' => 'invalid_method',
            'message' => '',
        ]);

        $response->assertSessionHasErrors(['name', 'phone', 'preferred_contact_method', 'message']);
    }
}
