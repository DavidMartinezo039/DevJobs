<?php

namespace Database\Factories;

use App\Models\CV;
use App\Models\Gender;
use App\Models\PersonalData;
use Illuminate\Database\Eloquent\Factories\Factory;

class PersonalDataFactory extends Factory
{
    protected $model = PersonalData::class;

    public function definition(): array
    {
        return [
            'cv_id' => Cv::factory(),
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'image' => $this->faker->imageUrl(),
            'about_me' => $this->faker->paragraph,
            'workPermits' => [$this->faker->country],
            'birth_date' => $this->faker->date(),
            'city' => $this->faker->city,
            'country' => $this->faker->country,
            'nationality' => [$this->faker->country],
            'email' => [$this->faker->safeEmail],
            'address' => [$this->faker->address],
            'gender_id' => Gender::inRandomOrder()->first()->id ?? Gender::factory(),
        ];
    }
}
