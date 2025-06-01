<?php

use App\Jobs\NotifyMarketingUsersOfGenderChange;
use App\Models\Gender;
use App\Models\User;
use Illuminate\Support\Facades\Queue;
use function Pest\Laravel\actingAs;

test('user with permission can toggle default gender via GendersManager', function () {
    loginAs('god'); // o un usuario con el permiso 'toggleDefault'
    $gender = Gender::factory()->create(['is_default' => false]);

    Livewire::test(\App\Livewire\Admin\GendersManager::class)
        ->call('markForToggle', $gender->id)
        ->call('saveChanges');

    $this->assertDatabaseHas('genders', [
        'id' => $gender->id,
        'is_default' => true,
    ]);
});

test('can store a new gender via GendersManager', function () {
    Queue::fake();
    loginAs('god');

    Livewire::test(\App\Livewire\Admin\GendersManager::class)
        ->set('type', 'Nuevo Género')
        ->call('store')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('genders', ['type' => 'Nuevo Género']);
});

test('can edit and update gender via GendersManager', function () {
    loginAs('god');
    $gender = Gender::factory()->create(['type' => 'Original']);

    Livewire::test(\App\Livewire\Admin\GendersManager::class)
        ->call('edit', $gender)
        ->set('type', 'Modificado')
        ->call('update');

    expect($gender->fresh()->type)->toBe('Modificado');
});

test('can delete a gender via GendersManager', function () {
    loginAs('god');
    $gender = Gender::factory()->create();

    Livewire::test(\App\Livewire\Admin\GendersManager::class)
        ->call('delete', $gender);

    expect(Gender::find($gender->id))->toBeNull();
});

test('confirmDelete dispatches DeleteAlert event', function () {
    loginAs('god');
    $gender = Gender::factory()->create();

    Livewire::test(\App\Livewire\Admin\GendersManager::class)
        ->call('confirmDelete', $gender->id)
        ->assertDispatched('DeleteAlert', $gender->id);
});

test('markForToggle coverage test', function () {
    $user = User::factory()->create();
    $user->assignRole('god');
    actingAs($user);

    $gender = Gender::factory()->create();

    $component = Livewire::test(\App\Livewire\Admin\GendersManager::class);

    // Llama una vez para entrar en el else
    $component->call('markForToggle', $gender->id);

    // Llama otra vez para entrar en el if
    $component->call('markForToggle', $gender->id);

    // Inspecciona el estado interno para asegurarte que cambió
    $this->assertArrayNotHasKey($gender->id, $component->instance()->pendingChanges);
});

test('delete gender pending chance marked', function () {
    Queue::fake();
    loginAs('god');
    $gender = Gender::factory()->create();

    $component = Livewire::test(\App\Livewire\Admin\GendersManager::class);

    $component->call('markForToggle', $gender->id);
    $this->assertArrayHasKey($gender->id, $component->instance()->pendingChanges);

    $component->call('delete', $gender);

    expect(Gender::find($gender->id))->toBeNull();

    $this->assertArrayNotHasKey($gender->id, $component->instance()->pendingChanges);

    Queue::assertPushed(NotifyMarketingUsersOfGenderChange::class, function ($job) use ($gender) {
        return $job->action === 'deleted' && $job->gender->id === $gender->id;
    });
});
