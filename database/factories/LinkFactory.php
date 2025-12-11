<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class LinkFactory extends Factory
{
    public function definition()
    {
        return [
            'slug' => $this->faker->unique()->bothify('??????'),
            'target_url' => $this->faker->url(),
            'is_active' => true,
        ];
    }
}
