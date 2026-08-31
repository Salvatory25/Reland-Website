<?php

namespace Database\Factories;

use App\Models\Location;
use App\Models\Plot;
use App\Models\PlotType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Plot>
 */
class PlotFactory extends Factory
{
    protected $model = Plot::class;

    public function definition(): array
    {
        $title = fake()->sentence(4);
        return [
            'title' => $title,
            'slug' => Str::slug($title . '-' . fake()->unique()->numberBetween(1000, 9999)),
            'plot_reference' => 'REL-ARU-' . fake()->unique()->numberBetween(1000, 9999),
            'plot_type_id' => PlotType::factory(),
            'location_id' => Location::factory(),
            'street_address' => fake()->streetAddress(),
            'listing_status' => 'available',
            'price' => fake()->numberBetween(15000000, 150000000),
            'currency' => 'TZS',
            'price_negotiable' => true,
            'plot_size' => fake()->numberBetween(400, 2000),
            'size_unit' => 'SQM',
            'dimension_details' => '20m x 40m',
            'ownership_title_type' => 'Hati Miliki (Clean Title Deed)',
            'short_description' => fake()->sentence(),
            'description' => fake()->paragraph(),
            'nearby_landmarks' => fake()->words(3, true),
            'road_accessibility' => 'Tarmac Road access',
            'has_electricity' => true,
            'has_water' => true,
            'has_internet' => true,
            'has_fence' => false,
            'topography' => 'Flat',
            'latitude' => -3.3869,
            'longitude' => 36.6830,
            'featured_image' => 'https://images.unsplash.com/photo-1500382017468-9049fed747ef',
            'is_featured' => true,
            'is_published' => true,
            'views_count' => 5,
        ];
    }
}
