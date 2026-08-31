<?php

namespace Tests\Feature\Public;

use Tests\TestCase;

class LocaleSwitchTest extends TestCase
{
    public function test_switching_to_english(): void
    {
        $response = $this->get('/lang/en');
        $response->assertStatus(302);
        $response->assertSessionHas('locale', 'en');
    }

    public function test_switching_to_swahili(): void
    {
        $response = $this->get('/lang/sw');
        $response->assertStatus(302);
        $response->assertSessionHas('locale', 'sw');
    }

    public function test_switching_to_invalid_locale_defaults_to_sw(): void
    {
        $response = $this->get('/lang/es');
        $response->assertStatus(302);
        $response->assertSessionHas('locale', 'sw');
    }
}
