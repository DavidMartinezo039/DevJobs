<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Vacancy;
use App\Models\Salary;
use App\Models\Category;

class FakeDataForTesting extends Command
{
    protected $signature = 'app:fake-data-for-testing';
    protected $description = 'Genera datos falsos de developers, vacantes y postulaciones con CVs únicos';

    public function handle(): void
    {
        $this->info('Generando datos falsos...');

        $this->info('Creando 20 reclutadores...');
        $recruiters = User::factory()->count(20)->create();
        $recruiters->each(fn($user) => $user->assignRole('recruiter'));


        $this->info('👨‍Creando 100 developers con CV...');
        $developers = User::factory()->count(100)->create();
        $developers->each(fn($user) => $user->assignRole('developer'));


        $this->info('Creando 50 vacantes...');
        $recruiters = User::role('recruiter')->inRandomOrder()->take(10)->get();
        $vacancies = collect();

        foreach ($recruiters as $recruiter) {
            $vacancies = $vacancies->merge(
                Vacancy::factory()->count(5)->create([
                    'user_id' => $recruiter->id,
                    'salary_id' => Salary::inRandomOrder()->first()->id,
                    'category_id' => Category::inRandomOrder()->first()->id,
                ])
            );
        }

        $this->info('Creando 1000 postulaciones aleatorias con CVs...');
        $originalCvPath = 'cv/default/fakecv_user1.pdf';

        for ($i = 0; $i < 1000; $i++) {
            $developer = $developers->random();
            $vacancy = $vacancies->random();

            $newFileName = 'cv_' . Str::random(10) . '.pdf';
            Storage::disk('public')->copy($originalCvPath, 'cv/' . $newFileName);

            $vacancy->users()->attach($developer->id, ['cv' => $newFileName]);
        }

        $this->info('Datos de prueba generados correctamente.');
    }
}
