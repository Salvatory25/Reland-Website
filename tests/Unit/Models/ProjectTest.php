<?php

namespace Tests\Unit\Models;

use App\Models\Enquiry;
use App\Models\Project;
use App\Models\ProjectImage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_create_project_with_factory(): void
    {
        $project = Project::factory()->create([
            'name' => 'Kisongo Cadastral Survey Scheme',
            'project_type' => 'Formalization',
            'is_published' => true,
        ]);

        $this->assertDatabaseHas('projects', [
            'id' => $project->id,
            'name' => 'Kisongo Cadastral Survey Scheme',
        ]);
        $this->assertNotEmpty($project->slug);
    }

    public function test_it_has_images_and_enquiries_relationships(): void
    {
        $project = Project::factory()->create();

        ProjectImage::create([
            'project_id' => $project->id,
            'image_path' => 'projects/sample.jpg',
            'caption' => 'Master Plan',
            'is_primary' => true,
            'display_order' => 1,
        ]);

        Enquiry::factory()->create([
            'project_id' => $project->id,
        ]);

        $this->assertCount(1, $project->images);
        $this->assertNotNull($project->primaryImage);
        $this->assertCount(1, $project->enquiries);
    }

    public function test_scopes_published_and_featured(): void
    {
        Project::factory()->create(['is_published' => true, 'is_featured' => true]);
        Project::factory()->create(['is_published' => false, 'is_featured' => false]);

        $this->assertCount(1, Project::published()->get());
        $this->assertCount(1, Project::featured()->get());
    }

    public function test_image_url_fallback(): void
    {
        $projectWithImage = Project::factory()->create([
            'featured_image' => 'https://example.com/project.jpg',
        ]);
        $this->assertEquals('https://example.com/project.jpg', $projectWithImage->image_url);

        $projectNoImage = Project::factory()->create([
            'featured_image' => null,
        ]);
        $this->assertNotEmpty($projectNoImage->image_url);
    }
}
