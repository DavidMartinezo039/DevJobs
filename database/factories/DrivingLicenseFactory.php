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
            'type' => $this->faker->word,
        ];
    }
}
