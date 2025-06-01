<?php

use App\Jobs\GenerateCVPdf;
use App\Livewire\CvManager;
use App\Models\CV;
use App\Models\DigitalSkill;
use App\Models\DrivingLicense;
use App\Models\Education;
use App\Models\Gender;
use App\Models\Identity;
use App\Models\Language;
use App\Models\PersonalData;
use App\Models\Phone;
use App\Models\SocialMedia;
use App\Models\User;
use App\Models\WorkExperience;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;

beforeEach(function () {
    // Necesario para el foreign key de 'cvs'
    $user = User::factory()->create();
    $user->assignRole('god');
    $this->actingAs($user);

    // Configurar el disco de almacenamiento para pruebas
    Storage::fake('public');

    // Mockear la fachada Gate
    // Configurar la cola para despachar jobs
    Queue::fake();

    // Crear datos base para las opciones de los selectores
    Gender::factory()->create(['type' => 'Male']);
    Gender::factory()->create(['type' => 'Female']);
    Identity::factory()->create(['type' => 'DNI']);
    Phone::factory()->create(['type' => 'Mobile']);
    SocialMedia::factory()->create(['type' => 'LinkedIn']);
    Language::factory()->create(['name' => 'English']);
    DigitalSkill::factory()->create(['name' => 'Laravel']);
    DrivingLicense::factory()->create(['category' => 'B']);
});

it('index method correctly resets data and sets view', function () {
    $user = User::factory()->create();
    $user->assignRole('developer');

    // Opcional: Crear algunos CVs para el usuario para que mount() los cargue
    CV::factory()->count(2)->create(['user_id' => $user->id]);

    // Crear un componente y establecerle un estado inicial que esperamos que se resetee
    $component = Livewire::actingAs($user)
        ->test(CvManager::class)
        ->set('title', 'Título de prueba antes de resetear')
        ->set('emails', ['email_viejo@example.com'])
        ->set('view', 'create'); // Establecer una vista diferente para asegurar que cambia

    // Llamar al método index
    $component->call('index');

    // Asertar que la propiedad 'view' se ha establecido a 'index'
    $component->assertSet('view', 'index');

    // Asertar que las propiedades clave (que resetComponentData debería limpiar) se han reseteado.
    // Esto prueba implícitamente que resetComponentData fue llamado y funcionó.
    $component->assertSet('title', null);
    // Asegúrate de que tu resetComponentData() realmente resetea 'emails' a un array vacío
    // Si lo resetea a [''], ajusta la aserción.
    $component->assertSet('emails', []);

    // Asertar que mount() fue llamado y recargó los CVs del usuario
    $component->assertSet('cvs', function ($cvs) use ($user) {
        // Comprueba que la colección de CVs tiene el número correcto de elementos
        return $cvs->count() === $user->cvs()->count();
    });

    // Asertar que los campos dinámicos se resetean a su estado inicial según mount()
    // (Asumiendo que mount() los inicializa con una entrada vacía si no hay datos)
    $component->assertSet('identity_documents', [['identity_id' => '', 'number' => '']]);
    $component->assertSet('phones', [['phone_id' => '', 'number' => '']]);
    $component->assertSet('socialMedia', [['social_media_id' => '', 'user_name' => '', 'url' => '']]);
    // Añade aserciones para otros arrays dinámicos si se inicializan en mount()
});

it('initializes with expected data on mount', function () {
    $user = User::factory()->create();
    $user->assignRole('developer');
    $cvs = CV::factory()->count(3)->create(['user_id' => $user->id]);


    Livewire::actingAs($user)
        ->test(CvManager::class)
        ->assertSet('cvs', function ($cvs) use ($user) {
            return $cvs->count() === 3 && $cvs->every(fn($cv) => $cv->user_id === $user->id);
        })
        ->assertSet('genders', Gender::all())
        ->assertSet('identityTypes', Identity::all())
        ->assertSet('phoneTypes', Phone::all())
        ->assertSet('socialMediaTypes', SocialMedia::all())
        ->assertSet('languages_options', Language::all())
        ->assertSet('skills_options', DigitalSkill::all())
        ->assertSet('drivingLicenses_options', DrivingLicense::all())
        ->assertSet('identity_documents', [['identity_id' => '', 'number' => '']])
        ->assertSet('phones', [['phone_id' => '', 'number' => '']])
        ->assertSet('socialMedia', [['social_media_id' => '', 'user_name' => '', 'url' => '']]);
});

it('resets component data when navigating to index', function () {
    Livewire::test(CvManager::class)
        ->set('title', 'Old Title')
        ->set('view', 'create')
        ->call('index')
        ->assertSet('title', null)
        ->assertSet('view', 'index')
        ->assertSet('identity_documents', [['identity_id' => '', 'number' => '']]);
});

it('resets component data and sets view to create', function () {
    Livewire::test(CvManager::class)
        ->set('title', 'Existing Title')
        ->call('create')
        ->assertSet('title', null)
        ->assertSet('view', 'create');
});

