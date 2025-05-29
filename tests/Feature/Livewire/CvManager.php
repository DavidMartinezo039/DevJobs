<?php

use App\Http\Requests\CvCreateRequest;
use App\Livewire\CvManager;

test('addSection adds section and initializes entry', function () {
    $cv = new CvManager();

    $cv->addSection('work_experience');

    expect($cv->activeSections)->toContain('work_experience')
        ->and($cv->workExperiences)->toHaveCount(1)
        ->and($cv->workExperiences[0])->toBe([
            'company' => '',
            'position' => '',
            'start' => '',
            'end' => '',
            'description' => '',
        ]);
});

test('addSection does not add duplicate sections', function () {
    $cv = new CvManager();

    $cv->addSection('education');
    $cv->addSection('education');

    expect($cv->activeSections)->toHaveCount(1)
        ->and($cv->educations)->toHaveCount(1);
});

test('removeSection removes section and clears related entries', function ($section, $property) {
    $cv = new CvManager();

    $cv->addSection($section);
    $cv->addEntry($section);

    expect($cv->activeSections)->toContain($section)
        ->and(count($cv->{$property}))->toBeGreaterThan(0);

    $cv->removeSection($section);

    expect($cv->activeSections)->not->toContain($section)
        ->and($cv->{$property})->toBeEmpty();
})->with([
    ['work_experience', 'workExperiences'],
    ['education', 'educations'],
    ['languages', 'languages'],
    ['skills', 'skills'],
    ['driving_licenses', 'drivingLicenses'],
]);

test('addEntry adds empty entry to correct section array', function ($section, $expectedKeys) {
    $cv = new CvManager();

    $cv->addEntry($section);

    $property = match ($section) {
        'work_experience' => 'workExperiences',
        'education' => 'educations',
        'languages' => 'languages',
        'skills' => 'skills',
        'driving_licenses' => 'drivingLicenses',
        default => null,
    };

    expect($cv->{$property})->toHaveCount(1);

    $entry = $cv->{$property}[0];

    foreach ($expectedKeys as $key) {
        expect(array_key_exists($key, $entry))->toBeTrue();
    }
})->with([
    ['work_experience', ['company', 'position', 'start', 'end', 'description']],
    ['education', ['school', 'degree', 'start', 'end', 'description', 'city', 'country']],
    ['languages', ['language_id', 'level']],
    ['skills', ['digital_skill_id', 'level']],
    ['driving_licenses', ['driving_license_id']],
]);

test('removeEntry removes entry by index from correct section array', function ($section, $property) {
    $cv = new CvManager();

    $cv->addEntry($section);
    $cv->addEntry($section);

    expect(count($cv->{$property}))->toBe(2);

    $cv->removeEntry($section, 0);

    expect(count($cv->{$property}))->toBe(1);
})->with([
    ['work_experience', 'workExperiences'],
    ['education', 'educations'],
    ['languages', 'languages'],
    ['skills', 'skills'],
    ['driving_licenses', 'drivingLicenses'],
]);


use App\Models\DigitalSkill;
use App\Models\DrivingLicense;
use App\Models\Gender;
use App\Models\Identity;
use App\Models\Language;
use App\Models\Phone;
use App\Models\SocialMedia;
use App\Models\User;
use App\Models\CV;
use App\Models\PersonalData;
use App\Models\WorkExperience;
use App\Models\Education;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;

