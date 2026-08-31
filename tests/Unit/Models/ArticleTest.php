<?php

namespace Tests\Unit\Models;

use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArticleTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_create_article(): void
    {
        $article = Article::factory()->create([
            'title' => 'Land Formalization Process in Arusha',
            'published_at' => now(),
        ]);

        $this->assertDatabaseHas('articles', [
            'id' => $article->id,
            'title' => 'Land Formalization Process in Arusha',
        ]);
        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $article->published_at);
    }
}
