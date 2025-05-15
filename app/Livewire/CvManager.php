<?php

namespace App\Livewire;

use App\Http\Requests\CvCreateRequest;
use App\Http\Requests\CvUpdateRequest;
use App\Jobs\GenerateCVPdf;
use App\Models\DigitalSkill;
use App\Models\DrivingLicense;
use App\Models\Education;
use App\Models\Gender;
use App\Models\Identity;
use App\Models\Language;
use App\Models\PersonalData;
use App\Models\Phone;
use App\Models\SocialMedia;
use App\Models\WorkExperience;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\CV;

class CvManager extends Component
{
    use WithFileUploads;

    public $title;

    public $first_name;
    public $last_name;
    public $image;
    public $about_me;
    public $birth_date;
    public $city;
    public $country;
    public $gender_id;

    public $workPermits = [];
    public $nationalities = [];
    public $emails = [];
    public $addresses = [];

    public $identity_documents = [];

    public $phones = [];

    public $socialMedia = [];

    public $workExperiences = [];

    public $educations = [];

    public $languages = [];

    public $skills = [];

    public $drivingLicenses = [];

    public $cvs, $selectedCv;

    public $genders = [];
    public $identityTypes = [];
    public $phoneTypes = [];
    public $socialMediaTypes = [];

    public $languages_options = [];

    public $skills_options = [];

    public $drivingLicenses_options = [];

    public $personalData;
    public $new_image;

    public $availableSections = [
        'work_experience' => 'Work Experience',
        'education' => 'Education',
        'languages' => 'Languages',
        'skills' => 'Skills',
        'driving_licenses' => 'Driving Licenses',
    ];

    public $activeSections = [];

    public $view = 'index';

    public function addSection($section)
    {
        if (!in_array($section, $this->activeSections)) {
            $this->activeSections[] = $section;
            $this->addEntry($section); // Inicializa con una entrada vacía
        }
    }

    public function removeSection($section)
    {
        $this->activeSections = array_filter($this->activeSections, fn($s) => $s !== $section);

        switch ($section) {
            case 'work_experience':
                $this->workExperiences = [];
                break;
            case 'education':
                $this->educations = [];
                break;
            case 'languages':
                $this->languages = [];
                break;
            case 'skills':
                $this->skills = [];
                break;
            case 'driving_licenses':
                $this->drivingLicenses = [];
                break;
        }
    }

    public function addEntry($section)
    {
        switch ($section) {
            case 'work_experience':
                $this->workExperiences[] = ['company' => '', 'position' => '', 'start' => '', 'end' => '', 'description' => ''];
                break;
            case 'education':
                $this->educations[] = ['school' => '', 'degree' => '', 'start' => '', 'end' => '', 'description' => '', 'city' => '', 'country' => ''];
                break;
            case 'languages':
                $this->languages[] = ['language_id' => '', 'level' => ''];
                break;
            case 'skills':
                $this->skills[] = ['digital_skill_id' => '', 'level' => ''];
                break;
            case 'driving_licenses':
                $this->drivingLicenses[] = ['driving_license_id' => ''];
                break;
        }
    }

    public function removeEntry($section, $index)
    {
        switch ($section) {
            case 'work_experience':
                unset($this->workExperiences[$index]);
                $this->workExperiences = array_values($this->workExperiences);
                break;
            case 'education':
                unset($this->educations[$index]);
                $this->educations = array_values($this->educations);
                break;
            case 'languages':
                unset($this->languages[$index]);
                $this->languages = array_values($this->languages);
                break;
            case 'skills':
                unset($this->skills[$index]);
                $this->skills = array_values($this->skills);
                break;
            case 'driving_licenses':
                unset($this->drivingLicenses[$index]);
                $this->drivingLicenses = array_values($this->drivingLicenses);
                break;
        }
    }

    public function mount()
    {
        Gate::authorize('viewAny', CV::class);

        $this->cvs = CV::CvByRol()->get();
        $this->genders = Gender::all();
        $this->identityTypes = Identity::all();
        $this->phoneTypes = Phone::all();
        $this->socialMediaTypes = SocialMedia::all();
        $this->languages_options = Language::all();
        $this->skills_options = DigitalSkill::all();
        $this->drivingLicenses_options = DrivingLicense::all();

        // Y demás campos...

        // Inicializar arrays dinámicos
        $this->identity_documents = [['identity_id' => '', 'number' => '']];
        $this->phones = [['phone_id' => '', 'number' => '']];
        $this->socialMedia = [['social_media_id' => '', 'user_name' => '', 'url' => '']];
    }


