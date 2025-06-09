<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Salary;
use App\Models\User;
use App\Models\Vacancy;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class VacancyFactory extends Factory
{
    protected $model = Vacancy::class;

    public function definition(): array
    {
        $newImageName = 'vacancy_' . Str::random(10) . '.png';

        if (!Storage::disk('public')->exists('vacancies/' . $newImageName)) {
            Storage::disk('public')->copy('vacancies/default/default.png', 'vacancies/' . $newImageName);
        }

        return [
            'title' => $this->faker->jobTitle(),
            'description' => $this->faker->paragraphs(3, true),
            'company' => $this->faker->company(),
            'last_day' => $this->faker->dateTimeBetween('now', '+1 month')->format('Y-m-d'),
            'image' => $newImageName,
            'salary_id' => Salary::inRandomOrder()->first()->id ?? Salary::factory(),
            'category_id' => Category::inRandomOrder()->first()->id ?? Category::factory(),
            'user_id' => User::inRandomOrder()->first()->id ?? User::factory(),
            'public' => 1,
        ];
    }
}
