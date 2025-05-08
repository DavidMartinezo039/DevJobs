<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Salary;
use App\Models\User;
use App\Models\Vacancy;
use Illuminate\Database\Eloquent\Factories\Factory;

class VacancyFactory extends Factory
{
    protected $model = Vacancy::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->jobTitle(),
            'description' => $this->faker->paragraphs(3, true),
            'company' => $this->faker->company(),
            'last_day' => $this->faker->dateTimeBetween('now', '+1 month')->format('Y-m-d'),
            'image' => 'default/default.png',
            'salary_id' => Salary::inRandomOrder()->first()->id ?? Salary::factory(),
            'category_id' => Category::inRandomOrder()->first()->id ?? Category::factory(),
            'user_id' => User::inRandomOrder()->first()->id ?? User::factory(),
            'public' => 1,
        ];
    }
}
