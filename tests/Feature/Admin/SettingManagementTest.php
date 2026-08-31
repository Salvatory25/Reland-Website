<?php

namespace Tests\Feature\Admin;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SettingManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create();
    }

    public function test_admin_can_view_settings_page(): void
    {
        Setting::set('whatsapp_number', '+255742448965');

        $response = $this->actingAs($this->admin)->get('/admin/settings');

        $response->assertStatus(200);
        $response->assertSee('Website & Brand Settings', false);
    }

    public function test_admin_can_update_settings(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/settings', [
            'contact_email' => 'info@reland.co.tz',
            'contact_phone' => '+255 742 448 965',
            'office_address' => 'Sakina, Arusha, Tanzania',
        ]);

        $response->assertRedirect('/admin/settings');
        $this->assertEquals('info@reland.co.tz', Setting::get('contact_email'));
        $this->assertEquals('+255 742 448 965', Setting::get('contact_phone'));
        $this->assertEquals('Sakina, Arusha, Tanzania', Setting::get('office_address'));
    }
}
