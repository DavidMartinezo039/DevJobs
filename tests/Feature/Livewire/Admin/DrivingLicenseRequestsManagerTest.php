<?php

use App\Livewire\Admin\DrivingLicenseRequestsManager;
use App\Models\DrivingLicense;
use App\Models\EditRequest;
use App\Models\User;
use Livewire\Livewire;

use function Pest\Laravel\assertDatabaseHas;

beforeEach(function () {
    $this->admin = User::factory()->create();
    $this->actingAs($this->admin);
});

it('approves a request', function () {
    $request = EditRequest::factory()->notApproved()->create([
        'approved' => null,
    ]);

    Livewire::test(DrivingLicenseRequestsManager::class)
        ->call('approve', $request->fresh());

    assertDatabaseHas('edit_requests', [
        'id' => $request->id,
        'approved' => true,
    ]);
});

it('rejects a request', function () {
    $request = EditRequest::factory()->approved()->create([
        'approved' => null,
    ]);

    Livewire::test(DrivingLicenseRequestsManager::class)
        ->call('reject', $request->fresh());

    assertDatabaseHas('edit_requests', [
        'id' => $request->id,
        'approved' => false,
    ]);
});

it('shows only unapproved requests', function () {
    $pending = EditRequest::factory()->count(2)->create(['approved' => null]);
    EditRequest::factory()->create(['approved' => true]);
    EditRequest::factory()->create(['approved' => false]);

    Livewire::test(DrivingLicenseRequestsManager::class)
        ->assertViewHas('requests', fn ($requests) =>
            $requests->count() === 2 &&
            $requests->every(fn ($r) => $r->approved === null)
        );
});