test('it creates a complete CV via store method', function () {
    // Preparamos almacenamiento falso para subir imagen
    Storage::fake('public');

    Gender::factory()->create(['id' => 1]);
    Identity::factory()->count(2)->create(); // o con IDs 1 y 2
    Phone::factory()->count(2)->create();
    SocialMedia::factory()->count(2)->create();
    Language::factory()->create(['id' => 1]);
    DigitalSkill::factory()->create(['id' => 1]);
    DrivingLicense::factory()->create(['id' => 1]);


    // Creamos usuario y nos logueamos
    $user = User::factory()->create();
    $user->assignRole('developer');
    actingAs($user);

    // Datos que se usarán para crear el CV
    $data = [
        'title' => 'Mi CV de prueba',
        'first_name' => 'Juan',
        'last_name' => 'Pérez',
        'birth_date' => '1990-01-01',
        'city' => 'Madrid',
        'country' => 'España',
        'about_me' => 'Soy un desarrollador.',
        'emails' => ['juan@example.com'],
        'addresses' => ['Calle Falsa 123'],
        'workPermits' => ['Permiso A'],
        'nationalities' => ['Española'],
        'gender_id' => 1, // Asume que existe género con ID=1
        'image' => UploadedFile::fake()->image('profile.png'),

        // Relaciones many to many y pivotes
        'identity_documents' => [
            ['identity_id' => 1, 'number' => '12345678A'],
            ['identity_id' => 2, 'number' => '87654321B'],
        ],

        'phones' => [
            ['phone_id' => 1, 'number' => '555-1234'],
            ['phone_id' => 2, 'number' => '555-5678'],
        ],

        'socialMedia' => [
            ['social_media_id' => 1, 'user_name' => 'juanp', 'url' => 'https://twitter.com/juanp'],
            ['social_media_id' => 2, 'user_name' => 'juanp2', 'url' => 'https://facebook.com/juanp2'],
        ],

        'workExperiences' => [
            [
                'company' => 'Empresa 1',
                'position' => 'Desarrollador',
                'start' => '2010-01-01',
                'end' => '2015-01-01',
                'description' => 'Trabajé en desarrollo web.',
            ],
        ],

        'educations' => [
            [
                'school' => 'Universidad X',
                'degree' => 'Grado en Informática',
                'start' => '2005-09-01',
                'end' => '2009-06-01',
                'city' => 'Madrid',
                'country' => 'España',
            ],
        ],

        'languages' => [
            ['language_id' => 1, 'level' => 'B2'],
        ],

        'skills' => [
            ['digital_skill_id' => 1, 'level' => 'Avanzado'],
        ],

        'drivingLicenses' => [
            ['driving_license_id' => 1],
        ],
    ];

    // Ejecutamos el componente Livewire y llamamos a store()
    Livewire::test(CvManager::class)
        ->set($data)
        ->call('store')
        ->assertSet('view', 'index')
        ->assertHasNoErrors();

    $cv = CV::where('user_id', $user->id)->first();

    expect($cv)->not()->toBeNull()
        ->and($cv->title)->toBe('Mi CV de prueba');

    // Comprobamos personal data
    $personalData = PersonalData::where('cv_id', $cv->id)->first();
    expect($personalData)->not()->toBeNull()
        ->and($personalData->first_name)->toBe('Juan')
        ->and($personalData->image)->not()->toBeNull()
        ->and($cv->workExperiences()->count())->toBe(1)
        ->and($cv->workExperiences()->first()->company_name)->toBe('Empresa 1')
        ->and($cv->education()->count())->toBe(1)
        ->and($cv->education()->first()->institution)->toBe('Universidad X')
        ->and($cv->languages()->count())->toBe(1)
        ->and($cv->languages()->first()->pivot->level)->toBe('B2')
        ->and($cv->digitalSkills()->count())->toBe(1)
        ->and($cv->digitalSkills()->first()->pivot->level)->toBe('Avanzado')
        ->and($cv->drivingLicenses()->count())->toBe(1);
});

it('validates required fields in CvCreateRequest', function () {
    $request = new CvCreateRequest();

    // Datos sin required para provocar errores
    $data = [];

    $validator = Validator::make($data, $request->rules());

    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has('title'))->toBeTrue()
        ->and($validator->errors()->has('first_name'))->toBeTrue()
        ->and($validator->errors()->has('last_name'))->toBeTrue()
        ->and($validator->errors()->has('about_me'))->toBeTrue();
});

it('passes validation with correct data', function () {
    Storage::fake('public');

    Gender::factory()->create(['id' => 1]);
    Identity::factory()->count(2)->create(); // o con IDs 1 y 2
    Phone::factory()->count(2)->create();
    SocialMedia::factory()->count(2)->create();
    Language::factory()->create(['id' => 1]);
    DigitalSkill::factory()->create(['id' => 1]);
    DrivingLicense::factory()->create(['id' => 1]);
    $request = new CvCreateRequest();

    $data = [
        'title' => 'Mi CV de prueba',
        'first_name' => 'Juan',
        'last_name' => 'Pérez',
        'birth_date' => '1990-01-01',
        'city' => 'Madrid',
        'country' => 'España',
        'about_me' => 'Soy un desarrollador.',
        'emails' => ['juan@example.com'],
        'addresses' => ['Calle Falsa 123'],
        'workPermits' => ['Permiso A'],
        'nationalities' => ['Española'],
        'gender_id' => 1, // Asume que existe género con ID=1
        'image' => UploadedFile::fake()->image('profile.png'),

        // Relaciones many to many y pivotes
        'identity_documents' => [
            ['identity_id' => 1, 'number' => '12345678A'],
            ['identity_id' => 2, 'number' => '87654321B'],
        ],

        'phones' => [
            ['phone_id' => 1, 'number' => '555-1234'],
            ['phone_id' => 2, 'number' => '555-5678'],
        ],

        'socialMedia' => [
            ['social_media_id' => 1, 'user_name' => 'juanp', 'url' => 'https://twitter.com/juanp'],
            ['social_media_id' => 2, 'user_name' => 'juanp2', 'url' => 'https://facebook.com/juanp2'],
        ],

        'workExperiences' => [
            [
                'company' => 'Empresa 1',
                'position' => 'Desarrollador',
                'start' => '2010-01-01',
                'end' => '2015-01-01',
                'description' => 'Trabajé en desarrollo web.',
            ],
        ],

        'educations' => [
            [
                'school' => 'Universidad X',
                'degree' => 'Grado en Informática',
                'start' => '2005-09-01',
                'end' => '2009-06-01',
                'city' => 'Madrid',
                'country' => 'España',
            ],
        ],

        'languages' => [
            ['language_id' => 1, 'level' => 'B2'],
        ],

        'skills' => [
            ['digital_skill_id' => 1, 'level' => 'Avanzado'],
        ],

        'drivingLicenses' => [
            ['driving_license_id' => 1],
        ],
    ];

    $validator = Validator::make($data, $request->rules());

    expect($validator->fails())->toBeFalse();
});
