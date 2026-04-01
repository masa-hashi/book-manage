<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name'        => $this->faker->unique()->word(),
            'slug'        => $this->faker->unique()->slug(1),
            'description' => $this->faker->optional()->sentence(),
        ];
    }
}
