<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $developer = User::factory()->create([
            'name' => 'developer',
            'email' => 'developer@example.com',
            'password' => bcrypt('123'),
            'wants_marketing' => true,
        ]);

        $recruiter = User::factory()->create([
            'name' => 'recruiter',
            'email' => 'recruiter@example.com',
            'password' => bcrypt('123'),
        ]);

        $moderator = User::factory()->create([
            'name' => 'moderator',
            'email' => 'moderator@example.com',
            'password' => bcrypt('123'),
        ]);

        $admin = User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('123'),
        ]);

        $god = User::factory()->create([
            'name' => 'god',
            'email' => 'god@example.com',
            'password' => bcrypt('123'),
        ]);

        $developer->assignRole('developer');
        $recruiter->assignRole('recruiter');
        $moderator->assignRole('moderator');
        $admin->assignRole('admin');
        $god->assignRole('god');
    }
}