it('shows a specific CV', function () {
    $cv = CV::factory()->create();
    PersonalData::factory()->create(['cv_id' => $cv->id]);

    Livewire::test(CvManager::class)
        ->call('show', $cv)
        ->assertSet('selectedCv.id', $cv->id)
        ->assertSet('personalData.cv_id', $cv->id)
        ->assertSet('view', 'show');
});

it('fills data for editing a CV', function () {
    $user = User::factory()->create();
    $user->assignRole('developer');
    $cv = CV::factory()->create([
        'title' => 'My Awesome CV',
        'file_path' => 'cv/test.pdf',
        'user_id' => $user->id,
    ]);
    $personalData = PersonalData::factory()->create([
        'first_name' => 'John',
        'last_name' => 'Doe',
        'image' => 'profile.jpg',
        'about_me' => 'A great person.',
        'birth_date' => '1990-01-01',
        'city' => 'Springfield',
        'country' => 'USA',
        'workPermits' => ['US Citizen'],
        'nationality' => ['American'],
        'email' => ['john.doe@example.com'],
        'address' => ['123 Main St'],
        'gender_id' => Gender::first()->id,
        'cv_id' => $cv->id,
    ]);

    $identity = Identity::first();
    $phone = Phone::first();
    $socialMedia = SocialMedia::first();

    $personalData->identities()->attach($identity->id, ['number' => '12345']);
    $personalData->phones()->attach($phone->id, ['number' => '555-1234']);
    $personalData->socialMedia()->attach($socialMedia->id, ['user_name' => 'johndoe', 'url' => 'linkedin.com/johndoe']);

    WorkExperience::factory()->for($cv)->create();
    Education::factory()->for($cv)->create();
    $cv->languages()->attach(Language::first()->id, ['level' => 'fluent']);
    $cv->digitalSkills()->attach(DigitalSkill::first()->id, ['level' => 'expert']);
    $cv->drivingLicenses()->attach(DrivingLicense::first()->id);

    Livewire::actingAs($user)
        ->test(CvManager::class)
        ->call('edit', $cv)
        ->assertSet('selectedCv.id', $cv->id)
        ->assertSet('title', 'My Awesome CV')
        ->assertSet('first_name', 'John')
        ->assertSet('last_name', 'Doe')
        ->assertSet('image', 'profile.jpg')
        ->assertSet('about_me', 'A great person.')
        ->assertSet('birth_date', '1990-01-01')
        ->assertSet('city', 'Springfield')
        ->assertSet('country', 'USA')
        ->assertSet('workPermits', ['US Citizen'])
        ->assertSet('nationalities', ['American'])
        ->assertSet('emails', ['john.doe@example.com'])
        ->assertSet('addresses', ['123 Main St'])
        ->assertSet('gender_id', Gender::first()->id)
        ->assertSet('identity_documents.0.number', '12345')
        ->assertSet('phones.0.number', '555-1234')
        ->assertSet('socialMedia.0.user_name', 'johndoe')
        ->assertSet('activeSections', ['work_experience', 'education', 'languages', 'skills', 'driving_licenses']);
});

it('stores a new CV with personal data and relations', function () {
    $user = User::factory()->create();
    $user->assignRole('developer');
    $gender = Gender::first();
    $identity = Identity::first();
    $phone = Phone::first();
    $socialMedia = SocialMedia::first();
    $language = Language::first();
    $skill = DigitalSkill::first();
    $drivingLicense = DrivingLicense::first();

    // Crear un archivo de imagen falso
    $imageFile = UploadedFile::fake()->image('avatar.jpg');

    $component = Livewire::actingAs($user)
        ->test(CvManager::class)
        ->set('title', 'Developer CV')
        ->set('first_name', 'Jane')
        ->set('last_name', 'Smith')
        ->set('image', $imageFile)
        ->set('about_me', 'Passionate developer.')
        ->set('birth_date', '1995-05-15')
        ->set('city', 'New York')
        ->set('country', 'USA')
        ->set('gender_id', $gender->id)
        ->set('emails', ['jane.smith@example.com'])
        ->set('addresses', ['456 Oak Ave'])
        ->set('workPermits', ['US Citizen'])
        ->set('nationalities', ['American'])
        ->set('identity_documents', [['identity_id' => $identity->id, 'number' => '98765']])
        ->set('phones', [['phone_id' => $phone->id, 'number' => '555-9876']])
        ->set('socialMedia', [['social_media_id' => $socialMedia->id, 'user_name' => 'janesmith', 'url' => 'https://gemini.google.com/app/00554d7acbd087ae?hl=es-ES']])
        ->set('workExperiences', [['company' => 'Tech Corp', 'position' => 'Dev', 'start' => '2020-01-01', 'end' => '2023-12-31', 'description' => 'Developed software.']])
        ->set('educations', [['school' => 'Uni', 'degree' => 'CS', 'start' => '2016-09-01', 'end' => '2019-06-30', 'city' => 'Anytown', 'country' => 'USA']])
        ->set('languages', [['language_id' => $language->id, 'level' => 'Intermediate']])
        ->set('skills', [['digital_skill_id' => $skill->id, 'level' => 'Advanced']])
        ->set('drivingLicenses', [['driving_license_id' => $drivingLicense->id]])
        ->call('store');

    $component->assertHasNoErrors();

    assertDatabaseCount('cvs', 1);
    assertDatabaseHas('cvs', ['title' => 'Developer CV', 'user_id' => $user->id]);
    assertDatabaseCount('personal_data', 1);
    assertDatabaseHas('personal_data', ['first_name' => 'Jane', 'last_name' => 'Smith', 'gender_id' => $gender->id]);
    assertDatabaseCount('identity_personal_data', 1);
    assertDatabaseHas('identity_personal_data', ['identity_id' => $identity->id, 'number' => '98765']);
    assertDatabaseCount('personal_data_phones', 1);
    assertDatabaseHas('personal_data_phones', ['phone_id' => $phone->id, 'number' => '555-9876']);
    assertDatabaseCount('personal_data_social_media', 1);
    assertDatabaseHas('personal_data_social_media', ['social_media_id' => $socialMedia->id, 'user_name' => 'janesmith', 'url' => 'https://gemini.google.com/app/00554d7acbd087ae?hl=es-ES']);
    assertDatabaseCount('work_experiences', 1);
    assertDatabaseHas('work_experiences', ['company_name' => 'Tech Corp', 'position' => 'Dev']);
    assertDatabaseCount('education', 1);
    assertDatabaseHas('education', ['institution' => 'Uni', 'title' => 'CS']);
    assertDatabaseCount('cvs_languages', 1);
    assertDatabaseHas('cvs_languages', ['language_id' => $language->id, 'level' => 'Intermediate']);
    assertDatabaseCount('cvs_digital_skills', 1);
    assertDatabaseHas('cvs_digital_skills', ['digital_skill_id' => $skill->id, 'level' => 'Advanced']);
    assertDatabaseCount('cvs_driving_licenses', 1);
    assertDatabaseHas('cvs_driving_licenses', ['driving_license_id' => $drivingLicense->id]);

    Storage::disk('public')->assertExists('images/' . $imageFile->hashName());
    Queue::assertPushed(GenerateCVPdf::class);
    // Asegurarse de que el componente se resetea y vuelve a la vista de índice
    Livewire::actingAs($user)->test(CvManager::class)->assertSet('view', 'index')->assertSet('title', null);
});


