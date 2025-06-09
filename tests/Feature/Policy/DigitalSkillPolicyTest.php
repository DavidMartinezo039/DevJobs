<?php

use App\Models\User;
use App\Models\DigitalSkill;
use App\Policies\DigitalSkillPolicy;

beforeEach(function () {
    $this->policy = new DigitalSkillPolicy();
    $this->digitalSkill = DigitalSkill::factory()->create();
});

it('allows moderators to update digital skills', function () {
    $moderator = User::factory()->create();
    $moderator->assignRole('moderator');

    expect($this->policy->update($moderator, $this->digitalSkill))->toBeTrue();
});

it('allows gods to update digital skills', function () {
    $god = User::factory()->create();
    $god->assignRole('god');

    expect($this->policy->update($god, $this->digitalSkill))->toBeTrue();
});

it('denies regular users from updating digital skills', function () {
    $user = User::factory()->create();
    $user->assignRole('developer');

    expect($this->policy->update($user, $this->digitalSkill))->toBeFalse();
});

it('allows moderators to delete digital skills', function () {
    $moderator = User::factory()->create();
    $moderator->assignRole('moderator');

    expect($this->policy->delete($moderator, $this->digitalSkill))->toBeTrue();
});

it('allows gods to delete digital skills', function () {
    $god = User::factory()->create();
    $god->assignRole('god');

    expect($this->policy->delete($god, $this->digitalSkill))->toBeTrue();
});

it('denies regular users from deleting digital skills', function () {
    $user = User::factory()->create();
    $user->assignRole('developer');

    expect($this->policy->delete($user, $this->digitalSkill))->toBeFalse();
});

it('allows gods to restore a deleted digital skill', function () {
    $god = User::factory()->create();
    $god->assignRole('god');

    expect($this->policy->restore($god, $this->digitalSkill))->toBeTrue();
});