    public function index()
    {
        $this->mount();
        $this->view = 'index';
    }

    public function create()
    {
        Gate::authorize('create', CV::class);

        $this->resetErrorBag();
        $this->resetValidation();
        $this->view = 'create';
    }

    public function store()
    {
        $this->validate((new CvCreateRequest())->rules());

        $cv = CV::create(['title' => $this->title, 'user_id' => auth()->id()]);

        $imagePath = $this->image ? basename($this->image->store('images', 'public')) : null;

        $personalData = PersonalData::create([
            'cv_id' => $cv->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'birth_date' => $this->birth_date,
            'city' => $this->city,
            'country' => $this->country,
            'about_me' => $this->about_me,
            'email' => $this->emails,
            'address' => $this->addresses,
            'workPermits' => $this->workPermits,
            'nationality' => $this->nationalities,
            'image' => $imagePath,
            'gender_id' => $this->gender_id,
        ]);

        if (!empty($this->identity_documents)) {
            foreach ($this->identity_documents as $doc) {
                if (!empty($doc['identity_id']) && !empty($doc['number'])) {
                    $personalData->identities()->attach($doc['identity_id'], [
                        'number' => $doc['number']
                    ]);
                }
            }
        }

        if (!empty($this->phones)) {
            foreach ($this->phones as $phone) {
                if (!empty($phone['phone_id']) && !empty($phone['number'])) {
                    $personalData->phones()->attach($phone['phone_id'], [
                        'number' => $phone['number']
                    ]);
                }
            }
        }

        if (!empty($this->socialMedia)) {
            foreach ($this->socialMedia as $social) {
                if (!empty($social['social_media_id']) && !empty($social['user_name'])) {
                    $personalData->socialMedia()->attach($social['social_media_id'], [
                        'user_name' => $social['user_name'],
                        'url' => $social['url'] ?? null,
                    ]);
                }
            }
        }

        if (!empty($this->workExperiences)) {
            foreach ($this->workExperiences as $experience) {
                $experienceModel = WorkExperience::create([
                    'cv_id' => $cv->id,
                    'company_name' => $experience['company'],
                    'position' => $experience['position'],
                    'start_date' => $experience['start'],
                    'end_date' => $experience['end'],
                    'description' => $experience['description'],
                ]);

                $cv->workExperiences()->save($experienceModel);
            }
        }

        if (!empty($this->educations)) {
            foreach ($this->educations as $education) {
                $educationModel = Education::create([
                    'cv_id' => $cv->id,
                    'institution' => $education['school'],
                    'title' => $education['degree'],
                    'start_date' => $education['start'],
                    'city' => $education['city'],
                    'country' => $education['country'],
                    'end_date' => $education['end'],
                ]);

                $cv->education()->save($educationModel);
            }
        }

        if (!empty($this->languages)) {
            foreach ($this->languages as $language) {
                if (!empty($language['language_id']) && !empty($language['level'])) {
                    $cv->languages()->attach($language['language_id'], [
                        'level' => $language['level']
                    ]);
                }
            }
        }

        if (!empty($this->skills)) {
            foreach ($this->skills as $skill) {
                if (!empty($skill['digital_skill_id']) && !empty($skill['level'])) {
                    $cv->digitalSkills()->attach($skill['digital_skill_id'], [
                        'level' => $skill['level']
                    ]);
                }
            }
        }

        if (!empty($this->drivingLicenses)) {
            foreach ($this->drivingLicenses as $drivingLicens) {
                if (!empty($drivingLicens['driving_license_id'])) {
                    $cv->drivingLicenses()->attach($drivingLicens['driving_license_id']);
                }
            }
        }

        $cv->save();
        GenerateCVPdf::dispatch($cv);

        $this->reset([
            'title',

            'first_name',
            'last_name',
            'birth_date',
            'city',
            'country',
            'about_me',
            'emails',
            'addresses',
            'workPermits',
            'nationalities',
            'image',

            // Pivotes
            'identity_documents',
            'phones',
            'socialMedia',

            // Muchos a muchos
            'workExperiences',
            'educations',
            'languages',
            'skills',
        ]);

        $this->mount();
        $this->view = 'index';
    }

