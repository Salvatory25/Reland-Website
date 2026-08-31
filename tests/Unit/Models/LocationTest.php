<?php

namespace Tests\Unit\Models;

use App\Models\Location;
use App\Models\Plot;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LocationTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_create_location_with_factory(): void
    {
        $location = Location::factory()->create([
            'region' => 'Arusha',
            'district' => 'Arumeru',
            'area_name' => 'USA River',
        ]);

        $this->assertDatabaseHas('locations', [
            'id' => $location->id,
            'area_name' => 'USA River',
        ]);
        $this->assertEquals('USA River, Arumeru', $location->display_name);
    }

    public function test_it_has_many_plots_and_available_plots(): void
    {
        $location = Location::factory()->create();

        // 1 available published plot
        Plot::factory()->create([
            'location_id' => $location->id,
            'is_published' => true,
            'listing_status' => 'available',
        ]);

        // 1 sold plot
        Plot::factory()->create([
            'location_id' => $location->id,
            'is_published' => true,
            'listing_status' => 'sold',
        ]);

        $this->assertCount(2, $location->plots);
        $this->assertCount(1, $location->availablePlots);
    }
}
