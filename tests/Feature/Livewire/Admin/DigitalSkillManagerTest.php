<?php

use App\Exports\DigitalSkillsExport;
use App\Livewire\Admin\DigitalSkillManager;
use App\Models\DigitalSkill;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;

it('renders the digital skill manager component', function () {
    $user = User::factory()->create();
    $user->assignRole('moderator');
    $this->actingAs($user);

    Livewire::test(DigitalSkillManager::class)
        ->assertStatus(200)
        ->assertSee(__('No digital skills yet.'));
});

it('renders the digital skill manager component as god', function () {
    $user = User::factory()->create();
    $user->assignRole('god');
    $this->actingAs($user);

    Livewire::test(DigitalSkillManager::class)
        ->assertStatus(200)
        ->assertSee(__('No digital skills yet.'));
});

it('can create a new digital skill', function () {
    $user = User::factory()->create();
    $user->assignRole('moderator');
    $this->actingAs($user);

    Livewire::test(DigitalSkillManager::class)
        ->set('name', 'Laravel')
        ->call('store')
        ->assertHasNoErrors()
        ->assertSee(__('Digital skill created successfully'));

    expect(DigitalSkill::where('name', 'Laravel')->exists())->toBeTrue();
});

it('cannot create digital skill without a name', function () {
    $user = User::factory()->create();
    $user->assignRole('moderator');
    $this->actingAs($user);

    Livewire::test(DigitalSkillManager::class)
        ->set('name', '')
        ->call('store')
        ->assertHasErrors(['name' => 'required']);
});

it('can update a digital skill', function () {
    $user = User::factory()->create();
    $user->assignRole('moderator');
    $this->actingAs($user);

    $skill = DigitalSkill::create(['name' => 'PHP']);

    Gate::shouldReceive('authorize')->with('update', $skill)->andReturn(true);

    Livewire::test(DigitalSkillManager::class)
        ->call('edit', $skill)
        ->set('name', 'PHP 8')
        ->call('update')
        ->assertSee(__('Digital skill updated successfully'));

    expect($skill->fresh()->name)->toBe('PHP 8');
});

it('cannot update to a duplicate name', function () {
    $user = User::factory()->create();
    $user->assignRole('moderator');
    $this->actingAs($user);

    $existing = DigitalSkill::create(['name' => 'Java']);
    $skill = DigitalSkill::create(['name' => 'Ruby']);

    Gate::shouldReceive('authorize')->with('update', $skill)->andReturn(true);

    Livewire::test(DigitalSkillManager::class)
        ->call('edit', $skill)
        ->set('name', 'Java')
        ->call('update')
        ->assertHasErrors(['name' => 'unique']);
});

it('denies unauthorized deletion', function () {
    $user = User::factory()->create();
    $user->assignRole('moderator');
    $this->actingAs($user);

    $skill = DigitalSkill::create(['name' => 'Vue']);

    Gate::shouldReceive('authorize')->with('delete', $skill)->andThrow(new \Illuminate\Auth\Access\AuthorizationException());

    Livewire::test(DigitalSkillManager::class)
        ->call('delete', $skill)
        ->assertForbidden();

    expect(DigitalSkill::where('id', $skill->id)->exists())->toBeTrue();
});

it('can delete a digital skill when authorized', function () {
    $user = User::factory()->create();
    $user->assignRole('moderator');
    $this->actingAs($user);

    $skill = DigitalSkill::create(['name' => 'React']);

    Gate::shouldReceive('authorize')->with('delete', $skill)->andReturn(true);

    Livewire::test(DigitalSkillManager::class)
        ->call('delete', $skill)
        ->assertSee(__('Digital skill deleted successfully'));

    expect(DigitalSkill::where('id', $skill->id)->exists())->toBeFalse();
});

it('dispatches the DeleteAlert event when confirming delete', function () {
    $user = User::factory()->create();
    $user->assignRole('moderator');
    $this->actingAs($user);

    $skill = DigitalSkill::create(['name' => 'Docker']);

    Livewire::test(DigitalSkillManager::class)
        ->call('confirmDelete', $skill->id)
        ->assertDispatched('DeleteAlert', $skill->id);
});

it('restores a soft deleted digital skill via Livewire', function () {
    $user = User::factory()->create();
    $user->assignRole('god');

    $digitalSkill = DigitalSkill::factory()->create();
    $digitalSkill->delete();

    Gate::define('restore', fn($authUser, $skill) => true);

    actingAs($user);

    Livewire::test(DigitalSkillManager::class)
        ->call('restore', $digitalSkill->id);

    expect(DigitalSkill::find($digitalSkill->id))->not->toBeNull()
        ->and(DigitalSkill::onlyTrashed()->find($digitalSkill->id))->toBeNull();
});

it('calls export method', function () {
    Excel::fake();

    $user = User::factory()->create()->assignRole('god');
    actingAs($user);

    Livewire::test(DigitalSkillManager::class)
        ->call('export');

    Excel::assertDownloaded('digital_skills.xlsx', function(DigitalSkillsExport $export) {
        return true;
    });
});