    public
    function edit(CV $cv)
    {
        Gate::authorize('update', $cv);

        $this->selectedCv = $cv;
        $this->title = $cv->title;

        $personalData = $cv->personalData;
        $this->image = $personalData->image ?? null;
        $this->first_name = $personalData->first_name;
        $this->last_name = $personalData->last_name;
        $this->birth_date = $personalData->birth_date;
        $this->city = $personalData->city;
        $this->country = $personalData->country;
        $this->about_me = $personalData->about_me;
        $this->emails = $personalData?->email ?? [''];
        $this->addresses = $personalData?->address ?? [''];
        $this->workPermits = $personalData?->workPermits ?? [''];
        $this->nationalities = $personalData?->nationality ?? [''];
        $this->gender_id = $personalData->gender_id;

        $this->identity_documents = $personalData->identities->map(function ($identity) {
            return [
                'identity_id' => $identity->id,
                'number' => $identity->pivot->number,
            ];
        })->toArray();

        $this->phones = $personalData->phones->map(function ($phone) {
            return [
                'phone_id' => $phone->id,
                'number' => $phone->pivot->number,
            ];
        })->toArray();

        $this->socialMedia = $personalData->socialMedia->map(function ($socialmedia) {
            return [
                'social_media_id' => $socialmedia->id,
                'user_name' => $socialmedia->pivot->user_name,
                'url' => $socialmedia->pivot->url,
            ];
        })->toArray();

        $this->workExperiences = $cv->workExperiences->map(function ($experience) {
            return [
                'company' => $experience->company_name,
                'position' => $experience->position,
                'start' => $experience->start_date,
                'end' => $experience->end_date,
                'description' => $experience->description,
            ];
        })->toArray();

        if (count($this->workExperiences) > 0 && !in_array("work_experience", $this->activeSections)) {
            $this->activeSections[] = "work_experience";
        }

        $this->educations = $cv->education->map(function ($education) {
            return [
                'school' => $education->institution,
                'city' => $education->city,
                'country' => $education->country,
                'degree' => $education->title,
                'start' => $education->start_date,
                'end' => $education->end_date,
            ];
        })->toArray();

        if (count($this->educations) > 0 && !in_array("education", $this->activeSections)) {
            $this->activeSections[] = "education";
        }

        $this->languages = $cv->languages->map(function ($language) {
            return [
                'language_id' => $language->id,
                'level' => $language->pivot->level,
            ];
        })->toArray();

        if (count($this->languages) > 0 && !in_array("languages", $this->activeSections)) {
            $this->activeSections[] = "languages";
        }

        $this->skills = $cv->digitalSkills->map(function ($skill) {
            return [
                'digital_skill_id' => $skill->id,
                'level' => $skill->pivot->level,
            ];
        })->toArray();

        if (count($this->languages) > 0 && !in_array("skills", $this->activeSections)) {
            $this->activeSections[] = "skills";
        }

        $this->drivingLicenses = $cv->drivingLicenses->map(function ($drivingLicense) {
            return [
                'driving_license_id' => $drivingLicense->id,
            ];
        })->toArray();

        if (count($this->drivingLicenses) > 0 && !in_array("driving_licenses", $this->activeSections)) {
            $this->activeSections[] = "driving_licenses";
        }

        $this->view = 'edit';
    }

