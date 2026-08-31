<?php

namespace Database\Factories;

use App\Models\Article;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Article>
 */
class ArticleFactory extends Factory
{
    protected $model = Article::class;

    public function definition(): array
    {
        $title = fake()->sentence(6);
        return [
            'title' => $title,
            'slug' => Str::slug($title . '-' . fake()->unique()->numberBetween(100, 999)),
            'excerpt' => fake()->paragraph(),
            'content' => fake()->paragraphs(3, true),
            'image_url' => 'https://images.unsplash.com/photo-1500382017468-9049fed747ef',
            'published_at' => now(),
        ];
    }
}
