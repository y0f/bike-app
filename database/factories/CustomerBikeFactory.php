<?php

namespace Database\Factories;

use App\Enums\BikeType;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerBikeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'identifier' => $this->faker->uuid(),
            'brand' => $this->faker->word(),
            'model' => $this->faker->word(),
            'type' => BikeType::Sport,
            'image' => 'avatar.png',
            'color' => $this->faker->colorName(),
            'specifications' => $this->faker->sentence(),
        ];
    }
}
