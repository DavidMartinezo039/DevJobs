<?php

namespace App\Livewire;

use App\Models\Gender;
use App\Models\Identity;
use App\Models\PersonalData;
use App\Models\Phone;
use App\Models\SocialMedia;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\CV;

class CvManager extends Component
{
    use WithFileUploads;

    public $cvs, $title, $description, $selectedCv, $image;
    public $emails = [''], $addresses = [''], $workPermits = [''], $nationalities = [''];
    public $gender_id;
    public $identity_documents = []; // Cada item: ['identity_id' => '', 'number' => '']
    public $phones = []; // Cada item: ['phone_id' => '', 'number' => '']
    public $socialMedia = []; // Cada item: ['social_media_id' => '', 'user_name' => '', 'url' => '']

    public $genders = []; // Lista de géneros desde DB
    public $identityTypes = []; // Lista de tipos de documento desde DB
    public $phoneTypes = []; // Lista de tipos de teléfono desde DB
    public $socialMediaTypes = []; // Lista de redes sociales desde DB


    public $availableSections = [
        'work_experience' => 'Experiencia Laboral',
        'education' => 'Educación',
        'languages' => 'Idiomas',
        'skills' => 'Habilidades',
    ];

    public $activeSections = [];

    public $workExperiences = [];
    public $educations = [];
    public $languages = [];
    public $skills = [];

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
                $this->educations[] = ['school' => '', 'degree' => '', 'start' => '', 'end' => '', 'description' => ''];
                break;
            case 'languages':
                $this->languages[] = ['language' => '', 'level' => ''];
                break;
            case 'skills':
                $this->skills[] = ['name' => '', 'level' => ''];
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
        $this->cvs = CV::all();
        $this->genders = Gender::all(); // Asumiendo que tienes un modelo Gender
        $this->identityTypes = Identity::all(); // Modelo IdentityType
        $this->phoneTypes = Phone::all(); // Modelo PhoneType
        $this->socialMediaTypes = SocialMedia::all(); // Modelo SocialMediaType

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
        $this->reset(['title', 'description', 'emails', 'addresses', 'workPermits', 'nationalities', 'image']);
        $this->view = 'create';
    }

    public function store()
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

        $cv = CV::create(['title' => $this->title, 'user_id' => auth()->id()]);

        $imagePath = $this->image ? $this->image->store('images', 'public') : null;

        PersonalData::create([
            'cv_id' => $cv->id,
            'about_me' => $this->description,
            'email' => json_encode($this->emails),
            'address' => json_encode($this->addresses),
            'work_permits' => json_encode($this->workPermits),
            'nationalities' => json_encode($this->nationalities),
            'image' => $imagePath,
        ]);

        $this->reset(['title', 'description', 'emails', 'addresses', 'workPermits', 'nationalities', 'image']);
        $this->mount();
        $this->view = 'index';
    }

    public function edit(CV $cv)
    {
        $this->selectedCv = $cv;
        $this->title = $cv->title;
        $this->description = $cv->description;

        $personalData = $cv->personalData;
        $this->emails = $personalData ? json_decode($personalData->email, true) ?? [''] : [''];
        $this->addresses = $personalData ? json_decode($personalData->address, true) ?? [''] : [''];
        $this->workPermits = $personalData ? json_decode($personalData->work_permits, true) ?? [''] : [''];
        $this->nationalities = $personalData ? json_decode($personalData->nationalities, true) ?? [''] : [''];
        $this->image = $personalData->image ?? null;

        $this->view = 'edit';
    }

    public function update()
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
            'work_permits' => json_encode($this->workPermits),
            'nationalities' => json_encode($this->nationalities),
            'image' => $imagePath,
        ]);

        $this->mount();
        $this->view = 'index';
    }

    public function delete(CV $cv)
    {
        $cv->delete();
        $this->mount();
    }

    public function show(CV $cv)
    {
        $this->selectedCv = $cv;
        $this->view = 'show';
    }

    public function addIdentity()
    {
        $this->identity_documents[] = ['identity_id' => '', 'number' => ''];
    }

    public function removeIdentity($index)
    {
        unset($this->identity_documents[$index]);
        $this->identity_documents = array_values($this->identity_documents);
    }

    public function addPhone()
    {
        $this->phones[] = ['phone_id' => '', 'number' => ''];
    }

    public function removePhone($index)
    {
        unset($this->phones[$index]);
        $this->phones = array_values($this->phones);
    }

    public function addSocialMedia()
    {
        $this->socialMedia[] = ['social_media_id' => '', 'user_name' => '', 'url' => ''];
    }

    public function removeSocialMedia($index)
    {
        unset($this->socialMedia[$index]);
        $this->socialMedia = array_values($this->socialMedia);
    }

    public function addEmail() { $this->emails[] = ''; }
    public function removeEmail($index) { unset($this->emails[$index]); $this->emails = array_values($this->emails); }

    public function addAddress() { $this->addresses[] = ''; }
    public function removeAddress($index) { unset($this->addresses[$index]); $this->addresses = array_values($this->addresses); }

    public function addWorkPermit() { $this->workPermits[] = ''; }
    public function removeWorkPermit($index) { unset($this->workPermits[$index]); $this->workPermits = array_values($this->workPermits); }

    public function addNationality() { $this->nationalities[] = ''; }
    public function removeNationality($index) { unset($this->nationalities[$index]); $this->nationalities = array_values($this->nationalities); }

    public function render()
    {
        return view('livewire.cv-manager')->layout('layouts.app');
    }
}

