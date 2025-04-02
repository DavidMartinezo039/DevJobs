<?php

namespace Database\Factories;

use App\Models\DrivingLicense;
use Illuminate\Database\Eloquent\Factories\Factory;

class DrivingLicenseFactory extends Factory
{
    protected $model = DrivingLicense::class;

    public function definition(): array
    {
        return [
            'image' => $this->faker->imageUrl(200, 200, 'transport'),
            'category' => strtoupper($this->faker->unique()->lexify('A?')),
            'vehicle_type' => $this->faker->randomElement(['Car', 'Motorcycle', 'Truck', 'Bus']),
            'max_speed' => $this->faker->optional()->numberBetween(80, 300),
            'max_power' => $this->faker->optional()->randomFloat(2, 50, 500),
            'power_to_weight' => $this->faker->optional()->randomFloat(2, 0.5, 3.0),
            'max_weight' => $this->faker->optional()->numberBetween(500, 5000),
            'max_passengers' => $this->faker->optional()->numberBetween(1, 50),
            'min_age' => $this->faker->numberBetween(16, 21),
        ];
    }
}
