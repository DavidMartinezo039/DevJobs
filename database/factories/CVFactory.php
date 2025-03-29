<?php

namespace Database\Factories;

use App\Models\CV;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CVFactory extends Factory
{
    protected $model = CV::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'title' => $this->faker->jobTitle,
            'file_path' => null,
        ];
    }
}
