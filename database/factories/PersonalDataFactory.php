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
            'work_permits' => json_encode([$this->faker->country]),
            'birth_date' => $this->faker->date(),
            'city' => $this->faker->city,
            'country' => $this->faker->country,
            'nationality' => json_encode([$this->faker->country]),
            'email' => json_encode([$this->faker->safeEmail]),
            'address' => json_encode([$this->faker->address]),
            'gender_id' => Gender::factory(),
        ];
    }
}
