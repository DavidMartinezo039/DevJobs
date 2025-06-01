<?php

use App\Livewire\VacanciesManager;
use App\Models\Category;
use App\Models\Salary;
use App\Models\User;
use App\Models\Vacancy;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

use function Pest\Laravel\{actingAs, assertDatabaseHas, assertDatabaseMissing};

beforeEach(function () {
    Storage::fake('public');

    $this->user = User::factory()->create();
    $this->user->assignRole('god');
    actingAs($this->user);

    $this->category = Category::factory()->create();
    $this->salary = Salary::factory()->create();
});

it('renders the component correctly', function () {
    Livewire::test(VacanciesManager::class)
        ->assertStatus(200)
        ->assertSeeLivewire(VacanciesManager::class);
});

it('authorizes viewing vacancies', function () {
    Livewire::test(VacanciesManager::class)
        ->assertViewIs('livewire.vacancies-manager');
});

it('can create a new vacancy', function () {
    $image = UploadedFile::fake()->image('vacancy.jpg');

    Livewire::test(VacanciesManager::class)
        ->set('title', 'New Vacancy')
        ->set('salary', $this->salary->id)
        ->set('category', $this->category->id)
        ->set('company', 'OpenAI')
        ->set('last_day', now()->addWeek()->format('Y-m-d'))
        ->set('description', 'This is a test vacancy.')
        ->set('image', $image)
        ->call('store');

    assertDatabaseHas('vacancies', [
        'title' => 'New Vacancy',
        'company' => 'OpenAI',
    ]);

    Storage::disk('public')->assertExists('vacancies/' . $image->hashName());
});

it('can edit a vacancy', function () {
    $vacancy = Vacancy::factory()->create([
        'user_id' => $this->user->id,
    ]);

    Livewire::test(VacanciesManager::class)
        ->call('edit', $vacancy)
        ->set('title', 'Updated Title')
        ->call('update');

    assertDatabaseHas('vacancies', [
        'id' => $vacancy->id,
        'title' => 'Updated Title',
    ]);
});

it('can update image on edit', function () {
    $vacancy = Vacancy::factory()->create([
        'user_id' => $this->user->id,
    ]);

    $newImage = UploadedFile::fake()->image('new.jpg');

    Livewire::test(VacanciesManager::class)
        ->call('edit', $vacancy)
        ->set('new_image', $newImage)
        ->call('update');

    Storage::disk('public')->assertExists('vacancies/' . $newImage->hashName());
});

it('can delete a vacancy', function () {
    $vacancy = Vacancy::factory()->create([
        'user_id' => $this->user->id,
        'image' => 'old.jpg',
    ]);

    Storage::disk('public')->put('vacancies/old.jpg', 'dummy content');

    Livewire::test(VacanciesManager::class)
        ->call('delete', $vacancy);

    assertDatabaseMissing('vacancies', [
        'id' => $vacancy->id,
    ]);

    Storage::disk('public')->assertMissing('vacancies/old.jpg');
});

it('validates required fields on store', function () {
    Livewire::test(VacanciesManager::class)
        ->call('store')
        ->assertHasErrors([
            'title' => 'required',
            'salary' => 'required',
            'category' => 'required',
            'company' => 'required',
            'last_day' => 'required',
            'description' => 'required',
            'image' => 'required',
        ]);
});

it('validates required fields on update', function () {
    $vacancy = Vacancy::factory()->create([
        'user_id' => $this->user->id,
    ]);

    Livewire::test(VacanciesManager::class)
        ->call('edit', $vacancy)
        ->set('title', '')
        ->call('update')
        ->assertHasErrors(['title' => 'required']);
});

it('dispatches the delete confirmation alert event', function () {
    $vacancy = Vacancy::factory()->create([
        'user_id' => $this->user->id,
    ]);

    Livewire::test(VacanciesManager::class)
        ->call('confirmDelete', $vacancy->id)
        ->assertDispatched('DeleteAlert', $vacancy->id);
});
