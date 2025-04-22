<?php

namespace Database\Seeders;

use App\Models\Identity;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class IdentitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $identities = ['DNI', 'Pasaporte', 'Permiso de residencia'];

        foreach ($identities as $identity) {
            Identity::factory()->create([
                'type' => $identity,
            ]);
        }
    }
}
