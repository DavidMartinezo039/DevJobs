<?php

namespace Database\Factories;

use App\Models\DigitalSkill;
use Illuminate\Database\Eloquent\Factories\Factory;

class DigitalSkillFactory extends Factory
{
    protected $model = DigitalSkill::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->word,
        ];
    }
}
