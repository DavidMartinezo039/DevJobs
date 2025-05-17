<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Salary;
use App\Models\User;
use App\Models\Vacancy;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class VacancySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user2 = User::find(2);
        $user3 = User::find(3);
        $candidate = User::find(1);

        $salary = Salary::inRandomOrder()->first();
        $category = Category::inRandomOrder()->first();

        $vacanciesUser2 = Vacancy::factory()->count(4)->create([
            'user_id' => $user2->id,
            'salary_id' => $salary->id,
            'category_id' => $category->id,
        ]);

        $vacanciesUser3 = Vacancy::factory()->count(4)->create([
            'user_id' => $user3->id,
            'salary_id' => $salary->id,
            'category_id' => $category->id,
        ]);

        foreach ($vacanciesUser2->take(2) as $vacancy) {
            $this->assignCandidateAndImage($vacancy, $candidate);
        }

        foreach ($vacanciesUser3->take(2) as $vacancy) {
            $this->assignCandidateAndImage($vacancy, $candidate);
        }
    }

    private function assignCandidateAndImage(Vacancy $vacancy, User $candidate)
    {
        $newImageName = 'vacancy_' . Str::random(10) . '.png';
        Storage::disk('public')->copy('vacancies/default/default.png', 'vacancies/' . $newImageName);

        $vacancy->update(['image' => $newImageName]);

        $newFileName = 'cv_' . Str::random(10) . '.pdf';
        Storage::disk('public')->copy('cv/default/fakecv_user1.pdf', 'cv/' . $newFileName);

        $vacancy->users()->attach($candidate->id, ['cv' => $newFileName]);
    }

}
