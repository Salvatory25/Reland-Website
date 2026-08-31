<?php

namespace Tests\Feature\Admin;

use App\Models\Enquiry;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EnquiryManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create();
    }

    public function test_admin_can_view_enquiries_list(): void
    {
        $enquiry = Enquiry::factory()->create(['name' => 'Sarah Kimberly']);

        $response = $this->actingAs($this->admin)->get('/admin/enquiries');

        $response->assertStatus(200);
        $response->assertSee('Sarah Kimberly');
    }

    public function test_admin_can_view_single_enquiry_details(): void
    {
        $enquiry = Enquiry::factory()->create([
            'name' => 'Dr. Emmanuel Mrema',
            'message' => 'Need urgent deed plan and boundary verification in Sakina.',
        ]);

        $response = $this->actingAs($this->admin)->get('/admin/enquiries/' . $enquiry->id);

        $response->assertStatus(200);
        $response->assertSee('Dr. Emmanuel Mrema');
        $response->assertSee('Need urgent deed plan and boundary verification in Sakina.');
    }

    public function test_admin_can_update_enquiry_status_and_notes(): void
    {
        $enquiry = Enquiry::factory()->create(['status' => 'new']);

        $response = $this->actingAs($this->admin)->put('/admin/enquiries/' . $enquiry->id, [
            'status' => 'site_visit_scheduled',
            'admin_notes' => 'Surveyor scheduled for Thursday morning.',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('enquiries', [
            'id' => $enquiry->id,
            'status' => 'site_visit_scheduled',
            'admin_notes' => 'Surveyor scheduled for Thursday morning.',
        ]);
    }

    public function test_admin_can_delete_enquiry(): void
    {
        $enquiry = Enquiry::factory()->create();

        $response = $this->actingAs($this->admin)->delete('/admin/enquiries/' . $enquiry->id);

        $response->assertRedirect('/admin/enquiries');
        $this->assertDatabaseMissing('enquiries', ['id' => $enquiry->id]);
    }
}
