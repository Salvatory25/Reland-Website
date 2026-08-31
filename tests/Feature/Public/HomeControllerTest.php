<?php

namespace Tests\Feature\Public;

use App\Models\Location;
use App\Models\Plot;
use App\Models\PlotType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomeControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_renders_successfully(): void
    {
        $location = Location::factory()->create(['area_name' => 'Sakina']);
        $plotType = PlotType::factory()->create(['name_en' => 'Residential']);
        
        $plot = Plot::factory()->create([
            'location_id' => $location->id,
            'plot_type_id' => $plotType->id,
            'is_published' => true,
            'is_featured' => true,
            'listing_status' => 'available',
            'title' => 'Prime Sakina Land Opportunity',
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('RELAND');
        $response->assertSee('Prime Sakina Land Opportunity');
    }
}
