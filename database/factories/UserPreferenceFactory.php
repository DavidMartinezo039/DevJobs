<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\UserPreference;
use App\Models\Salary;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserPreferenceFactory extends Factory
{
    protected $model = UserPreference::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'salary_id' => Salary::factory(),
            'category_id' => Category::factory(),
            'company' => $this->faker->company(),
            'keyword' => $this->faker->randomElement(['Laravel', 'Vue', 'Backend', 'Fullstack']),
        ];
    }

    /**
     * Define a state with null values for optional fields.
     */
    public function partial(): static
    {
        return $this->state(fn () => [
            'company' => null,
            'keyword' => null,
        ]);
    }

    /**
     * Define a state where only salary preference is set.
     */
    public function onlySalary(): static
    {
        return $this->state(fn () => [
            'category_id' => null,
            'company' => null,
            'keyword' => null,
        ]);
    }
}
