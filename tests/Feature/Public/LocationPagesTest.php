<?php

namespace Tests\Feature\Public;

use App\Models\Location;
use App\Models\Plot;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LocationPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_locations_index_page_loads(): void
    {
        $location = Location::factory()->create([
            'area_name' => 'Njiro Block D',
            'region' => 'Arusha',
        ]);

        $response = $this->get('/locations');

        $response->assertStatus(200);
        $response->assertSee('Njiro Block D');
    }

    public function test_location_show_page_displays_location_and_plots(): void
    {
        $location = Location::factory()->create([
            'area_name' => 'Themi Valley',
        ]);

        $plot = Plot::factory()->create([
            'location_id' => $location->id,
            'title' => 'Prime Themi Riverside Plot',
            'is_published' => true,
        ]);

        $response = $this->get('/locations/' . $location->slug);

        $response->assertStatus(200);
        $response->assertSee('Themi Valley');
        $response->assertSee('Prime Themi Riverside Plot');
    }
}
