<?php

namespace Database\Factories;

use App\Models\Identity;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

class IdentityFactory extends Factory
{
    protected $model = Identity::class;

    public function definition(): array
    {
        return [
            'type' => $this->faker->unique()->word,
        ];
    }
}
