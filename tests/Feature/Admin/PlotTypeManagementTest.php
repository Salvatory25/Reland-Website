<?php

namespace Tests\Feature\Admin;

use App\Models\Plot;
use App\Models\PlotType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PlotTypeManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create();
    }

    public function test_admin_can_view_plot_types_list(): void
    {
        $type = PlotType::factory()->create(['name_en' => 'Industrial Land Zone']);

        $response = $this->actingAs($this->admin)->get('/admin/plot-types');

        $response->assertStatus(200);
        $response->assertSee('Industrial Land Zone');
    }

    public function test_admin_can_create_plot_type(): void
    {
        $payload = [
            'name_en' => 'Agricultural Farm',
            'name_sw' => 'Mashamba ya Kilimo',
            'description' => 'Fertile lands suitable for crops.',
            'icon' => 'tree',
            'is_active' => 1,
            'display_order' => 3,
        ];

        $response = $this->actingAs($this->admin)->post('/admin/plot-types', $payload);

        $response->assertRedirect('/admin/plot-types');
        $this->assertDatabaseHas('plot_types', [
            'name_en' => 'Agricultural Farm',
            'name_sw' => 'Mashamba ya Kilimo',
        ]);
    }

    public function test_admin_can_update_plot_type(): void
    {
        $type = PlotType::factory()->create(['name_en' => 'Old Type']);

        $payload = [
            'name_en' => 'Updated Commercial',
            'name_sw' => 'Biashara Iliyosasishwa',
            'description' => 'Updated info',
        ];

        $response = $this->actingAs($this->admin)->put('/admin/plot-types/' . $type->id, $payload);

        $response->assertRedirect('/admin/plot-types');
        $this->assertDatabaseHas('plot_types', [
            'id' => $type->id,
            'name_en' => 'Updated Commercial',
        ]);
    }

    public function test_admin_cannot_delete_plot_type_with_plots(): void
    {
        $type = PlotType::factory()->create();
        Plot::factory()->create(['plot_type_id' => $type->id]);

        $response = $this->actingAs($this->admin)->delete('/admin/plot-types/' . $type->id);

        $this->assertDatabaseHas('plot_types', ['id' => $type->id]);
    }

    public function test_admin_can_delete_unused_plot_type(): void
    {
        $type = PlotType::factory()->create();

        $response = $this->actingAs($this->admin)->delete('/admin/plot-types/' . $type->id);

        $response->assertRedirect('/admin/plot-types');
        $this->assertDatabaseMissing('plot_types', ['id' => $type->id]);
    }
}
