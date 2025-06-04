<?php

use App\Models\DrivingLicense;
use App\Models\EditRequest;
use App\Models\User;
use App\Policies\DrivingLicensePolicy;

beforeEach(function () {
    $this->policy = new DrivingLicensePolicy();
});

it('allows god role to update or delete any driving license', function () {
    $user = User::factory()->create();
    $user->assignRole('god');

    $license = DrivingLicense::factory()->create(['only_god' => true]);

    expect($this->policy->update($user, $license))->toBeTrue()
        ->and($this->policy->delete($user, $license))->toBeTrue();
});

it('allows moderator with approved edit request to update or delete only_god license', function () {
    $user = User::factory()->create();
    $user->assignRole('moderator');

    $license = DrivingLicense::factory()->create(['only_god' => true]);

    expect($this->policy->update($user, $license))->toBeFalse()
        ->and($this->policy->delete($user, $license))->toBeFalse();

    EditRequest::factory()->create([
        'driving_license_id' => $license->id,
        'requested_by' => $user->id,
        'approved' => true,
    ]);

    expect($this->policy->update($user, $license))->toBeTrue()
        ->and($this->policy->delete($user, $license))->toBeTrue();
});

it('denies update or delete if only_god is true and user has no role or approval', function () {
    $user = User::factory()->create();

    $license = DrivingLicense::factory()->create(['only_god' => true]);

    expect($this->policy->update($user, $license))->toBeFalse()
        ->and($this->policy->delete($user, $license))->toBeFalse();
});

it('allows moderator or god role to update or delete if only_god is false', function () {
    $god = User::factory()->create();
    $god->assignRole('god');

    $moderator = User::factory()->create();
    $moderator->assignRole('moderator');

    $license = DrivingLicense::factory()->create(['only_god' => false]);

    expect($this->policy->update($god, $license))->toBeTrue()
        ->and($this->policy->delete($god, $license))->toBeTrue()
        ->and($this->policy->update($moderator, $license))->toBeTrue()
        ->and($this->policy->delete($moderator, $license))->toBeTrue();

});

it('denies update or delete if only_god is false and user has no role', function () {
    $user = User::factory()->create();

    $license = DrivingLicense::factory()->create(['only_god' => false]);

    expect($this->policy->update($user, $license))->toBeFalse()
        ->and($this->policy->delete($user, $license))->toBeFalse();
});
