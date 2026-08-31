<?php

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Project>
 */
class ProjectFactory extends Factory
{
    protected $model = Project::class;

    public function definition(): array
    {
        $name = 'Project ' . fake()->company() . ' ' . fake()->unique()->numberBetween(100, 999);
        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'location_name' => 'Njiro, Arusha',
            'project_type' => 'Residential Cadastral Surveying',
            'short_description' => fake()->sentence(),
            'description' => fake()->paragraphs(2, true),
            'services_performed' => ['Cadastral Surveying', 'Beacon Demarcation', 'Town Planning'],
            'project_status' => 'completed',
            'client_type' => 'Private Developer',
            'size_covered' => '25 Acres',
            'completion_date' => now(),
            'latitude' => -3.3869,
            'longitude' => 36.6830,
            'featured_image' => 'https://images.unsplash.com/photo-1500382017468-9049fed747ef',
            'is_featured' => true,
            'is_published' => true,
            'views_count' => 10,
        ];
    }
}
