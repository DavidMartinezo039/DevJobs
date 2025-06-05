<?php

use App\Models\User;
use App\Models\Vacancy;
use App\Models\EditRequest;
use App\Models\Salary;
use App\Models\Category;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;

beforeEach(function () {
    Storage::fake('public');
});

it('cleans up unresolved edit requests older than 30 days', function () {
    EditRequest::factory()->create([
        'created_at' => now()->subDays(31),
        'approved' => null,
    ]);

    EditRequest::factory()->create([
        'created_at' => now()->subDays(10),
        'approved' => null,
    ]);

    Artisan::call('requests:cleanup');

    expect(EditRequest::count())->toBe(1);
});

it('cleans up expired vacancies and removes CVs from storage', function () {
    $recruiter = User::factory()->create()->assignRole('recruiter');
    $developer = User::factory()->create()->assignRole('developer');

    $salary = Salary::factory()->create();
    $category = Category::factory()->create();

    $cvFile = 'cv_' . Str::random(10) . '.pdf';
    Storage::disk('public')->put("cv/{$cvFile}", 'fake-pdf');

    $vacancy = Vacancy::factory()->create([
        'user_id' => $recruiter->id,
        'salary_id' => $salary->id,
        'category_id' => $category->id,
        'last_day' => now()->subMonths(2),
        'image' => 'vacancy_' . Str::random(10) . '.png',
    ]);

    Storage::disk('public')->put("vacancies/{$vacancy->image}", 'fake-image');

    DB::table('candidates')->insert([
        'vacancy_id' => $vacancy->id,
        'user_id' => $developer->id,
        'cv' => $cvFile,
    ]);

    Artisan::call('requests:cleanup');

    expect(Vacancy::find($vacancy->id))->toBeNull()
        ->and(Storage::disk('public')->exists("cv/{$cvFile}"))->toBeFalse()
        ->and(Storage::disk('public')->exists("vacancies/{$vacancy->image}"))->toBeFalse();
});