    public function update()
    {
        $this->validate((new CvUpdateRequest())->rules());

        $cv = $this->selectedCv;
        $cv->update(['title' => $this->title]);

        $imagePath = $this->new_image ? basename($this->new_image->store('images', 'public')) : $cv->personalData->image;

        $personalData = $cv->personalData;
        $personalData->update([
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'birth_date' => $this->birth_date,
            'city' => $this->city,
            'country' => $this->country,
            'about_me' => $this->about_me,
            'email' => $this->emails,
            'address' => $this->addresses,
            'workPermits' => $this->workPermits,
            'nationality' => $this->nationalities,
            'image' => $imagePath,
            'gender_id' => $this->gender_id,
        ]);

        $personalData->identities()->detach();
        $personalData->phones()->detach();
        $personalData->socialMedia()->detach();
        $cv->languages()->detach();
        $cv->digitalSkills()->detach();
        $cv->drivingLicenses()->detach();
        $cv->workExperiences()->delete();
        $cv->education()->delete();

        foreach ($this->identity_documents ?? [] as $doc) {
            if (!empty($doc['identity_id']) && !empty($doc['number'])) {
                $personalData->identities()->attach($doc['identity_id'], ['number' => $doc['number']]);
            }
        }

        foreach ($this->phones ?? [] as $phone) {
            if (!empty($phone['phone_id']) && !empty($phone['number'])) {
                $personalData->phones()->attach($phone['phone_id'], ['number' => $phone['number']]);
            }
        }

        foreach ($this->socialMedia ?? [] as $social) {
            if (!empty($social['social_media_id']) && !empty($social['user_name'])) {
                $personalData->socialMedia()->attach($social['social_media_id'], [
                    'user_name' => $social['user_name'],
                    'url' => $social['url'] ?? null,
                ]);
            }
        }

        foreach ($this->workExperiences ?? [] as $experience) {
            $cv->workExperiences()->create([
                'company_name' => $experience['company'],
                'position' => $experience['position'],
                'start_date' => $experience['start'],
                'end_date' => $experience['end'],
                'description' => $experience['description'],
            ]);
        }

        foreach ($this->educations ?? [] as $education) {
            $cv->education()->create([
                'institution' => $education['school'],
                'title' => $education['degree'],
                'start_date' => $education['start'],
                'city' => $education['city'],
                'country' => $education['country'],
                'end_date' => $education['end'],
            ]);
        }

        foreach ($this->languages ?? [] as $language) {
            if (!empty($language['language_id']) && !empty($language['level'])) {
                $cv->languages()->attach($language['language_id'], ['level' => $language['level']]);
            }
        }

        foreach ($this->skills ?? [] as $skill) {
            if (!empty($skill['digital_skill_id']) && !empty($skill['level'])) {
                $cv->digitalSkills()->attach($skill['digital_skill_id'], ['level' => $skill['level']]);
            }
        }

        foreach ($this->drivingLicenses ?? [] as $drivingLicens) {
            if (!empty($drivingLicens['driving_license_id'])) {
                $cv->drivingLicenses()->attach($drivingLicens['driving_license_id']);
            }
        }

        $cv->save();
        GenerateCVPdf::dispatch($cv);

        $this->reset([
            'title',
            'first_name', 'last_name', 'birth_date', 'city', 'country', 'about_me',
            'emails', 'addresses', 'workPermits', 'nationalities', 'image',
            'identity_documents', 'phones', 'socialMedia',
            'workExperiences', 'educations', 'languages', 'skills', 'drivingLicenses'
        ]);

        $this->mount();
        $this->view = 'index';
    }

    public
    function delete(CV $cv)
    {
        Gate::authorize('delete', $cv);
        $cv->delete();
        $this->mount();
    }

    public
    function show(CV $cv)
    {
        Gate::authorize('view', $cv);

        $this->selectedCv = $cv;
        $this->personalData = $cv->personalData;
        $this->view = 'show';
    }

    public
    function addIdentity()
    {
        $this->identity_documents[] = ['identity_id' => '', 'number' => ''];
    }

    public
    function removeIdentity($index)
    {
        unset($this->identity_documents[$index]);
        $this->identity_documents = array_values($this->identity_documents);
    }

    public
    function addPhone()
    {
        $this->phones[] = ['phone_id' => '', 'number' => ''];
    }

    public
    function removePhone($index)
    {
        unset($this->phones[$index]);
        $this->phones = array_values($this->phones);
    }

    public
    function addSocialMedia()
    {
        $this->socialMedia[] = ['social_media_id' => '', 'user_name' => '', 'url' => ''];
    }

    public
    function removeSocialMedia($index)
    {
        unset($this->socialMedia[$index]);
        $this->socialMedia = array_values($this->socialMedia);
    }

    public
    function addEmail()
    {
        $this->emails[] = '';
    }

    public
    function removeEmail($index)
    {
        unset($this->emails[$index]);
        $this->emails = array_values($this->emails);
    }

    public
    function addAddress()
    {
        $this->addresses[] = '';
    }

    public
    function removeAddress($index)
    {
        unset($this->addresses[$index]);
        $this->addresses = array_values($this->addresses);
    }

    public
    function addWorkPermit()
    {
        $this->workPermits[] = '';
    }

    public
    function removeWorkPermit($index)
    {
        unset($this->workPermits[$index]);
        $this->workPermits = array_values($this->workPermits);
    }

    public
    function addNationality()
    {
        $this->nationalities[] = '';
    }

    public
    function removeNationality($index)
    {
        unset($this->nationalities[$index]);
        $this->nationalities = array_values($this->nationalities);
    }

    public
    function render()
    {
        return view('livewire.cv-manager')->layout('layouts.app');
    }
}

