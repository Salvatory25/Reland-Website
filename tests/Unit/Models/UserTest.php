<?php

namespace Tests\Unit\Models;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_user_with_hashed_password(): void
    {
        $user = User::factory()->create([
            'password' => 'secret123',
        ]);

        $this->assertTrue(Hash::check('secret123', $user->password));
        $this->assertArrayNotHasKey('password', $user->toArray());
    }
}
