<?php

namespace Database\Factories;

use App\Models\PlotType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<PlotType>
 */
class PlotTypeFactory extends Factory
{
    protected $model = PlotType::class;

    public function definition(): array
    {
        $name = fake()->word() . ' ' . fake()->unique()->numberBetween(100, 999);
        return [
            'name_en' => ucfirst($name),
            'name_sw' => 'Viwanja vya ' . ucfirst($name),
            'slug' => Str::slug($name),
            'description' => fake()->sentence(),
            'icon' => 'home',
            'is_active' => true,
            'display_order' => 1,
        ];
    }
}
