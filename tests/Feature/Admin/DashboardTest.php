<?php

namespace Tests\Feature\Admin;

use App\Models\Enquiry;
use App\Models\Plot;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_admin_can_view_dashboard_with_stats(): void
    {
        $admin = User::factory()->create();
        
        Plot::factory()->count(3)->create(['listing_status' => 'available']);
        Project::factory()->count(2)->create(['project_status' => 'completed']);
        Enquiry::factory()->create(['name' => 'Dr. Emmanuel Mrema', 'status' => 'new']);

        $response = $this->actingAs($admin)->get('/admin/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Dr. Emmanuel Mrema');
    }
}
