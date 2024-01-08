<?php

namespace Database\Factories;

use App\Models\ServicePoint;
use Illuminate\Database\Eloquent\Factories\Factory;

class ServicePointFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ServicePoint::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->company,
            'address' => $this->faker->address,
            'zip' => $this->faker->postcode,
            'phone' => $this->faker->phoneNumber,
        ];
    }
}
