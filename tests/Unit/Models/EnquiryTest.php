<?php

namespace Tests\Unit\Models;

use App\Models\Enquiry;
use App\Models\Plot;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EnquiryTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_create_enquiry_with_factory(): void
    {
        $enquiry = Enquiry::factory()->create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'service_type' => 'Cadastral Surveying',
        ]);

        $this->assertDatabaseHas('enquiries', [
            'id' => $enquiry->id,
            'name' => 'John Doe',
        ]);
        $this->assertNotEmpty($enquiry->tracking_reference);
    }

    public function test_it_belongs_to_plot_and_project_optionally(): void
    {
        $plot = Plot::factory()->create();
        $project = Project::factory()->create();

        $enquiry = Enquiry::factory()->create([
            'plot_id' => $plot->id,
            'project_id' => $project->id,
        ]);

        $this->assertInstanceOf(Plot::class, $enquiry->plot);
        $this->assertEquals($plot->id, $enquiry->plot->id);
        $this->assertInstanceOf(Project::class, $enquiry->project);
        $this->assertEquals($project->id, $enquiry->project->id);
    }
}
