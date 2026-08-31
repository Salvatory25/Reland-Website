<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login_when_accessing_admin_area(): void
    {
        $response = $this->get('/admin/dashboard');
        $response->assertRedirect('/admin/login');
    }

    public function test_login_page_renders_successfully(): void
    {
        $response = $this->get('/admin/login');
        $response->assertStatus(200);
    }

    public function test_admin_can_login_with_correct_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'admin@reland.co.tz',
            'password' => bcrypt('AdminPassword123'),
        ]);

        $response = $this->post('/admin/login', [
            'email' => 'admin@reland.co.tz',
            'password' => 'AdminPassword123',
        ]);

        $response->assertRedirect('/admin/dashboard');
        $this->assertAuthenticatedAs($user);
    }

    public function test_login_fails_with_incorrect_password(): void
    {
        $user = User::factory()->create([
            'email' => 'admin@reland.co.tz',
            'password' => bcrypt('CorrectPassword'),
        ]);

        $response = $this->post('/admin/login', [
            'email' => 'admin@reland.co.tz',
            'password' => 'WrongPassword',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_admin_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/admin/logout');

        $response->assertRedirect('/admin/login');
        $this->assertGuest();
    }
}
