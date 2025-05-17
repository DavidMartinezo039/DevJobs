<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\{
    User, CV, WorkExperience, Language, DigitalSkill, Education, DrivingLicense, PersonalData, Gender, Identity, Phone, SocialMedia
};

class CVSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::whereIn('id', [1, 3])->get();

        foreach ($users as $user) {
            CV::factory(3)->create(['user_id' => $user->id])->each(function ($cv) {

                WorkExperience::factory(2)->create(['cv_id' => $cv->id]);
                Education::factory(2)->create(['cv_id' => $cv->id]);

                $languages = Language::factory(3)->create();
                $languages->each(function ($language) use ($cv) {
                    $cv->languages()->attach($language->id, ['level' => 'Intermediate']);
                });

                $digitalSkills = DigitalSkill::factory(3)->create();
                $digitalSkills->each(function ($skill) use ($cv) {
                    $cv->digitalSkills()->attach($skill->id, ['level' => 'Intermediate']);
                });

                $drivingLicense = DrivingLicense::factory()->create();
                $cv->drivingLicenses()->attach($drivingLicense->id);

                $newImageName = 'profile_' . Str::random(10) . '.png';
                Storage::disk('public')->copy('images/default/default.png', 'images/' . $newImageName);

                $personalData = PersonalData::factory()->create([
                    'cv_id' => $cv->id,
                    'image' => $newImageName,
                ]);

                $genderIds = Gender::inRandomOrder()->limit(1)->pluck('id');
                $personalData->gender_id = $genderIds[0];
                $personalData->save();

                $identityIds = Identity::inRandomOrder()->limit(2)->pluck('id');
                foreach ($identityIds as $identityId) {
                    $personalData->identities()->attach($identityId, [
                        'number' => 'ID-' . rand(1000, 9999),
                    ]);
                }

                $phoneIds = Phone::inRandomOrder()->limit(2)->pluck('id');
                foreach ($phoneIds as $phoneId) {
                    $personalData->phones()->attach($phoneId, [
                        'number' => '555-123-' . rand(1000, 9999),
                    ]);
                }

                $socialMediaIds = SocialMedia::inRandomOrder()->limit(2)->pluck('id');
                foreach ($socialMediaIds as $socialMediaId) {
                    $personalData->socialMedia()->attach($socialMediaId, [
                        'user_name' => 'user' . rand(1, 100),
                        'url' => 'https://socialmedia.com/user' . rand(1, 100),
                    ]);
                }
            });
        }
    }
}
