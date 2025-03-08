<?php

namespace Database\Seeders;

use App\Models\Salary;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SalarySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Salary::factory()->create([
            'salary' => '$0 - $499'
        ]);
        Salary::factory()->create([
            'salary' => '$500 - $749'
        ]);
        Salary::factory()->create([
            'salary' => '$750 - $999'
        ]);
        Salary::factory()->create([
            'salary' => '$1000 - $1499'
        ]);
        Salary::factory()->create([
            'salary' => '$1500 - $1999'
        ]);
        Salary::factory()->create([
            'salary' => '$2000 - $2499'
        ]);
        Salary::factory()->create([
            'salary' => '$2500 - $2999'
        ]);
        Salary::factory()->create([
            'salary' => '$3000 - $4999'
        ]);
        Salary::factory()->create([
            'salary' => '+$5000'
        ]);
    }
}
