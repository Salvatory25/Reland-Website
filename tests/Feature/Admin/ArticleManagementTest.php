<?php

namespace Tests\Feature\Admin;

use App\Models\Article;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArticleManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create();
    }

    public function test_admin_can_view_articles_list(): void
    {
        $article = Article::factory()->create(['title' => 'Land Formalization 101']);

        $response = $this->actingAs($this->admin)->get('/admin/articles');

        $response->assertStatus(200);
        $response->assertSee('Land Formalization 101');
    }

    public function test_admin_can_create_article(): void
    {
        $payload = [
            'title' => 'Understanding Deed Plans in Tanzania',
            'excerpt' => 'An essential guide for land buyers in Arusha.',
            'content' => 'Full article content discussing survey laws and title deed procedures.',
        ];

        $response = $this->actingAs($this->admin)->post('/admin/articles', $payload);

        $response->assertRedirect('/admin/articles');
        $this->assertDatabaseHas('articles', [
            'title' => 'Understanding Deed Plans in Tanzania',
        ]);
    }

    public function test_admin_can_update_article(): void
    {
        $article = Article::factory()->create(['title' => 'Initial Title']);

        $payload = [
            'title' => 'Updated Article Title',
            'excerpt' => 'Updated short summary',
            'content' => 'Updated full article text',
        ];

        $response = $this->actingAs($this->admin)->put('/admin/articles/' . $article->id, $payload);

        $response->assertRedirect('/admin/articles');
        $this->assertDatabaseHas('articles', [
            'id' => $article->id,
            'title' => 'Updated Article Title',
        ]);
    }

    public function test_admin_can_delete_article(): void
    {
        $article = Article::factory()->create();

        $response = $this->actingAs($this->admin)->delete('/admin/articles/' . $article->id);

        $response->assertRedirect('/admin/articles');
        $this->assertDatabaseMissing('articles', ['id' => $article->id]);
    }
}
