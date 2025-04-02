<?php

namespace Database\Factories;

use App\Models\CV;
use App\Models\WorkExperience;
use Illuminate\Database\Eloquent\Factories\Factory;

class WorkExperienceFactory extends Factory
{
    protected $model = WorkExperience::class;

    public function definition(): array
    {
        return [
            'cv_id' => CV::factory(),
            'company_name' => $this->faker->company,
            'position' => $this->faker->jobTitle,
            'start_date' => $this->faker->date,
            'end_date' => $this->faker->optional()->date,
            'description' => $this->faker->paragraph,
        ];
    }
}
