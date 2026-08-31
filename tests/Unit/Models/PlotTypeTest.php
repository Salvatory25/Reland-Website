<?php

namespace Tests\Unit\Models;

use App\Models\Plot;
use App\Models\PlotType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PlotTypeTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_create_plot_type_with_factory(): void
    {
        $type = PlotType::factory()->create([
            'name_en' => 'Commercial Land',
            'name_sw' => 'Viwanja vya Biashara',
        ]);

        $this->assertDatabaseHas('plot_types', [
            'id' => $type->id,
            'name_en' => 'Commercial Land',
        ]);
        $this->assertNotEmpty($type->slug);
    }

    public function test_it_has_many_plots(): void
    {
        $type = PlotType::factory()->create();
        Plot::factory()->count(2)->create(['plot_type_id' => $type->id]);

        $this->assertCount(2, $type->plots);
    }

    public function test_name_attribute_respects_locale(): void
    {
        $type = PlotType::factory()->create([
            'name_en' => 'Residential',
            'name_sw' => 'Makazi',
        ]);

        app()->setLocale('en');
        $this->assertEquals('Residential', $type->name);

        app()->setLocale('sw');
        $this->assertEquals('Makazi', $type->name);
    }
}
