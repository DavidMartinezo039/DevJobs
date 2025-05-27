<?php

use App\Models\User;
use App\Models\Vacancy;
use App\Models\Salary;
use App\Models\Category;
use Illuminate\Support\Facades\Gate;
use function Pest\Laravel\deleteJson;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

beforeEach(function () {
    $this->salary = Salary::factory()->create();
    $this->category = Category::factory()->create();
});

test('index devuelve todas las vacantes', function () {
    Vacancy::factory()->count(3)->create();

    $response = getJson('/api/vacancies');

    $response->assertOk()
        ->assertJsonCount(3, 'data');
});

test('show devuelve una vacante', function () {
    $vacancy = Vacancy::factory()->create();

    $response = getJson("/api/vacancies/{$vacancy->id}");

    $response->assertOk()
        ->assertJson([
            'data' => [
                'id' => $vacancy->id,
                'title' => $vacancy->title,
            ]
        ]);
});

test('usuario autorizado puede crear una vacante', function () {
    $user = loginAs('god');

    $payload = [
        'title' => 'Desarrollador PHP',
        'description' => 'Se busca desarrollador con experiencia en Laravel.',
        'company' => 'TechCorp',
        'last_day' => now()->addDays(10)->toDateString(),
        'salary_id' => $this->salary->id,
        'category_id' => $this->category->id,
    ];

    $response = postJson('/api/vacancies', $payload);

    $response->assertCreated()
        ->assertJsonFragment(['title' => 'Desarrollador PHP']);

    $this->assertDatabaseHas('vacancies', ['title' => 'Desarrollador PHP']);
});

test('usuario autorizado puede actualizar una vacante', function () {
    $user = loginAs('god');
    $vacancy = Vacancy::factory()->create([
        'salary_id' => $this->salary->id,
        'category_id' => $this->category->id,
    ]);

    $payload = [
        'title' => 'Frontend Developer',
    ];

    $response = putJson("/api/vacancies/{$vacancy->id}", $payload);

    $response->assertOk()
        ->assertJsonFragment(['title' => 'Frontend Developer']);

    $this->assertDatabaseHas('vacancies', ['id' => $vacancy->id, 'title' => 'Frontend Developer']);
});

test('usuario autorizado puede eliminar una vacante', function () {
    $user = loginAs('god');
    $vacancy = Vacancy::factory()->create();

    $response = deleteJson("/api/vacancies/{$vacancy->id}");

    $response->assertOk()
        ->assertJson(['message' => 'Vacancy deleted successfully']);

    $this->assertDatabaseMissing('vacancies', ['id' => $vacancy->id]);
});