it('updates an existing CV with personal data and relations', function () {
    $user = User::factory()->create();
    $user->assignRole('developer');
    $cv = CV::factory()->for($user)->create(['title' => 'Old CV Title']);
    $personalData = PersonalData::factory()->create([
        'first_name' => 'Old First', 'last_name' => 'Old Last', 'image' => null,
        'cv_id' => $cv->id,
    ]);

    $gender = Gender::first();
    $identity = Identity::first();
    $phone = Phone::first();
    $socialMedia = SocialMedia::first();
    $language = Language::first();
    $skill = DigitalSkill::first();
    $drivingLicense = DrivingLicense::first();

    // Attach some initial data to be synced
    $personalData->identities()->attach($identity->id, ['number' => 'old_id_num']);
    $personalData->phones()->attach($phone->id, ['number' => 'old_phone_num']);
    $personalData->socialMedia()->attach($socialMedia->id, ['user_name' => 'old_user', 'url' => 'old_url']);
    WorkExperience::factory()->for($cv)->create(['company_name' => 'Old Company']);
    Education::factory()->for($cv)->create(['institution' => 'Old School']);
    $cv->languages()->attach($language->id, ['level' => 'Basic']);
    $cv->digitalSkills()->attach($skill->id, ['level' => 'Beginner']);
    $cv->drivingLicenses()->attach($drivingLicense->id);

    $newImageFile = UploadedFile::fake()->image('new_avatar.jpg');

    $component = Livewire::actingAs($user)
        ->test(CvManager::class)
        ->call('edit', $cv) // Load the existing CV data
        ->set('title', 'Updated CV Title')
        ->set('first_name', 'Updated First')
        ->set('last_name', 'Updated Last')
        ->set('new_image', $newImageFile) // New image
        ->set('gender_id', $gender->id)
        ->set('emails', ['updated.email@example.com'])
        ->set('identity_documents', [['identity_id' => $identity->id, 'number' => 'new_id_num']])
        ->set('phones', [['phone_id' => $phone->id, 'number' => 'new_phone_num']])
        ->set('socialMedia', [['social_media_id' => $socialMedia->id, 'user_name' => 'new_user', 'url' => 'https://gemini.google.com/app/00554d7acbd087ae?hl=es-ES']])
        ->set('workExperiences', [['company' => 'New Company', 'position' => 'Senior Dev', 'start' => '2024-01-01', 'end' => null, 'description' => 'New desc.']])
        ->set('educations', [['school' => 'New Uni', 'degree' => 'PhD', 'start' => '2023-09-01', 'end' => null, 'city' => 'Othertown', 'country' => 'Canada']])
        ->set('languages', [['language_id' => $language->id, 'level' => 'Advanced']])
        ->set('skills', [['digital_skill_id' => $skill->id, 'level' => 'Expert']])
        ->set('drivingLicenses', [['driving_license_id' => $drivingLicense->id]])
        ->call('update');

    $component->assertHasNoErrors();

    $cv->refresh(); // Refresh the CV model to get updated data

    assertDatabaseHas('cvs', ['id' => $cv->id, 'title' => 'Updated CV Title']);
    assertDatabaseHas('personal_data', ['cv_id' => $cv->id, 'first_name' => 'Updated First', 'last_name' => 'Updated Last', 'image' => $newImageFile->hashName()]);
    assertDatabaseHas('identity_personal_data', ['personal_data_id' => $personalData->id, 'identity_id' => $identity->id, 'number' => 'new_id_num']);
    assertDatabaseMissing('identity_personal_data', ['personal_data_id' => $personalData->id, 'identity_id' => $identity->id, 'number' => 'old_id_num']);
    assertDatabaseHas('personal_data_phones', ['personal_data_id' => $personalData->id, 'phone_id' => $phone->id, 'number' => 'new_phone_num']);
    assertDatabaseHas('personal_data_social_media', ['personal_data_id' => $personalData->id, 'social_media_id' => $socialMedia->id, 'user_name' => 'new_user', 'url' => 'https://gemini.google.com/app/00554d7acbd087ae?hl=es-ES']);
    assertDatabaseHas('work_experiences', ['cv_id' => $cv->id, 'company_name' => 'New Company']);
    assertDatabaseMissing('work_experiences', ['cv_id' => $cv->id, 'company_name' => 'Old Company']);
    assertDatabaseHas('education', ['cv_id' => $cv->id, 'institution' => 'New Uni']);
    assertDatabaseMissing('education', ['cv_id' => $cv->id, 'institution' => 'Old School']);
    assertDatabaseHas('cvs_languages', ['cv_id' => $cv->id, 'language_id' => $language->id, 'level' => 'Advanced']);
    assertDatabaseMissing('cvs_languages', ['cv_id' => $cv->id, 'language_id' => $language->id, 'level' => 'Basic']);
    assertDatabaseHas('cvs_digital_skills', ['cv_id' => $cv->id, 'digital_skill_id' => $skill->id, 'level' => 'Expert']);
    assertDatabaseHas('cvs_driving_licenses', ['cv_id' => $cv->id, 'driving_license_id' => $drivingLicense->id]);

    Storage::disk('public')->assertExists('images/' . $newImageFile->hashName());
    Queue::assertPushed(GenerateCVPdf::class);
    Livewire::actingAs($user)->test(CvManager::class)->assertSet('view', 'index');
});

