<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class SalaryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'salary' => '$' . $this->faker->numberBetween(0, 499) . ' - $' . $this->faker->numberBetween(500, 999),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
