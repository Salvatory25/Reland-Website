<?php

namespace Tests\Feature\Public;

use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContentPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_about_and_contact_pages_load(): void
    {
        $this->get('/about')->assertStatus(200);
        $this->get('/contact')->assertStatus(200);
    }

    public function test_insights_index_and_show_page(): void
    {
        $article = Article::factory()->create([
            'title' => 'Guide to Title Deed Acquisition in Tanzania',
            'published_at' => now(),
        ]);

        $response = $this->get('/insights');
        $response->assertStatus(200);
        $response->assertSee('Guide to Title Deed Acquisition in Tanzania');

        $detailResponse = $this->get('/insights/' . $article->slug);
        $detailResponse->assertStatus(200);
        $detailResponse->assertSee('Guide to Title Deed Acquisition in Tanzania');
    }

    public function test_blog_alias_redirects_or_loads(): void
    {
        $this->get('/blog')->assertStatus(200);
    }
}