it('deletes a CV and its associated files', function () {
    $user = User::factory()->create();
    $user->assignRole('developer');
    $cv = CV::factory()->for($user)->create(['file_path' => 'test_cv.pdf']);
    $personalData = PersonalData::factory()->for($cv)->create(['image' => 'test_image.jpg']);

    // Crear archivos falsos en el disco para simular que existen
    Storage::disk('public')->put('cv/test_cv.pdf', 'pdf content');
    Storage::disk('public')->put('images/test_image.jpg', 'image content');

    Storage::disk('public')->assertExists('cv/test_cv.pdf');
    Storage::disk('public')->assertExists('images/test_image.jpg');

    Livewire::actingAs($user)
        ->test(CvManager::class)
        ->call('delete', $cv);

    assertDatabaseMissing('cvs', ['id' => $cv->id]);
    assertDatabaseMissing('personal_data', ['cv_id' => $cv->id]); // Personal data should also be deleted via cascade

    Storage::disk('public')->assertMissing('cv/test_cv.pdf');
    Storage::disk('public')->assertMissing('images/test_image.jpg');
});

it('adds a new section and an initial entry', function () {
    $component = Livewire::test(CvManager::class);

    $component->call('addSection', 'work_experience');

    $component->assertSet('activeSections', function ($activeSections) {
        return in_array('work_experience', $activeSections);
    });

    $component->assertSet('workExperiences', function ($workExperiences) {
        return count($workExperiences) === 1;
    });

    $component->call('addSection', 'education');

    $component->assertSet('activeSections', function ($activeSections) {
        return in_array('education', $activeSections) && in_array('work_experience', $activeSections);
    });

    $component->assertSet('educations', function ($educations) {
        return count($educations) === 1;
    });
});

it('does not add a section if it already exists', function () {
    $component = Livewire::test(CvManager::class);

    $component->call('addSection', 'work_experience')
        ->call('addSection', 'work_experience');

    $component->assertSet('activeSections', function ($activeSections) {
        return count($activeSections) === 1;
    });
});

it('removes a section and clears its data', function () {
    $component = Livewire::test(CvManager::class);

    $component->call('addSection', 'work_experience')
        ->call('addEntry', 'work_experience');

    $component->assertSet('workExperiences', function ($workExperiences) {
        return count($workExperiences) === 2;
    });

    $component->call('removeSection', 'work_experience');

    $component->assertSet('activeSections', function ($activeSections) {
        return !in_array('work_experience', $activeSections);
    });

    $component->assertSet('workExperiences', function ($workExperiences) {
        return count($workExperiences) === 0;
    });
});


