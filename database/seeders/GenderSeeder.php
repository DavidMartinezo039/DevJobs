<?php

namespace Database\Seeders;

use App\Models\Gender;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GenderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $genders = ['Masculino', 'Femenino', 'Otro', 'Prefiero no decirlo'];

        foreach ($genders as $gender) {
            Gender::factory()->create([
                'type' => $gender
            ]);
        }
    }
}
