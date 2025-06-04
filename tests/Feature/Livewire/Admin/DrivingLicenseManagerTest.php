<?php

use App\Livewire\Admin\DrivingLicenseManager;
use App\Models\DrivingLicense;
use App\Models\User;
use Illuminate\Support\Facades\Event;
use Livewire\Livewire;
use App\Events\DrivingLicenseEditRequested;
use function Pest\Laravel\actingAs;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->user->assignRole('god');
    actingAs($this->user);
});

it('can create a driving license', function () {
    Livewire::test(DrivingLicenseManager::class)
        ->set('category', 'B')
        ->set('vehicle_type', 'Car')
        ->set('max_speed', 120)
        ->set('max_power', 100)
        ->set('power_to_weight', '0.1')
        ->set('max_weight', 1500)
        ->set('max_passengers', 5)
        ->set('min_age', 18)
        ->call('store');

    expect(DrivingLicense::where('category', 'B')->exists())->toBeTrue();
});

it('can show a driving license', function () {
    $license = DrivingLicense::factory()->create([
        'category' => 'C',
        'vehicle_type' => 'Truck',
    ]);

    Livewire::test(DrivingLicenseManager::class)
        ->call('show', $license)
        ->assertSet('category', 'C')
        ->assertSet('vehicle_type', 'Truck')
        ->assertSet('isShowMode', true);
});

it('can edit a driving license if authorized', function () {
    $license = DrivingLicense::factory()->create();

    Gate::shouldReceive('allows')->with('update', $license)->andReturnTrue();

    Livewire::test(DrivingLicenseManager::class)
        ->call('edit', $license)
        ->assertSet('isEditMode', true);
});

it('dispatches edit requested event if not authorized', function () {
    Event::fake();

    $license = DrivingLicense::factory()->create();

    Gate::shouldReceive('allows')->with('update', $license)->andReturnFalse();

    Livewire::test(DrivingLicenseManager::class)
        ->call('edit', $license);

    Event::assertDispatched(DrivingLicenseEditRequested::class);
});

it('can update a driving license', function () {
    $license = DrivingLicense::factory()->create(['category' => 'A']);

    Livewire::test(DrivingLicenseManager::class)
        ->call('edit', $license)
        ->set('category', 'AM')
        ->call('update');

    $license->refresh();
    expect($license->category)->toBe('AM');
});

it('can delete a driving license if authorized', function () {
    $license = DrivingLicense::factory()->create();

    Gate::shouldReceive('allows')->with('delete', $license)->andReturnTrue();

    Livewire::test(DrivingLicenseManager::class)
        ->call('delete', $license);

    expect(DrivingLicense::find($license->id))->toBeNull();
});

it('dispatches delete request if not authorized', function () {
    Event::fake();

    $license = DrivingLicense::factory()->create();

    Gate::shouldReceive('allows')->with('delete', $license)->andReturnFalse();

    Livewire::test(DrivingLicenseManager::class)
        ->call('delete', $license);

    Event::assertDispatched(DrivingLicenseEditRequested::class);
});

test('confirmDelete dispatches DeleteAlert event', function () {
    loginAs('god');
    $drivingLicense = DrivingLicense::factory()->create();

    Livewire::test(DrivingLicenseManager::class)
        ->call('confirmDelete', $drivingLicense->id)
        ->assertDispatched('DeleteAlert', $drivingLicense->id);
});
