<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::factory()->create([
            'category' => 'Backend Developer'
        ]);
        Category::factory()->create([
            'category' => 'Front end Developer'
        ]);
        Category::factory()->create([
            'category' => 'Mobile Developer'
        ]);
        Category::factory()->create([
            'category' => 'Techlead'
        ]);
        Category::factory()->create([
            'category' => 'UX / UI Design'
        ]);
        Category::factory()->create([
            'category' => 'Software Architecture'
        ]);
        Category::factory()->create([
            'category' => 'Devops'
        ]);
    }
}
