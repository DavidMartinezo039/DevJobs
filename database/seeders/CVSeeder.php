<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{User, CV, WorkExperience, Language, DigitalSkill, Education, DrivingLicense, PersonalData, Identity, Phone, SocialMedia};

class CVSeeder extends Seeder
{
    public function run(): void
    {
        // Obtén el usuario con id 1 (suponiendo que ya existe)
        $user = User::find(1);

        if (!$user) {
            // Si el usuario con id 1 no existe, puedes lanzar un error o registrar un mensaje
            throw new \Exception("El usuario con ID 1 no existe.");
        }

        // Crea 3 CVs asociados al usuario
        CV::factory(3)->create(['user_id' => $user->id])->each(function ($cv) {
            // Crea 2 experiencias laborales para cada CV
            WorkExperience::factory(2)->create(['cv_id' => $cv->id]);

            // Crea 2 estudios para cada CV
            Education::factory(2)->create(['cv_id' => $cv->id]);

            // Crea 3 idiomas y asocia a cada CV con el nivel
            $languages = Language::factory(3)->create();
            $languages->each(function ($language) use ($cv) {
                $cv->languages()->attach($language->id, ['level' => 'Intermediate']);
            });

            // Crea 3 habilidades digitales y asocia a cada CV
            $digitalSkills = DigitalSkill::factory(3)->create();
            $cv->digitalSkills()->attach($digitalSkills->pluck('id'));

            // Crea una licencia de conducir y asocia a cada CV
            $drivingLicense = DrivingLicense::factory()->create();
            $cv->drivingLicenses()->attach($drivingLicense->id);

            // Crea los datos personales asociados al CV
            $personalData = PersonalData::factory()->create(['cv_id' => $cv->id]);

            // Crear identidades relacionadas (pueden ser múltiples)
            Identity::factory(2)->create()->each(function ($identity) use ($personalData) {
                $personalData->identities()->attach($identity->id, ['identity_number' => 'ID-' . rand(1000, 9999)]);
            });

            // Crear teléfonos relacionados (pueden ser múltiples)
            Phone::factory(2)->create()->each(function ($phone) use ($personalData) {
                $personalData->phones()->attach($phone->id, ['number' => '555-123-' . rand(1000, 9999)]);
            });

            // Crear redes sociales relacionadas (pueden ser múltiples)
            SocialMedia::factory(2)->create()->each(function ($socialMedia) use ($personalData) {
                $personalData->socialMedia()->attach($socialMedia->id, ['user_name' => 'user' . rand(1, 100), 'url' => 'https://socialmedia.com/user' . rand(1, 100)]);
            });
        });
    }
}
