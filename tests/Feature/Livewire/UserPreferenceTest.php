<?php

use App\Livewire\UserPreference;
use App\Models\Category;
use App\Models\Salary;
use App\Models\UserPreference as PreferenceModel;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);

    Category::factory()->count(3)->create();
    Salary::factory()->count(3)->create();
});

it('mounts and loads categories, salaries and user preference if exists', function () {
    $pref = PreferenceModel::factory()->create([
        'user_id' => $this->user->id,
        'category_id' => Category::first()->id,
        'salary_id' => Salary::first()->id,
        'company' => 'Acme Inc.',
        'keyword' => 'Laravel',
    ]);

    Livewire::test(UserPreference::class)
        ->assertSet('categories', Category::all())
        ->assertSet('salaries', Salary::all())
        ->assertSet('category', $pref->category_id)
        ->assertSet('salary', $pref->salary_id)
        ->assertSet('company', $pref->company)
        ->assertSet('keyword', $pref->keyword);
});

it('mounts and loads categories and salaries with null preferences if none exist', function () {
    Livewire::test(UserPreference::class)
        ->assertSet('categories', Category::all())
        ->assertSet('salaries', Salary::all())
        ->assertSet('category', null)
        ->assertSet('salary', null)
        ->assertSet('company', null)
        ->assertSet('keyword', null);
});

it('validates inputs properly', function () {
    Livewire::test(UserPreference::class)
        ->set('category', 999999)
        ->set('salary', 'not-a-number')
        ->set('company', str_repeat('a', 300))
        ->set('keyword', str_repeat('b', 300))
        ->call('save')
        ->assertHasErrors([
            'category' => 'exists',
            'salary' => 'exists',
            'company' => 'max',
            'keyword' => 'max',
        ]);
});
