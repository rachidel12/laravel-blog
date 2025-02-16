<?php

namespace Database\Factories;

use App\Models\Article;
use App\Models\User;
use Str;

use Illuminate\Database\Eloquent\Factories\Factory;

class ArticleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Article::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id'=>User::factory(),
            'title' => $title = $this->faker->sentence,
            'slug' => Str::slug($title),
            'content' => $this->faker->paragraph,
        ];
    }
}
