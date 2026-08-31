<?php

namespace Tests\Feature\Admin;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create();
    }

    public function test_admin_can_view_projects_list(): void
    {
        $project = Project::factory()->create([
            'name' => 'Sakina Cadastral Master Survey',
        ]);

        $response = $this->actingAs($this->admin)->get('/admin/projects');

        $response->assertStatus(200);
        $response->assertSee('Sakina Cadastral Master Survey');
    }

    public function test_admin_can_create_project(): void
    {
        $payload = [
            'name' => 'Meru Eco Lodge Surveying',
            'location_name' => 'USA River, Arumeru',
            'project_type' => 'Topographical Survey',
            'project_status' => 'completed',
            'short_description' => 'Eco-lodge surveying',
            'description' => 'Comprehensive survey of 50 acres eco-lodge terrain.',
            'services_performed' => 'RTK Survey, Demarcation, Hati Processing',
            'is_published' => 1,
            'is_featured' => 1,
        ];

        $response = $this->actingAs($this->admin)->post('/admin/projects', $payload);

        $response->assertRedirect('/admin/projects');
        $this->assertDatabaseHas('projects', [
            'name' => 'Meru Eco Lodge Surveying',
            'location_name' => 'USA River, Arumeru',
        ]);
    }

    public function test_admin_can_update_project(): void
    {
        $project = Project::factory()->create([
            'name' => 'Old Project Name',
        ]);

        $payload = [
            'name' => 'Updated Project Name Meru',
            'location_name' => 'Njiro, Arusha',
            'project_type' => 'Master Planning',
            'project_status' => 'in_progress',
            'description' => 'Updated project description.',
        ];

        $response = $this->actingAs($this->admin)->put('/admin/projects/' . $project->id, $payload);

        $response->assertRedirect('/admin/projects');
        $this->assertDatabaseHas('projects', [
            'id' => $project->id,
            'name' => 'Updated Project Name Meru',
            'project_status' => 'in_progress',
        ]);
    }

    public function test_admin_can_toggle_published_and_featured(): void
    {
        $project = Project::factory()->create([
            'is_published' => false,
            'is_featured' => false,
        ]);

        $this->actingAs($this->admin)->post('/admin/projects/' . $project->id . '/toggle-publish');
        $this->assertTrue($project->fresh()->is_published);

        $this->actingAs($this->admin)->post('/admin/projects/' . $project->id . '/toggle-featured');
        $this->assertTrue($project->fresh()->is_featured);
    }

    public function test_admin_can_delete_project(): void
    {
        $project = Project::factory()->create();

        $response = $this->actingAs($this->admin)->delete('/admin/projects/' . $project->id);

        $response->assertRedirect('/admin/projects');
        $this->assertDatabaseMissing('projects', ['id' => $project->id]);
    }
}
