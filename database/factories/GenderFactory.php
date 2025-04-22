<?php

namespace Database\Factories;

use App\Models\Gender;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

class GenderFactory extends Factory
{
    protected $model = Gender::class;

    public function definition(): array
    {
        return [
            'type' => $this->faker->unique()->word,
        ];
    }
}
