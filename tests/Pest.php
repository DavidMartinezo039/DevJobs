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

pest()->beforeEach(function () {
    Permission::create(['name' => 'view vacancies']);
    Permission::create(['name' => 'create vacancies']);
    Permission::create(['name' => 'edit vacancies']);
    Permission::create(['name' => 'delete vacancies']);

    Permission::create(['name' => 'manage users']);
    Permission::create(['name' => 'change roles']);

    $developer = Role::create(['name' => 'developer']);
    $recruiter = Role::create(['name' => 'recruiter']);
    $moderator = Role::create(['name' => 'moderator']);
    $admin = Role::create(['name' => 'admin']);
    $god = Role::create(['name' => 'god']);

    $recruiter->givePermissionTo([
        'view vacancies',
        'create vacancies',
        'edit vacancies',
        'delete vacancies'
    ]);

    $moderator->givePermissionTo([
        'view vacancies',
        'create vacancies',
        'edit vacancies',
        'delete vacancies'
    ]);

    $admin->givePermissionTo([
        'view vacancies',
        'create vacancies',
        'edit vacancies',
        'delete vacancies',
        'manage users',
        'change roles'
    ]);

    $god->givePermissionTo([
        'view vacancies',
        'create vacancies',
        'edit vacancies',
        'delete vacancies',
        'manage users',
        'change roles'
    ]);
});

function something()
{
    // ..
}
