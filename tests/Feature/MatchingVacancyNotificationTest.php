<?php

use App\Models\User;
use App\Models\UserPreference;
use App\Models\Vacancy;
use App\Models\Salary;
use App\Models\Category;
use Illuminate\Support\Facades\Mail;
use App\Mail\MatchingVacancyNotification;

use function Pest\Laravel\actingAs;

it('does not notify users without preferences', function () {
    Mail::fake();

    $user = User::factory()->create();
    actingAs($user);

    $vacancy = Vacancy::factory()->create([
        'user_id' => $user->id
    ]);

    // Simula evento, notificación o lógica
    event(new \App\Events\NewVacancyCreated($vacancy));

    Mail::assertNothingSent();
});

it('notifies users with matching salary preference', function () {
    Mail::fake();

    $salary = Salary::factory()->create();
    $category = Category::factory()->create();

    $creator = User::factory()->create();
    $receiver = User::factory()->create();

    UserPreference::factory()->create([
        'user_id' => $receiver->id,
        'salary_id' => $salary->id,
        'category_id' => null,
        'company' => null,
        'keyword' => null,
    ]);

    $vacancy = Vacancy::factory()->create([
        'salary_id' => $salary->id,
        'category_id' => $category->id,
        'user_id' => $creator->id,
    ]);

    event(new \App\Events\NewVacancyCreated($vacancy));

    Mail::assertQueued(MatchingVacancyNotification::class, fn ($mail) =>
    $mail->hasTo($receiver->email)
    );
});

it('notifies users with matching category preference', function () {
    Mail::fake();

    $salary = Salary::factory()->create();
    $category = Category::factory()->create();

    $creator = User::factory()->create();
    $receiver = User::factory()->create();

    UserPreference::factory()->create([
        'user_id' => $receiver->id,
        'salary_id' => null,
        'category_id' => $category->id,
        'company' => null,
        'keyword' => null,
    ]);

    $vacancy = Vacancy::factory()->create([
        'salary_id' => $salary->id,
        'category_id' => $category->id,
        'user_id' => $creator->id,
    ]);


    event(new \App\Events\NewVacancyCreated($vacancy));

    Mail::assertQueued(MatchingVacancyNotification::class, fn ($mail) =>
    $mail->hasTo($receiver->email)
    );
});

it('does not notify creator of the vacancy', function () {
    Mail::fake();

    $salary = Salary::factory()->create();
    $category = Category::factory()->create();

    $creator = User::factory()->create();

    UserPreference::factory()->create([
        'user_id' => $creator->id,
        'salary_id' => $salary->id,
        'category_id' => $category->id,
    ]);

    $vacancy = Vacancy::factory()->create([
        'salary_id' => $salary->id,
        'category_id' => $category->id,
        'user_id' => $creator->id,
    ]);

    event(new \App\Events\NewVacancyCreated($vacancy));

    Mail::assertNotSent(MatchingVacancyNotification::class);
});

it('only notifies users whose preferences match all filled fields', function () {
    Mail::fake();

    $salary = Salary::factory()->create();
    $category = Category::factory()->create();

    $creator = User::factory()->create();
    $receiver = User::factory()->create();

    UserPreference::factory()->create([
        'user_id' => $receiver->id,
        'salary_id' => $salary->id,
        'category_id' => $category->id,
        'company' => 'Acme Inc.',
        'keyword' => 'Laravel'
    ]);

    $vacancy = Vacancy::factory()->create([
        'salary_id' => $salary->id,
        'category_id' => $category->id,
        'company' => 'Acme Inc.',
        'title' => 'Laravel Developer',
        'description' => 'Experience with Laravel needed',
        'user_id' => $creator->id,
    ]);

    event(new \App\Events\NewVacancyCreated($vacancy));

    Mail::assertQueued(MatchingVacancyNotification::class, fn ($mail) =>
    $mail->hasTo($receiver->email)
    );
});
