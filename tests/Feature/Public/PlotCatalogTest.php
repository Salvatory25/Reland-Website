<?php

namespace Tests\Feature\Public;

use App\Models\Location;
use App\Models\Plot;
use App\Models\PlotType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PlotCatalogTest extends TestCase
{
    use RefreshDatabase;

    public function test_plots_index_page_loads_and_displays_plots(): void
    {
        $plot = Plot::factory()->create([
            'title' => 'Affordable Plot in USA River',
            'is_published' => true,
        ]);

        $response = $this->get('/plots');

        $response->assertStatus(200);
        $response->assertSee('Affordable Plot in USA River');
    }

    public function test_plots_can_be_filtered_by_location(): void
    {
        $loc1 = Location::factory()->create(['area_name' => 'Sakina']);
        $loc2 = Location::factory()->create(['area_name' => 'Kisongo']);

        $plot1 = Plot::factory()->create(['location_id' => $loc1->id, 'title' => 'Sakina Unique Plot', 'is_published' => true]);
        $plot2 = Plot::factory()->create(['location_id' => $loc2->id, 'title' => 'Kisongo Unique Plot', 'is_published' => true]);

        $response = $this->get('/plots?location=' . $loc1->id);

        $response->assertStatus(200);
        $response->assertSee('Sakina Unique Plot');
        $response->assertDontSee('Kisongo Unique Plot');
    }

    public function test_plots_can_be_filtered_by_type(): void
    {
        $type1 = PlotType::factory()->create(['name_en' => 'Residential']);
        $type2 = PlotType::factory()->create(['name_en' => 'Industrial']);

        $plot1 = Plot::factory()->create(['plot_type_id' => $type1->id, 'title' => 'Residential Alpha', 'is_published' => true]);
        $plot2 = Plot::factory()->create(['plot_type_id' => $type2->id, 'title' => 'Industrial Beta', 'is_published' => true]);

        $response = $this->get('/plots?type=' . $type1->id);

        $response->assertStatus(200);
        $response->assertSee('Residential Alpha');
        $response->assertDontSee('Industrial Beta');
    }

    public function test_plot_show_page_displays_details_and_increments_views(): void
    {
        $plot = Plot::factory()->create([
            'title' => 'Scenic Mountain View Plot',
            'views_count' => 0,
            'is_published' => true,
        ]);

        $response = $this->get('/plots/' . $plot->slug);

        $response->assertStatus(200);
        $response->assertSee('Scenic Mountain View Plot');
        $this->assertEquals(1, $plot->fresh()->views_count);
    }

    public function test_plot_show_returns_404_for_non_existing_or_unpublished_plot(): void
    {
        $unpublishedPlot = Plot::factory()->create([
            'is_published' => false,
        ]);

        $this->get('/plots/non-existent-plot-slug-999')->assertStatus(404);
        $this->get('/plots/' . $unpublishedPlot->slug)->assertStatus(404);
    }
}
