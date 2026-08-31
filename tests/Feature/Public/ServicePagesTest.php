<?php

namespace Tests\Feature\Public;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ServicePagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_services_index_page_loads(): void
    {
        $response = $this->get('/services');
        $response->assertStatus(200);
    }

    public function test_individual_service_pages_load_valid_slugs(): void
    {
        $slugs = [
            'land-surveying',
            'land-formalization',
            'plot-subdivision',
            'boundary-demarcation',
            'land-consultation',
            'plot-sales',
        ];

        foreach ($slugs as $slug) {
            $response = $this->get('/services/' . $slug);
            $response->assertStatus(200);
        }
    }

    public function test_invalid_service_slug_returns_404(): void
    {
        $this->get('/services/invalid-fake-service-name')->assertStatus(404);
    }
}
