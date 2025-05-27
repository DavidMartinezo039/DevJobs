<?php

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "pest()" function to bind a different classes or traits.
|
*/

use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

uses(TestCase::class, LazilyRefreshDatabase::class)->in('Feature', 'Unit');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

use App\Models\{
    User, CV, WorkExperience, Language, DigitalSkill, Education, DrivingLicense, PersonalData, Gender, Identity, Phone, SocialMedia
};
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use function Pest\Laravel\actingAs;

function createUserWithCompleteCv(string $roleName = 'developer'): User
{
    $user = User::factory()->create();
    $user->assignRole($roleName);

    CV::factory(1)->create(['user_id' => $user->id])->each(function ($cv) {

        WorkExperience::factory(2)->create(['cv_id' => $cv->id]);
        Education::factory(2)->create(['cv_id' => $cv->id]);

        $languages = Language::factory(3)->create();
        $languages->each(fn($language) => $cv->languages()->attach($language->id, ['level' => 'Intermediate']));

        $digitalSkills = DigitalSkill::factory(3)->create();
        $digitalSkills->each(fn($skill) => $cv->digitalSkills()->attach($skill->id, ['level' => 'Intermediate']));

        $drivingLicense = DrivingLicense::factory()->create();
        $cv->drivingLicenses()->attach($drivingLicense->id);

        $newImageName = 'profile_' . Str::random(10) . '.png';
        Storage::disk('public')->copy('images/default/default.png', 'images/' . $newImageName);

        $personalData = PersonalData::factory()->create([
            'cv_id' => $cv->id,
            'image' => $newImageName,
        ]);

        $genderId = Gender::inRandomOrder()->value('id');
        $personalData->gender_id = $genderId;
        $personalData->save();

        $identityIds = Identity::inRandomOrder()->limit(2)->pluck('id');
        foreach ($identityIds as $identityId) {
            $personalData->identities()->attach($identityId, [
                'number' => 'ID-' . rand(1000, 9999),
            ]);
        }

        $phoneIds = Phone::inRandomOrder()->limit(2)->pluck('id');
        foreach ($phoneIds as $phoneId) {
            $personalData->phones()->attach($phoneId, [
                'number' => '555-123-' . rand(1000, 9999),
            ]);
        }

        $socialMediaIds = SocialMedia::inRandomOrder()->limit(2)->pluck('id');
        foreach ($socialMediaIds as $socialMediaId) {
            $personalData->socialMedia()->attach($socialMediaId, [
                'user_name' => 'user' . rand(1, 100),
                'url' => 'https://socialmedia.com/user' . rand(1, 100),
            ]);
        }
    });

    return $user;
}

function loginAs(string $role = 'god'): User {
    $user = User::factory()->create();
    $user->assignRole([$role]);
    actingAs($user, 'sanctum');
    return $user;
}

pest()->beforeEach(function () {
    Permission::create(['name' => 'view vacancies']);
    Permission::create(['name' => 'create vacancies']);
    Permission::create(['name' => 'view cvs']);
    Permission::create(['name' => 'create cvs']);
    Permission::create(['name' => 'vacancies applied']);
    Permission::create(['name' => 'apply for vacancy']);

    $developer = Role::create(['name' => 'developer']);
    $recruiter = Role::create(['name' => 'recruiter']);
    $moderator = Role::create(['name' => 'moderator']);
    $god = Role::create(['name' => 'god']);

    $developer->givePermissionTo([
        'view cvs',
        'create cvs',
        'vacancies applied',
        'apply for vacancy',
    ]);

    $recruiter->givePermissionTo([
        'view vacancies',
        'create vacancies',
    ]);

    $moderator->givePermissionTo([
        'view vacancies',
        'create vacancies',
        'view cvs',
        'create cvs',
        'vacancies applied',
        'apply for vacancy',
    ]);

    $god->givePermissionTo([
        'view vacancies',
        'create vacancies',
        'view cvs',
        'create cvs',
        'vacancies applied',
        'apply for vacancy',
    ]);
});

function something()
{
    // ..
}
