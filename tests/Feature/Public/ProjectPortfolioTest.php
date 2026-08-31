<?php

namespace Tests\Feature\Public;

use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectPortfolioTest extends TestCase
{
    use RefreshDatabase;

    public function test_projects_index_page_loads(): void
    {
        $project = Project::factory()->create([
            'name' => 'USA River Topographical Survey',
            'is_published' => true,
        ]);

        $response = $this->get('/projects');

        $response->assertStatus(200);
        $response->assertSee('USA River Topographical Survey');
    }

    public function test_project_show_page_displays_details_and_increments_views(): void
    {
        $project = Project::factory()->create([
            'name' => 'Arusha Airport Commercial Scheme',
            'views_count' => 0,
            'is_published' => true,
        ]);

        $response = $this->get('/projects/' . $project->slug);

        $response->assertStatus(200);
        $response->assertSee('Arusha Airport Commercial Scheme');
        $this->assertEquals(1, $project->fresh()->views_count);
    }

    public function test_project_show_returns_404_for_unpublished_or_missing(): void
    {
        $unpublished = Project::factory()->create(['is_published' => false]);

        $this->get('/projects/non-existent-project-slug')->assertStatus(404);
        $this->get('/projects/' . $unpublished->slug)->assertStatus(404);
    }
}
