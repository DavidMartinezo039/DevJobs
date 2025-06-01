<?php

use App\Livewire\FilterVacancies;
use App\Livewire\HomeVacancies;
use App\Models\Category;
use App\Models\Salary;
use App\Models\Vacancy;
use Livewire\Livewire;
use function Pest\Laravel\get;

it('renders the home vacancies component and shows vacancies', function () {
    $category = Category::factory()->create(['category' => 'Programming']);
    $salary = Salary::factory()->create(['salary' => '3000€']);

    $vacancy = Vacancy::factory()->create([
        'title' => 'Senior Developer',
        'company' => 'Laravel Corp',
        'category_id' => $category->id,
        'salary_id' => $salary->id,
        'last_day' => now()->addDays(5),
    ]);

    Livewire::test(HomeVacancies::class)
        ->assertStatus(200)
        ->assertSee('Senior Developer')
        ->assertSee('Laravel Corp')
        ->assertSee('Programming')
        ->assertSee('3000€');
});

it('shows the vacancy details in the show view', function () {
    $category = Category::factory()->create(['category' => 'Frontend']);
    $salary = Salary::factory()->create(['salary' => '2500€']);

    $vacancy = Vacancy::factory()->create([
        'title' => 'VueJS Dev',
        'company' => 'Frontend Inc',
        'category_id' => $category->id,
        'salary_id' => $salary->id,
        'last_day' => now()->addWeek(),
        'description' => 'Experience with Vue 3',
        'image' => 'test.jpg',
    ]);

    get(route('vacancies.show', $vacancy))
        ->assertOk()
        ->assertSee('VueJS Dev')
        ->assertSee('Frontend Inc')
        ->assertSee('Frontend')
        ->assertSee('2500€')
        ->assertSee('Experience with Vue 3')
        ->assertSee(route('vacancy.download', $vacancy));
});

it('assigns properties when search is called', function () {
    Livewire::test(HomeVacancies::class)
        ->call('search', 'Laravel', 1, 2)
        ->assertSet('term', 'Laravel')
        ->assertSet('category', 1)
        ->assertSet('salary', 2);
});


it('emits search event with correct parameters', function () {
    $category = Category::factory()->create();
    $salary = Salary::factory()->create();

    Livewire::test(FilterVacancies::class)
        ->set('term', 'Fullstack')
        ->set('category', $category->id)
        ->set('salary', $salary->id)
        ->call('readFormData')
        ->assertDispatched('search', 'Fullstack', $category->id, $salary->id);
});
