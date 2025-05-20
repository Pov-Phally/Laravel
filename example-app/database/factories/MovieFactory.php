<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Movie>
 */
class MovieFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
            // You can use the Faker library to generate fake data for your movies
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'genre' => $this->faker->word,
        ];
    }
}