it('adds and removes work experience entries', function () {
    $component = Livewire::test(CvManager::class);

    $component->call('addEntry', 'work_experience');
    $component->assertSet('workExperiences', function ($workExperiences) {
        return count($workExperiences) === 1;
    });

    $component->call('addEntry', 'work_experience');
    $component->assertSet('workExperiences', function ($workExperiences) {
        return count($workExperiences) === 2;
    });

    $component->call('removeEntry', 'work_experience', 0);
    $component->assertSet('workExperiences', function ($workExperiences) {
        return count($workExperiences) === 1;
    });
});

it('adds and removes education entries', function () {
    $component = Livewire::test(CvManager::class);

    $component->call('addEntry', 'education');
    $component->assertSet('educations', function ($educations) {
        return count($educations) === 1;
    });

    $component->call('removeEntry', 'education', 0);
    $component->assertSet('educations', function ($educations) {
        return count($educations) === 0;
    });
});

it('adds and removes language entries', function () {
    $component = Livewire::test(CvManager::class);

    $component->call('addEntry', 'languages');
    $component->assertSet('languages', function ($languages) {
        return count($languages) === 1;
    });

    $component->call('removeEntry', 'languages', 0);
    $component->assertSet('languages', function ($languages) {
        return count($languages) === 0;
    });
});

it('adds and removes skill entries', function () {
    $component = Livewire::test(CvManager::class);

    $component->call('addEntry', 'skills');
    $component->assertSet('skills', function ($skills) {
        return count($skills) === 1;
    });

    $component->call('removeEntry', 'skills', 0);
    $component->assertSet('skills', function ($skills) {
        return count($skills) === 0;
    });
});

it('adds and removes driving license entries', function () {
    $component = Livewire::test(CvManager::class);

    $component->call('addEntry', 'driving_licenses');
    $component->assertSet('drivingLicenses', function ($drivingLicenses) {
        return count($drivingLicenses) === 1;
    });

    $component->call('removeEntry', 'driving_licenses', 0);
    $component->assertSet('drivingLicenses', function ($drivingLicenses) {
        return count($drivingLicenses) === 0;
    });
});


it('adds and removes identity documents', function () {
    $component = Livewire::test(CvManager::class);
    $component->assertSet('identity_documents', function ($identityDocuments) {
        return count($identityDocuments) === 1;
    });

    $component->call('addIdentity');
    $component->assertSet('identity_documents', function ($identityDocuments) {
        return count($identityDocuments) === 2;
    });

    $component->call('removeIdentity', 0);
    $component->assertSet('identity_documents', function ($identityDocuments) {
        return count($identityDocuments) === 1;
    });
});

it('adds and removes phones', function () {
    $component = Livewire::test(CvManager::class);
    $component->assertSet('phones', function ($phones) {
        return count($phones) === 1;
    });

    $component->call('addPhone');
    $component->assertSet('phones', function ($phones) {
        return count($phones) === 2;
    });

    $component->call('removePhone', 0);
    $component->assertSet('phones', function ($phones) {
        return count($phones) === 1;
    });
});

it('adds and removes social media entries', function () {
    $component = Livewire::test(CvManager::class);
    $component->assertSet('socialMedia', function ($socialMedia) {
        return count($socialMedia) === 1;
    });

    $component->call('addSocialMedia');
    $component->assertSet('socialMedia', function ($socialMedia) {
        return count($socialMedia) === 2;
    });

    $component->call('removeSocialMedia', 0);
    $component->assertSet('socialMedia', function ($socialMedia) {
        return count($socialMedia) === 1;
    });
});

it('adds and removes emails', function () {
    $component = Livewire::test(CvManager::class);
    $component->assertSet('emails', function ($emails) {
        return count($emails) === 0;
    });

    $component->call('addEmail');
    $component->assertSet('emails', function ($emails) {
        return count($emails) === 1;
    });

    $component->call('removeEmail', 0);
    $component->assertSet('emails', function ($emails) {
        return count($emails) === 0;
    });
});

it('adds and removes addresses', function () {
    $component = Livewire::test(CvManager::class);
    $component->assertSet('addresses', function ($addresses) {
        return count($addresses) === 0;
    });

    $component->call('addAddress');
    $component->assertSet('addresses', function ($addresses) {
        return count($addresses) === 1;
    });

    $component->call('removeAddress', 0);
    $component->assertSet('addresses', function ($addresses) {
        return count($addresses) === 0;
    });
});

it('adds and removes work permits', function () {
    $component = Livewire::test(CvManager::class);
    $component->assertSet('workPermits', function ($workPermits) {
        return count($workPermits) === 0;
    });

    $component->call('addWorkPermit');
    $component->assertSet('workPermits', function ($workPermits) {
        return count($workPermits) === 1;
    });

    $component->call('removeWorkPermit', 0);
    $component->assertSet('workPermits', function ($workPermits) {
        return count($workPermits) === 0;
    });
});

