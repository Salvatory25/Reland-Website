<?php

namespace Tests\Feature;

use App\Models\Plot;
use App\Models\User;
use Database\Seeders\RelandSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RelandPlatformTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RelandSeeder::class);
    }

    public function test_public_home_page_is_successful(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('RELAND');
        $response->assertSee('Arusha');
    }

    public function test_plots_catalog_page_is_successful(): void
    {
        $response = $this->get('/plots');
        $response->assertStatus(200);
    }

    public function test_plot_details_page_is_successful(): void
    {
        $plot = Plot::first();
        $this->assertNotNull($plot);

        $response = $this->get('/plots/' . $plot->slug);
        $response->assertStatus(200);
        $response->assertSee($plot->title);
        $response->assertSee($plot->plot_reference);
    }

    public function test_locations_pages_are_successful(): void
    {
        $response = $this->get('/locations');
        $response->assertStatus(200);
    }

    public function test_static_pages_are_successful(): void
    {
        $this->get('/about')->assertStatus(200);
        $this->get('/services')->assertStatus(200);
        $this->get('/contact')->assertStatus(200);
    }

    public function test_language_switch(): void
    {
        $response = $this->get('/lang/sw');
        $response->assertStatus(302);
        $response->assertSessionHas('locale', 'sw');
    }

    public function test_admin_requires_authentication(): void
    {
        $response = $this->get('/admin/dashboard');
        $response->assertRedirect('/admin/login');
    }

    public function test_admin_can_login_and_view_dashboard(): void
    {
        $admin = User::first();
        $this->assertNotNull($admin);

        $response = $this->actingAs($admin)->get('/admin/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Corporate Dashboard');
        $response->assertSee('Land Projects');
    }
}
