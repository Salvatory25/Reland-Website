<?php

namespace Tests\Feature\Admin;

use App\Models\Location;
use App\Models\Plot;
use App\Models\PlotType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PlotManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected Location $location;
    protected PlotType $plotType;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create();
        $this->location = Location::factory()->create();
        $this->plotType = PlotType::factory()->create();
    }

    public function test_admin_can_view_plots_list(): void
    {
        $plot = Plot::factory()->create([
            'title' => 'Sakina Prime Plot 001',
            'location_id' => $this->location->id,
            'plot_type_id' => $this->plotType->id,
        ]);

        $response = $this->actingAs($this->admin)->get('/admin/plots');

        $response->assertStatus(200);
        $response->assertSee('Sakina Prime Plot 001');
    }

    public function test_admin_can_create_new_plot(): void
    {
        $payload = [
            'title' => 'New Commercial Plot in Njiro',
            'plot_reference' => 'REL-ARU-7788',
            'plot_type_id' => $this->plotType->id,
            'location_id' => $this->location->id,
            'street_address' => 'Block C, Njiro Road',
            'listing_status' => 'available',
            'price' => 85000000,
            'currency' => 'TZS',
            'price_negotiable' => 1,
            'plot_size' => 1000,
            'size_unit' => 'SQM',
            'ownership_title_type' => 'Clean Title',
            'short_description' => 'Great plot',
            'description' => 'Full description of the plot',
            'is_published' => 1,
            'is_featured' => 1,
        ];

        $response = $this->actingAs($this->admin)->post('/admin/plots', $payload);

        $response->assertRedirect('/admin/plots');
        $this->assertDatabaseHas('plots', [
            'title' => 'New Commercial Plot in Njiro',
            'plot_reference' => 'REL-ARU-7788',
        ]);
    }

    public function test_admin_can_update_existing_plot(): void
    {
        $plot = Plot::factory()->create([
            'location_id' => $this->location->id,
            'plot_type_id' => $this->plotType->id,
            'title' => 'Initial Title',
            'price' => 50000000,
        ]);

        $payload = [
            'title' => 'Updated Title Sakina',
            'plot_reference' => $plot->plot_reference,
            'plot_type_id' => $this->plotType->id,
            'location_id' => $this->location->id,
            'listing_status' => 'reserved',
            'price' => 60000000,
            'currency' => 'TZS',
            'plot_size' => 800,
            'size_unit' => 'SQM',
            'ownership_title_type' => 'Clean Title',
            'description' => 'Updated description text',
        ];

        $response = $this->actingAs($this->admin)->put('/admin/plots/' . $plot->id, $payload);

        $response->assertRedirect('/admin/plots');
        $this->assertDatabaseHas('plots', [
            'id' => $plot->id,
            'title' => 'Updated Title Sakina',
            'listing_status' => 'reserved',
        ]);
    }

    public function test_admin_can_toggle_published_and_featured(): void
    {
        $plot = Plot::factory()->create([
            'location_id' => $this->location->id,
            'plot_type_id' => $this->plotType->id,
            'is_published' => false,
            'is_featured' => false,
        ]);

        $this->actingAs($this->admin)->post('/admin/plots/' . $plot->id . '/toggle-publish');
        $this->assertTrue($plot->fresh()->is_published);

        $this->actingAs($this->admin)->post('/admin/plots/' . $plot->id . '/toggle-featured');
        $this->assertTrue($plot->fresh()->is_featured);
    }

    public function test_admin_can_update_plot_status(): void
    {
        $plot = Plot::factory()->create([
            'location_id' => $this->location->id,
            'plot_type_id' => $this->plotType->id,
            'listing_status' => 'available',
        ]);

        $this->actingAs($this->admin)->post('/admin/plots/' . $plot->id . '/status', [
            'listing_status' => 'sold',
        ]);

        $this->assertEquals('sold', $plot->fresh()->listing_status);
    }

    public function test_admin_can_delete_plot(): void
    {
        $plot = Plot::factory()->create([
            'location_id' => $this->location->id,
            'plot_type_id' => $this->plotType->id,
        ]);

        $response = $this->actingAs($this->admin)->delete('/admin/plots/' . $plot->id);

        $response->assertRedirect('/admin/plots');
        $this->assertDatabaseMissing('plots', ['id' => $plot->id]);
    }
}
