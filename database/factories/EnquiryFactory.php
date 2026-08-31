<?php

namespace Database\Factories;

use App\Models\Enquiry;
use App\Models\Plot;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Enquiry>
 */
class EnquiryFactory extends Factory
{
    protected $model = Enquiry::class;

    public function definition(): array
    {
        return [
            'tracking_reference' => 'ENQ-' . strtoupper(fake()->unique()->bothify('????-####')),
            'plot_id' => null,
            'project_id' => null,
            'service_type' => 'Land Surveying',
            'name' => fake()->name(),
            'phone' => '+255754' . fake()->numerify('######'),
            'email' => fake()->safeEmail(),
            'preferred_contact_method' => 'phone',
            'message' => fake()->paragraph(),
            'status' => 'new',
            'admin_notes' => null,
        ];
    }
}
