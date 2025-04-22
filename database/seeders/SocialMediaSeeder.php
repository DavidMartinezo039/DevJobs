<?php

namespace Database\Seeders;

use App\Models\SocialMedia;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SocialMediaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $socialMedias = ['LinkedIn', 'GitHub', 'Twitter'];

        foreach ($socialMedias as $socialMedia) {
            SocialMedia::factory()->create([
                'type' => $socialMedia,
            ]);
        }
    }
}
