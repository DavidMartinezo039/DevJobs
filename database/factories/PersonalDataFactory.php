<?php

namespace Database\Factories;

use App\Models\CV;
use App\Models\Gender;
use App\Models\PersonalData;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PersonalDataFactory extends Factory
{
    protected $model = PersonalData::class;

    public function definition(): array
    {
        $newImageName = 'profile_' . Str::random(10) . '.png';

        if (!Storage::disk('public')->exists('images/' . $newImageName)) {
            Storage::disk('public')->copy('images/default/default.png', 'vacancies/' . $newImageName);
        }
        return [
            'cv_id' => Cv::factory(),
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'image' => 'default/default.png',
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
