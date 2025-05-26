<?php

use App\Models\User;
use App\Models\Vacancy;
use App\Models\Salary;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use function Pest\Laravel\artisan;

beforeEach(function () {
    Storage::fake('public');

    Role::findOrCreate('recruiter');
    Role::findOrCreate('developer');

    if (Salary::count() === 0) {
        Salary::factory()->count(5)->create();
    }
    if (Category::count() === 0) {
        Category::factory()->count(5)->create();
    }

    Storage::disk('public')->put('vacancies/default/default.png', 'contenido de imagen de prueba');
    Storage::disk('public')->put('cv/default/fakecv_user1.pdf', 'contenido de PDF de prueba');
});

it('crea 20 reclutadores y les asigna el rol "recruiter"', function () {
    artisan('app:fake-data-for-testing');

    $this->assertDatabaseCount('users', 120);

    $this->assertCount(20, User::role('recruiter')->get());
});

it('crea 100 desarrolladores y les asigna el rol "developer"', function () {
    artisan('app:fake-data-for-testing');

    $this->assertDatabaseCount('users', 120);

    $this->assertCount(100, User::role('developer')->get());
});

it('crea 50 vacantes con asociaciones correctas y imágenes únicas', function () {
    artisan('app:fake-data-for-testing');

    $this->assertDatabaseCount('vacancies', 50);

    $vacancies = Vacancy::all();

    foreach ($vacancies as $vacancy) {
        $this->assertNotNull($vacancy->user_id, 'El user_id de la vacante es nulo');
        $this->assertNotNull($vacancy->salary_id, 'El salary_id de la vacante es nulo');
        $this->assertNotNull($vacancy->category_id, 'El category_id de la vacante es nulo');

        $this->assertStringStartsWith('vacancy_', $vacancy->image, 'El nombre de la imagen de la vacante no comienza con "vacancy_"');
        $this->assertStringEndsWith('.png', $vacancy->image, 'El nombre de la imagen de la vacante no termina con ".png"');

        Storage::disk('public')->assertExists('vacancies/' . $vacancy->image);
    }

    $uniqueImages = $vacancies->pluck('image')->unique();
    $this->assertCount(50, $uniqueImages, 'No todas las imágenes de vacantes son únicas');
});

it('crea 1000 postulaciones con CVs únicos', function () {
    artisan('app:fake-data-for-testing');

    $this->assertDatabaseCount('candidates', 1000);

    $applications = Vacancy::all()->pluck('users')->flatten()->map(fn($user) => $user->pivot->cv);

    $this->assertCount(1000, $applications->unique(), 'No todos los CVs de las postulaciones son únicos');

    foreach ($applications as $cvFileName) {
        $this->assertStringStartsWith('cv_', $cvFileName, 'El nombre del archivo CV no comienza con "cv_"');
        $this->assertStringEndsWith('.pdf', $cvFileName, 'El nombre del archivo CV no termina con ".pdf"');

        Storage::disk('public')->assertExists('cv/' . $cvFileName);
    }
});

it('muestra los mensajes de salida esperados', function () {
    artisan('app:fake-data-for-testing')
        ->expectsOutput('Generando datos falsos...')
        ->expectsOutput('Creando 20 reclutadores...')
        ->expectsOutput('Creando 100 developers con CV...')
        ->expectsOutput('Creando 50 vacantes...')
        ->expectsOutput('Creando 1000 postulaciones aleatorias con CVs...')
        ->expectsOutput('Datos de prueba generados correctamente.')
        ->assertExitCode(0);
});