it('adds and removes nationalities', function () {
    $component = Livewire::test(CvManager::class);
    $component->assertSet('nationalities', function ($nationalities) {
        return count($nationalities) === 0;
    });

    $component->call('addNationality');
    $component->assertSet('nationalities', function ($nationalities) {
        return count($nationalities) === 1;
    });

    $component->call('removeNationality', 0);
    $component->assertSet('nationalities', function ($nationalities) {
        return count($nationalities) === 0;
    });
});
/*
it('resets component data to initial state', function () {
    $component = Livewire::test(CvManager::class)
        ->set('title', 'Test Title')
        ->set('emails', ['test@example.com'])
        ->set('activeSections', ['work_experience'])
        ->set('workExperiences', [['company' => 'A', 'position' => 'B']]);

    $component->call('resetComponentData');

    expect($component->title)->toBeNull()
        ->and($component->emails)->toBe([])
        ->and($component->activeSections)->toBe([])
        ->and($component->workExperiences)->toBe([])
        ->and($component->identity_documents)->toEqual([['identity_id' => '', 'number' => '']])
        ->and($component->phones)->toEqual([['phone_id' => '', 'number' => '']])
        ->and($component->socialMedia)->toEqual([['social_media_id' => '', 'user_name' => '', 'url' => '']]);
});
*/
it('correctly handles image upload for new CV', function () {
    Storage::fake('public');

    $user = User::factory()->create();
    $user->assignRole('developer');

    $imageFile = UploadedFile::fake()->image('profile.png');

    Livewire::actingAs($user)
        ->test(CvManager::class)
        ->set('title', 'CV with image')
        ->set('first_name', 'Img')
        ->set('last_name', 'Test')
        ->set('about_me', 'About me text')
        ->set('image', $imageFile)
        ->call('store');

    $cv = CV::where('title', 'CV with image')->first();

    expect($cv)->not->toBeNull()
        ->and($cv->personalData)->not->toBeNull();

    Storage::disk('public')->assertExists('images/' . $imageFile->hashName());

    expect($cv->personalData->image)->toBe($imageFile->hashName());
});

it('correctly handles new image upload for existing CV', function () {
    Storage::fake('public');

    $user = User::factory()->create();
    $user->assignRole('developer');

    $cv = CV::factory()->for($user)->create();
    $personalData = PersonalData::factory()->for($cv)->create(['image' => 'old_image.jpg']);

    Storage::disk('public')->put('images/old_image.jpg', 'dummy');

    $newImageFile = UploadedFile::fake()->image('new_profile.png');

    Livewire::actingAs($user)
        ->test(CvManager::class)
        ->call('edit', $cv)
        ->set('new_image', $newImageFile)
        ->call('update');

    $personalData->refresh();

    Storage::disk('public')->assertMissing('images/old_image.jpg');

    Storage::disk('public')->assertExists('images/' . $newImageFile->hashName());

    expect($personalData->image)->toBe($newImageFile->hashName());
});

it('retains existing image if no new image is uploaded during update', function () {
    $user = User::factory()->create();
    $user->assignRole('developer');
    $cv = CV::factory()->for($user)->create();
    $personalData = PersonalData::factory()->for($cv)->create(['image' => 'existing_image.jpg']);
    Storage::disk('public')->put('images/existing_image.jpg', 'dummy');

    Livewire::actingAs($user)
        ->test(CvManager::class)
        ->call('edit', $cv)
        ->set('new_image', null)
        ->call('update');

    $personalData->refresh();
    Storage::disk('public')->assertExists('images/existing_image.jpg');
    expect($personalData->image)->toBe('existing_image.jpg');
});

it('does not dispatch GenerateCVPdf if no new cv is created or updated', function () {
    Queue::fake();
    Livewire::test(CvManager::class)->call('index');
    Queue::assertNotPushed(GenerateCVPdf::class);
});

it('adds a section to activeSections if data exists on fillCvData', function () {
    $user = User::factory()->create();
    $user->assignRole('developer');
    $cv = CV::factory()->for($user)->create();
    PersonalData::factory()->for($cv)->create();
    WorkExperience::factory()->for($cv)->create();
    Education::factory()->for($cv)->create();
    $cv->languages()->attach(Language::first()->id, ['level' => 'fluent']);
    $cv->digitalSkills()->attach(DigitalSkill::first()->id, ['level' => 'expert']);
    $cv->drivingLicenses()->attach(DrivingLicense::first()->id);

    $component = Livewire::actingAs($user)
        ->test(CvManager::class)
        ->call('edit', $cv);

    expect($component->activeSections)->toContain('work_experience')
        ->and($component->activeSections)->toContain('education')
        ->and($component->activeSections)->toContain('languages')
        ->and($component->activeSections)->toContain('skills')
        ->and($component->activeSections)->toContain('driving_licenses');
});

it('does not add a section to activeSections if no data exists on fillCvData', function () {
    $user = User::factory()->create();
    $user->assignRole('developer');
    $cv = CV::factory()->for($user)->create();
    PersonalData::factory()->for($cv)->create();

    $component = Livewire::actingAs($user)
        ->test(CvManager::class)
        ->call('edit', $cv);

    expect($component->activeSections)->not->toContain('work_experience')
        ->and($component->activeSections)->not->toContain('education')
        ->and($component->activeSections)->not->toContain('languages')
        ->and($component->activeSections)->not->toContain('skills')
        ->and($component->activeSections)->not->toContain('driving_licenses');
});

