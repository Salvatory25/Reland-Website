<?php

namespace Database\Factories;

use App\Models\Location;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Location>
 */
class LocationFactory extends Factory
{
    protected $model = Location::class;

    public function definition(): array
    {
        $area = fake()->city();
        return [
            'region' => 'Arusha',
            'district' => 'Arusha City',
            'ward' => fake()->streetName(),
            'area_name' => $area,
            'slug' => Str::slug($area . '-' . fake()->unique()->numberBetween(100, 999)),
            'featured_image' => 'https://images.unsplash.com/photo-1500382017468-9049fed747ef',
            'description' => fake()->paragraph(),
            'is_popular' => true,
            'display_order' => 1,
        ];
    }
}
