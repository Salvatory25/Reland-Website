<?php

namespace Tests\Feature\Admin;

use App\Models\Location;
use App\Models\Plot;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LocationManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create();
    }

    public function test_admin_can_view_locations_list(): void
    {
        $location = Location::factory()->create(['area_name' => 'Sakina Prime']);

        $response = $this->actingAs($this->admin)->get('/admin/locations');

        $response->assertStatus(200);
        $response->assertSee('Sakina Prime');
    }

    public function test_admin_can_create_location(): void
    {
        $payload = [
            'region' => 'Arusha',
            'district' => 'Arumeru',
            'ward' => 'Poli',
            'area_name' => 'Poli Hills',
            'description' => 'Great scenic location for development.',
            'is_popular' => 1,
            'display_order' => 5,
        ];

        $response = $this->actingAs($this->admin)->post('/admin/locations', $payload);

        $response->assertRedirect('/admin/locations');
        $this->assertDatabaseHas('locations', [
            'area_name' => 'Poli Hills',
            'district' => 'Arumeru',
        ]);
    }

    public function test_admin_can_update_location(): void
    {
        $location = Location::factory()->create(['area_name' => 'Old Name']);

        $payload = [
            'region' => 'Arusha',
            'district' => 'Arusha City',
            'area_name' => 'New Area Name',
            'description' => 'Updated description',
        ];

        $response = $this->actingAs($this->admin)->put('/admin/locations/' . $location->id, $payload);

        $response->assertRedirect('/admin/locations');
        $this->assertDatabaseHas('locations', [
            'id' => $location->id,
            'area_name' => 'New Area Name',
        ]);
    }

    public function test_admin_cannot_delete_location_with_associated_plots(): void
    {
        $location = Location::factory()->create();
        Plot::factory()->create(['location_id' => $location->id]);

        $response = $this->actingAs($this->admin)->delete('/admin/locations/' . $location->id);

        $this->assertDatabaseHas('locations', ['id' => $location->id]);
    }

    public function test_admin_can_delete_location_without_plots(): void
    {
        $location = Location::factory()->create();

        $response = $this->actingAs($this->admin)->delete('/admin/locations/' . $location->id);

        $response->assertRedirect('/admin/locations');
        $this->assertDatabaseMissing('locations', ['id' => $location->id]);
    }
}
