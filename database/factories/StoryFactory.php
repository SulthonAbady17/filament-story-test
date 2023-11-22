<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Story;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Story>
 */
class StoryFactory extends Factory
{
    protected $model = Story::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(),
            'synopsis' => fake()->paragraph(),
            'category_id' => Category::all()->random()->id,
            'cover' => fake()->imageUrl(360, 640),
            'is_published' => fake()->boolean()
        ];
    }
}
