<?php

namespace App\Livewire;

use App\Models\DigitalSkill;
use App\Models\Education;
use App\Models\Gender;
use App\Models\Identity;
use App\Models\Language;
use App\Models\PersonalData;
use App\Models\Phone;
use App\Models\SocialMedia;
use App\Models\WorkExperience;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\CV;

class CvManager extends Component
{
    use WithFileUploads;

    public $title;

    // Datos Personales
    public $first_name;
    public $last_name;
    public $image;
    public $about_me;
    public $birth_date;
    public $city;
    public $country;
    public $gender_id;

    // Arrays de relaciones
    public $workPermits = [];
    public $nationalities = [];
    public $emails = [];
    public $addresses = [];

    // Documentos de identidad
    public $identity_documents = [];

    // Teléfonos
    public $phones = [];

    // Redes sociales
    public $socialMedia = [];

    // Experiencia laboral
    public $workExperiences = [];

    // Educación
    public $educations = [];

    // Idiomas
    public $languages = [];

    // Habilidades
    public $skills = [];

    public $cvs, $selectedCv;

    public $genders = []; // Lista de géneros desde DB
    public $identityTypes = []; // Lista de tipos de documento desde DB
    public $phoneTypes = []; // Lista de tipos de teléfono desde DB
    public $socialMediaTypes = []; // Lista de redes sociales desde DB

    public $languages_options = [];

    public $skills_options = [];

    public $personalData;


    public $availableSections = [
        'work_experience' => 'Experiencia Laboral',
        'education' => 'Educación',
        'languages' => 'Idiomas',
        'skills' => 'Habilidades',
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
        }
    }

    public function mount()
    {
        $this->first_name = '';
        $this->last_name = '';
        $this->title = '';
        $this->cvs = CV::where('user_id', auth()->id())->get();
        $this->genders = Gender::all();
        $this->identityTypes = Identity::all();
        $this->phoneTypes = Phone::all();
        $this->socialMediaTypes = SocialMedia::all();
        $this->languages_options = Language::all();
        $this->skills_options = DigitalSkill::all();

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
        $this->resetErrorBag();
        $this->resetValidation();
        $this->view = 'create';
    }

    public function store()
    {

        $validated = $this->validate([
            'title' => 'required|string|max:255',

            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048',
            'about_me' => 'required|string',
            'workPermits' => 'nullable|array',
            'workPermits.*' => 'nullable|string|max:255',
            'birth_date' => 'nullable|date',
            'city' => 'nullable|string',
            'country' => 'nullable|string',
            'nationalities' => 'nullable|array',
            'nationalities.*' => 'nullable|string|max:255',
            'emails' => 'nullable|array',
            'emails.*' => 'nullable|email',
            'addresses' => 'nullable|array',
            'addresses.*' => 'nullable|string|max:255',

            'gender_id' => 'nullable|exists:genders,id',

            'identity_documents' => 'nullable|array',
            'identity_documents.*.identity_id' => 'nullable|exists:identities,id',
            'identity_documents.*.number' => 'nullable|string|max:100',

            'phones' => 'nullable|array',
            'phones.*.phone_id' => 'nullable|exists:phones,id',
            'phones.*.number' => 'nullable|string|max:20',

            'socialMedia' => 'nullable|array',
            'socialMedia.*.social_media_id' => 'nullable|exists:social_media,id',
            'socialMedia.*.user_name' => 'nullable|string|min:3|max:255',
            'socialMedia.*.url' => 'nullable|url|max:255',

            // Experiencia Laboral
            'workExperiences' => 'nullable|array',
            'workExperiences.*.company' => 'nullable|string|max:255',
            'workExperiences.*.position' => 'nullable|string|max:255',
            'workExperiences.*.start' => 'nullable|date',
            'workExperiences.*.end' => 'nullable|date|after_or_equal:workExperiences.*.start',
            'workExperiences.*.description' => 'nullable|string',

            // Educación
            'educations' => 'nullable|array',
            'educations.*.school' => 'nullable|string|max:255',
            'educations.*.degree' => 'nullable|string|max:255',
            'educations.*.city' => 'nullable|string|max:255',
            'educations.*.country' => 'nullable|string|max:255',
            'educations.*.start' => 'nullable|date',
            'educations.*.end' => 'nullable|date|after_or_equal:educations.*.start',
            'educations.*.description' => 'nullable|string',

            // Idiomas
            'languages' => 'nullable|array',
            'languages.*.language_id' => 'nullable|exists:languages,id',
            'languages.*.level' => 'nullable|string|max:50',

            // Habilidades
            'skills' => 'nullable|array',
            'skills.*.digital_skill_id' => 'nullable|exists:digital_skills,id',
            'skills.*.level' => 'nullable|string|max:50',
        ]);

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
            'email' => json_encode($this->emails),
            'address' => json_encode($this->addresses),
            'workPermits' => json_encode($this->workPermits),
            'nationalities' => json_encode($this->nationalities),
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

        $cv->save();

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
        $this->selectedCv = $cv;
        $this->title = $cv->title;

        $personalData = $cv->personalData;
        $this->emails = $personalData ? json_decode($personalData->email, true) ?? [''] : [''];
        $this->addresses = $personalData ? json_decode($personalData->address, true) ?? [''] : [''];
        $this->workPermits = $personalData ? json_decode($personalData->workPermits, true) ?? [''] : [''];
        $this->nationalities = $personalData ? json_decode($personalData->nationalities, true) ?? [''] : [''];
        $this->image = $personalData->image ?? null;

        $this->view = 'edit';
    }

    public
    function update()
    {
        $this->validate([
            'title' => 'required',
            'description' => 'required',
            'emails.*' => 'nullable|email',
            'addresses.*' => 'nullable|string',
            'workPermits.*' => 'nullable|string',
            'nationalities.*' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ]);

        $this->selectedCv->update(['title' => $this->title, 'description' => $this->description]);

        $imagePath = $this->image ? $this->image->store('images', 'public') : $this->selectedCv->personalData->image;

        $this->selectedCv->personalData->update([
            'about_me' => $this->description,
            'email' => json_encode($this->emails),
            'address' => json_encode($this->addresses),
            'workPermits' => json_encode($this->workPermits),
            'nationalities' => json_encode($this->nationalities),
            'image' => $imagePath,
        ]);

        $this->mount();
        $this->view = 'index';
    }

    public
    function delete(CV $cv)
    {
        $cv->delete();
        $this->mount();
    }

    public
    function show(CV $cv)
    {
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

