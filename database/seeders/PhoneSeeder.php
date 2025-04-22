<?php

namespace Database\Seeders;

use App\Models\Phone;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PhoneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $phones = ['Móvil', 'Trabajo', 'Domicilio'];

        foreach ($phones as $phone) {
            Phone::factory()->create([
                'type' => $phone
            ]);
        }
    }
}
