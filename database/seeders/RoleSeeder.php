<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::create(['name' => 'view vacancies']);
        Permission::create(['name' => 'create vacancies']);
        Permission::create(['name' => 'view cvs']);
        Permission::create(['name' => 'create cvs']);

        Permission::create(['name' => 'manage users']);
        Permission::create(['name' => 'change roles']);

        $developer = Role::create(['name' => 'developer']);
        $recruiter = Role::create(['name' => 'recruiter']);
        $moderator = Role::create(['name' => 'moderator']);
        $admin = Role::create(['name' => 'admin']);
        $god = Role::create(['name' => 'god']);

        $developer->givePermissionTo([
            'view cvs',
            'create cvs',
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
        ]);

        $admin->givePermissionTo([
            'manage users',
            'change roles'
        ]);

        $god->givePermissionTo([
            'view vacancies',
            'create vacancies',
            'view cvs',
            'create cvs',
            'manage users',
            'change roles'
        ]);
    }
}
