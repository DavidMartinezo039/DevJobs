<?php

use App\Models\Vacancy;
use App\Models\Category;
use App\Models\Salary;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Carbon;

it('creates a vacancy with valid data', function () {
    $vacancy = Vacancy::factory()->create([
        'title' => 'Senior Laravel Developer',
        'last_day' => now()->addDays(10),
    ]);

    expect($vacancy)->toBeInstanceOf(Vacancy::class)
        ->and($vacancy->title)->toBe('Senior Laravel Developer')
        ->and($vacancy->last_day)->toBeInstanceOf(Carbon::class);
});

it('belongs to a salary', function () {
    $vacancy = Vacancy::factory()->create();

    expect($vacancy->salary)->not()->toBeNull();
});

it('belongs to a category', function () {
    $vacancy = Vacancy::factory()->create();

    expect($vacancy->category)->not()->toBeNull();
});

it('belongs to a user (recruiter)', function () {
    $vacancy = Vacancy::factory()->create();

    expect($vacancy->user)->not()->toBeNull()
        ->and($vacancy->recruiter)->toBeInstanceOf(\App\Models\User::class);
});

it('has many candidates through users pivot', function () {
    $vacancy = Vacancy::factory()->create();
    $users = \App\Models\User::factory()->count(2)->create();

    $vacancy->users()->attach($users->pluck('id')->toArray(), ['cv' => 'cv.pdf', 'status' => 'pending']);

    expect($vacancy->users)->toHaveCount(2);
});

it('filters home vacancies by term, category and salary', function () {
    $category = \App\Models\Category::factory()->create();
    $salary = \App\Models\Salary::factory()->create();

    $matching = Vacancy::factory()->create([
        'title' => 'Laravel Developer',
        'company' => 'DevCompany',
        'category_id' => $category->id,
        'salary_id' => $salary->id,
        'last_day' => now()->addDays(5),
    ]);

    $vacancy = Vacancy::factory()->create([
        'salary_id' => $salary->id,
        'category_id' => $category->id,
        'user_id' => 1,
    ]);

    $filters = [
        'term' => 'Laravel',
        'category' => $category->id,
        'salary' => $salary->id,
    ];

    $results = Vacancy::homeVacancies($filters)->get();

    expect($results)->toHaveCount(1)
        ->and($results->first()->id)->toBe($matching->id);
});

it('returns vacancies by moderator and recruiter roles', function () {

    $moderator = User::factory()->create()->assignRole('moderator');
    $recruiter = User::factory()->create()->assignRole('recruiter');
    $regularUser = User::factory()->create();

    Auth::login($moderator);

    Vacancy::factory()->create(['user_id' => $moderator->id]);
    Vacancy::factory()->create(['user_id' => $recruiter->id]);
    Vacancy::factory()->create(['user_id' => $regularUser->id]);

    $results = Vacancy::vacanciesByRol()->get();

    expect($results)->toHaveCount(2);
});
