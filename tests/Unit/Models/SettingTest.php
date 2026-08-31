<?php

namespace Tests\Unit\Models;

use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SettingTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_get_and_set_settings(): void
    {
        Setting::set('company_phone', '+255742448965');

        $this->assertEquals('+255742448965', Setting::get('company_phone'));
        $this->assertEquals('default_value', Setting::get('non_existent_key', 'default_value'));
    }

    public function test_it_updates_existing_setting(): void
    {
        Setting::set('support_email', 'old@reland.co.tz');
        $this->assertEquals('old@reland.co.tz', Setting::get('support_email'));

        Setting::set('support_email', 'new@reland.co.tz');
        $this->assertEquals('new@reland.co.tz', Setting::get('support_email'));
    }
}