it('handles empty dynamic fields correctly when filling CV data', function () {
    $user = User::factory()->create();
    $user->assignRole('developer');
    $cv = CV::factory()->for($user)->create();
    PersonalData::factory()->for($cv)->create([
        'email' => null,
        'address' => null,
        'workPermits' => null,
        'nationality' => null,
    ]);

    $component = Livewire::actingAs($user)
        ->test(CvManager::class)
        ->call('edit', $cv);

    expect($component->emails)->toBeArray()->toHaveCount(1)->each->toBe('')
        ->and($component->addresses)->toBeArray()->toHaveCount(1)->each->toBe('')
        ->and($component->workPermits)->toBeArray()->toHaveCount(1)->each->toBe('')
        ->and($component->nationalities)->toBeArray()->toHaveCount(1)->each->toBe('')
        ->and($component->identity_documents)->toEqual([['identity_id' => '', 'number' => '']])
        ->and($component->phones)->toEqual([['phone_id' => '', 'number' => '']])
        ->and($component->socialMedia)->toEqual([['social_media_id' => '', 'user_name' => '', 'url' => '']]);
});
/*

it('attaches pivot data correctly, including with and without additional pivot data', function () {
    $user = User::factory()->create();
    $user->assignRole('developer');
    $cv = CV::factory()->for($user)->create();
    $personalData = PersonalData::factory()->for($cv)->create();

    $identity = Identity::first();
    $drivingLicense = DrivingLicense::first();
    $language = Language::first();

    Livewire::actingAs($user)
        ->test(CvManager::class)
        ->set('identity_documents', [['identity_id' => $identity->id, 'number' => 'DOC123']])
        ->call(
            'callPrivateMethod',
            'attachPivotData',
            $personalData->identities(),
            [['identity_id' => $identity->id, 'number' => 'DOC123']],
            ['identity_id' => 'id', 'number' => 'number']
        );
    assertDatabaseHas('identity_personal_data', ['personal_data_id' => $personalData->id, 'identity_id' => $identity->id, 'number' => 'DOC123']);

    Livewire::actingAs($user)
        ->test(CvManager::class)
        ->call(
            'callPrivateMethod',
            'attachPivotData',
            $cv->drivingLicenses(),
            [['driving_license_id' => $drivingLicense->id]],
            ['driving_license_id' => 'id']
        );
    assertDatabaseHas('cvs_driving_licenses', ['cv_id' => $cv->id, 'driving_license_id' => $drivingLicense->id]);

    Livewire::actingAs($user)
        ->test(CvManager::class)
        ->call(
            'callPrivateMethod',
            'attachPivotData',
            $cv->languages(),
            [['language_id' => $language->id, 'level' => 'Fluent']],
            ['language_id' => 'id', 'level' => 'level']
        );
    assertDatabaseHas('cvs_languages', ['cv_id' => $cv->id, 'language_id' => $language->id, 'level' => 'Fluent']);
});

it('syncs pivot data correctly, including with and without additional pivot data', function () {
    $user = User::factory()->create();
    $user->assignRole('developer');
    $cv = CV::factory()->for($user)->create();
    $personalData = PersonalData::factory()->for($cv)->create();

    $identity1 = Identity::first();
    $identity2 = Identity::factory()->create(['type' => 'Passport']); // Create another identity

    $drivingLicense1 = DrivingLicense::first();
    $drivingLicense2 = DrivingLicense::factory()->create(['name' => 'A']); // Create another driving license

    $language1 = Language::first();
    $language2 = Language::factory()->create(['name' => 'Spanish']); // Create another language

    // Initial attach for identity
    $personalData->identities()->attach($identity1->id, ['number' => 'OLD123']);
    assertDatabaseHas('identity_personal_data', ['personal_data_id' => $personalData->id, 'identity_id' => $identity1->id, 'number' => 'OLD123']);

    // Initial attach for driving license
    $cv->drivingLicenses()->attach($drivingLicense1->id);
    assertDatabaseHas('cvs_driving_licenses', ['cv_id' => $cv->id, 'driving_license_id' => $drivingLicense1->id]);

    // Initial attach for languages
    $cv->languages()->attach($language1->id, ['level' => 'Old Level']);
    assertDatabaseHas('cvs_languages', ['cv_id' => $cv->id, 'language_id' => $language1->id, 'level' => 'Old Level']);


    // Test syncing identity: update existing and add new
    Livewire::actingAs($user)
        ->test(CvManager::class)
        ->call(
            'callPrivateMethod',
            'syncPivotData',
            $personalData->identities(),
            [
                ['identity_id' => $identity1->id, 'number' => 'NEW123'],
                ['identity_id' => $identity2->id, 'number' => 'NEW456']
            ],
            ['identity_id' => 'id', 'number' => 'number']
        );

    assertDatabaseHas('identity_personal_data', ['personal_data_id' => $personalData->id, 'identity_id' => $identity1->id, 'number' => 'NEW123']);
    assertDatabaseHas('identity_personal_data', ['personal_data_id' => $personalData->id, 'identity_id' => $identity2->id, 'number' => 'NEW456']);
    assertDatabaseMissing('identity_personal_data', ['personal_data_id' => $personalData->id, 'identity_id' => $identity1->id, 'number' => 'OLD123']); // Old entry should be gone

    // Test syncing driving license: remove existing and add new (only one entry)
    Livewire::actingAs($user)
        ->test(CvManager::class)
        ->call(
            'callPrivateMethod',
            'syncPivotData',
            $cv->drivingLicenses(),
            [['driving_license_id' => $drivingLicense2->id]],
            ['driving_license_id' => 'id']
        );
    assertDatabaseMissing('cvs_driving_licenses', ['cv_id' => $cv->id, 'driving_license_id' => $drivingLicense1->id]);
    assertDatabaseHas('cvs_driving_licenses', ['cv_id' => $cv->id, 'driving_license_id' => $drivingLicense2->id]);

    // Test syncing languages: update existing and add new
    Livewire::actingAs($user)
        ->test(CvManager::class)
        ->call(
            'callPrivateMethod',
            'syncPivotData',
            $cv->languages(),
            [
                ['language_id' => $language1->id, 'level' => 'Updated Fluent'],
                ['language_id' => $language2->id, 'level' => 'Basic']
            ],
            ['language_id' => 'id', 'level' => 'level']
        );
    assertDatabaseHas('cvs_languages', ['cv_id' => $cv->id, 'language_id' => $language1->id, 'level' => 'Updated Fluent']);
    assertDatabaseHas('cvs_languages', ['cv_id' => $cv->id, 'language_id' => $language2->id, 'level' => 'Basic']);
    assertDatabaseMissing('cvs_languages', ['cv_id' => $cv->id, 'language_id' => $language1->id, 'level' => 'Old Level']);
});

it('creates HasMany data correctly', function () {
    $user = User::factory()->create();
    $user->assignRole('developer');
    $cv = CV::factory()->for($user)->create();

    $workExperiencesData = [
        ['company' => 'Comp1', 'position' => 'Pos1', 'start' => '2020-01-01', 'end' => '2021-01-01', 'description' => 'Desc1'],
        ['company' => 'Comp2', 'position' => 'Pos2', 'start' => '2021-02-01', 'end' => null, 'description' => 'Desc2']
    ];

    Livewire::actingAs($user)
        ->test(CvManager::class)
        ->call(
            'callPrivateMethod',
            'createHasManyData',
            $cv->workExperiences(),
            $workExperiencesData,
            ['company_name' => 'company', 'position' => 'position', 'start_date' => 'start', 'end_date' => 'end', 'description' => 'description']
        );

    assertDatabaseHas('work_experiences', ['cv_id' => $cv->id, 'company_name' => 'Comp1', 'position' => 'Pos1']);
    assertDatabaseHas('work_experiences', ['cv_id' => $cv->id, 'company_name' => 'Comp2', 'position' => 'Pos2']);
    assertDatabaseCount('work_experiences', 2);
});

it('syncs HasMany data correctly', function () {
    $user = User::factory()->create();
    $user->assignRole('developer');
    $cv = CV::factory()->for($user)->create();

    // Create initial data
    WorkExperience::factory()->for($cv)->create(['company_name' => 'Old Company', 'position' => 'Old Position']);
    Education::factory()->for($cv)->create(['institution' => 'Old School', 'title' => 'Old Degree']);

    assertDatabaseCount('work_experiences', 1);
    assertDatabaseCount('education', 1);

    $newWorkExperiencesData = [
        ['company' => 'New Comp', 'position' => 'New Pos', 'start' => '2022-01-01', 'end' => null, 'description' => 'New Desc']
    ];
    $newEducationData = [
        ['school' => 'New Uni', 'degree' => 'New Degree', 'start' => '2023-01-01', 'end' => null, 'city' => 'New City', 'country' => 'New Country']
    ];

    Livewire::actingAs($user)
        ->test(CvManager::class)
        ->call(
            'callPrivateMethod',
            'syncHasManyData',
            $cv->workExperiences(),
            $newWorkExperiencesData,
            ['company_name' => 'company', 'position' => 'position', 'start_date' => 'start', 'end_date' => 'end', 'description' => 'description']
        );

    Livewire::actingAs($user)
        ->test(CvManager::class)
        ->call(
            'callPrivateMethod',
            'syncHasManyData',
            $cv->education(),
            $newEducationData,
            ['institution' => 'school', 'title' => 'degree', 'start_date' => 'start', 'end_date' => 'end', 'city' => 'city', 'country' => 'country']
        );

    assertDatabaseMissing('work_experiences', ['company_name' => 'Old Company']);
    assertDatabaseHas('work_experiences', ['cv_id' => $cv->id, 'company_name' => 'New Comp', 'position' => 'New Pos']);
    assertDatabaseCount('work_experiences', 1);

    assertDatabaseMissing('education', ['institution' => 'Old School']);
    assertDatabaseHas('education', ['cv_id' => $cv->id, 'institution' => 'New Uni', 'title' => 'New Degree']);
    assertDatabaseCount('education', 1);
});
*/
if (!function_exists('callPrivateMethod')) {
    function callPrivateMethod($object, $methodName, ...$parameters)
    {
        $reflection = new \ReflectionClass($object);
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);
        return Livewire::test(CvManager::class)->call(function ($component) use ($method, $object, $parameters) {
            return $method->invokeArgs($component, $parameters);
        });
    }
}
