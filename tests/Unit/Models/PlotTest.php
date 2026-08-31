<?php

namespace Tests\Unit\Models;

use App\Models\Enquiry;
use App\Models\Location;
use App\Models\Plot;
use App\Models\PlotImage;
use App\Models\PlotType;
use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PlotTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_create_a_plot_with_factory(): void
    {
        $plot = Plot::factory()->create([
            'title' => 'Prime Residential Plot Sakina',
            'price' => 50000000,
            'listing_status' => 'available',
        ]);

        $this->assertDatabaseHas('plots', [
            'id' => $plot->id,
            'title' => 'Prime Residential Plot Sakina',
        ]);
        $this->assertNotEmpty($plot->slug);
        $this->assertNotEmpty($plot->plot_reference);
    }

    public function test_it_belongs_to_plot_type(): void
    {
        $plotType = PlotType::factory()->create();
        $plot = Plot::factory()->create(['plot_type_id' => $plotType->id]);

        $this->assertInstanceOf(PlotType::class, $plot->plotType);
        $this->assertEquals($plotType->id, $plot->plotType->id);
    }

    public function test_it_belongs_to_location(): void
    {
        $location = Location::factory()->create();
        $plot = Plot::factory()->create(['location_id' => $location->id]);

        $this->assertInstanceOf(Location::class, $plot->location);
        $this->assertEquals($location->id, $plot->location->id);
    }

    public function test_it_has_many_images_and_enquiries(): void
    {
        $plot = Plot::factory()->create();

        PlotImage::create([
            'plot_id' => $plot->id,
            'image_path' => 'plots/test.jpg',
            'is_primary' => true,
            'display_order' => 1,
        ]);

        Enquiry::factory()->create([
            'plot_id' => $plot->id,
        ]);

        $this->assertCount(1, $plot->images);
        $this->assertCount(1, $plot->enquiries);
    }

    public function test_scopes_published_featured_and_available(): void
    {
        Plot::factory()->create([
            'is_published' => true,
            'is_featured' => true,
            'listing_status' => 'available',
        ]);

        Plot::factory()->create([
            'is_published' => false,
            'is_featured' => false,
            'listing_status' => 'sold',
        ]);

        $this->assertCount(1, Plot::published()->get());
        $this->assertCount(1, Plot::featured()->get());
        $this->assertCount(1, Plot::available()->get());
    }

    public function test_formatted_price_and_size_accessors(): void
    {
        $plot = Plot::factory()->create([
            'price' => 75000000,
            'currency' => 'TZS',
            'plot_size' => 1200.00,
            'size_unit' => 'SQM',
        ]);

        $this->assertEquals('TZS 75,000,000', $plot->formatted_price);
        $this->assertEquals('1,200 SQM', $plot->formatted_size);
    }

    public function test_full_location_accessor(): void
    {
        $location = Location::factory()->create([
            'area_name' => 'Sakina',
            'district' => 'Arusha Urban',
            'region' => 'Arusha',
        ]);

        $plot = Plot::factory()->create([
            'location_id' => $location->id,
            'street_address' => 'Near Sakina Supermarket',
        ]);

        $this->assertStringContainsString('Near Sakina Supermarket', $plot->full_location);
        $this->assertStringContainsString('Sakina', $plot->full_location);
        $this->assertStringContainsString('Arusha Urban', $plot->full_location);
    }

    public function test_status_badge_classes_and_labels(): void
    {
        $availablePlot = Plot::factory()->create(['listing_status' => 'available']);
        $soldPlot = Plot::factory()->create(['listing_status' => 'sold']);
        $reservedPlot = Plot::factory()->create(['listing_status' => 'reserved']);

        $this->assertStringContainsString('emerald', $availablePlot->status_badge_classes);
        $this->assertStringContainsString('rose', $soldPlot->status_badge_classes);
        $this->assertStringContainsString('amber', $reservedPlot->status_badge_classes);

        app()->setLocale('en');
        $this->assertEquals('Available', $availablePlot->status_label);
        $this->assertEquals('Sold', $soldPlot->status_label);

        app()->setLocale('sw');
        $this->assertEquals('Inapatikana', $availablePlot->status_label);
        $this->assertEquals('Imeuzwa', $soldPlot->status_label);
    }

    public function test_whatsapp_inquiry_url(): void
    {
        Setting::set('whatsapp_number', '+255 742 448 965');
        $plot = Plot::factory()->create(['title' => 'Kisongo Land']);

        $url = $plot->whatsapp_inquiry_url;
        $this->assertStringContainsString('https://wa.me/255742448965', $url);
        $this->assertStringContainsString(rawurlencode($plot->plot_reference), $url);
    }
}
