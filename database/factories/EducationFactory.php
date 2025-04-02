<?php

namespace Database\Factories;

use App\Models\CV;
use App\Models\Education;
use Illuminate\Database\Eloquent\Factories\Factory;

class EducationFactory extends Factory
{
    protected $model = Education::class;

    public function definition(): array
    {
        return [
            'cv_id' => CV::factory(),
            'institution' => $this->faker->company,
            'degree' => $this->faker->word,
            'start_date' => $this->faker->date,
            'end_date' => $this->faker->optional()->date,
        ];
    }
}
