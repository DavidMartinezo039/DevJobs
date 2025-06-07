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
use App\Traits\LogsActivity;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\CV;

class CvManager extends Component
{
    use WithFileUploads;
    use LogsActivity;


    public $title, $first_name, $last_name, $image, $new_image, $about_me, $birth_date, $city, $country, $gender_id;
    public $workPermits = [], $nationalities = [], $emails = [], $addresses = [];

    public $identity_documents = [], $phones = [], $socialMedia = [], $workExperiences = [], $educations = [], $languages = [], $skills = [], $drivingLicenses = [];

    public $cvs, $selectedCv, $personalData;
    public $genders = [], $identityTypes = [], $phoneTypes = [], $socialMediaTypes = [], $languages_options = [], $skills_options = [], $drivingLicenses_options = [];
    public $availableSections = [
        'work_experience' => 'Work Experience',
        'education' => 'Education',
        'languages' => 'Languages',
        'skills' => 'Skills',
        'driving_licenses' => 'Driving Licenses',
    ];
    public $activeSections = [], $view = 'index';

    protected $listeners = ['delete'];

    /**
     * Mount the component and initialize data.
     */
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

        $this->identity_documents = [['identity_id' => '', 'number' => '']];
        $this->phones = [['phone_id' => '', 'number' => '']];
        $this->socialMedia = [['social_media_id' => '', 'user_name' => '', 'url' => '']];
    }

    /**
     * Render the component's view.
     */
    public function render()
    {
        return view('livewire.cv-manager')->layout('layouts.app');
    }

    /*
    |--------------------------------------------------------------------------
    | View Management
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $this->resetComponentData();
        $this->mount();
        $this->view = 'index';
    }

    public function create()
    {
        Gate::authorize('create', CV::class);
        $this->resetComponentData();
        $this->view = 'create';
    }

    public function show(CV $cv)
    {
        Gate::authorize('view', $cv);

        $this->selectedCv = $cv;
        $this->personalData = $cv->personalData;
        $this->view = 'show';
    }

    public function edit(CV $cv)
    {
        Gate::authorize('update', $cv);
        $this->resetComponentData();
        $this->selectedCv = $cv;
        $this->fillCvData($cv);
        $this->view = 'edit';
    }

    /*
    |--------------------------------------------------------------------------
    | CRUD Operations
    |--------------------------------------------------------------------------
    */

    public function store()
    {
        $this->validate((new CvCreateRequest())->rules());


        $cv = CV::create(['title' => $this->title, 'user_id' => auth()->id()]);

        $this->savePersonalData($cv);
        $this->saveRelations($cv);

        $this->logActivity(
            action: 'created_cv',
            targetType: 'App\Models\CV',
            targetId: $cv->id,
            description: 'Creo un cv'
        );

        GenerateCVPdf::dispatch($cv);

        $this->resetComponentData();
        $this->mount();
        $this->view = 'index';
    }

    public function update()
    {
        $this->validate((new CvUpdateRequest())->rules());

        $cv = $this->selectedCv;
        $cv->update(['title' => $this->title]);

        $this->updatePersonalData($cv);
        $this->syncRelations($cv);

        $this->logActivity(
            action: 'updated_cv',
            targetType: 'App\Models\CV',
            targetId: $cv->id,
            description: 'Actualizó un cv'
        );

        GenerateCVPdf::dispatch($cv);

        $this->resetComponentData();
        $this->mount();
        $this->view = 'index';
    }

    public function confirmDelete($cv)
    {
        $this->dispatch('DeleteAlert', $cv);
    }

    public function delete(CV $cv)
    {
        Gate::authorize('delete', $cv);

        $this->deleteAssociatedFiles($cv);

        $this->logActivity(
            action: 'deleted_cv',
            targetType: 'App\Models\CV',
            targetId: $cv->id,
            description: 'Eliminó un cv'
        );

        $cv->delete();
        $this->mount();
    }

    /*
    |--------------------------------------------------------------------------
    | Dynamic Section Management
    |--------------------------------------------------------------------------
    */

    public function addSection($section)
    {
        if (!in_array($section, $this->activeSections)) {
            $this->activeSections[] = $section;
            $this->addEntry($section);
        }
    }

    public function removeSection($section)
    {
        $this->activeSections = array_filter($this->activeSections, fn($s) => $s !== $section);
        $this->clearSectionData($section);
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

    /*
    |--------------------------------------------------------------------------
    | Dynamic Field Management (e.g., Identities, Phones)
    |--------------------------------------------------------------------------
    */

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

    public function addEmail()
    {
        $this->emails[] = '';
    }

    public function removeEmail($index)
    {
        unset($this->emails[$index]);
        $this->emails = array_values($this->emails);
    }

    public function addAddress()
    {
        $this->addresses[] = '';
    }

    public function removeAddress($index)
    {
        unset($this->addresses[$index]);
        $this->addresses = array_values($this->addresses);
    }

    public function addWorkPermit()
    {
        $this->workPermits[] = '';
    }

    public function removeWorkPermit($index)
    {
        unset($this->workPermits[$index]);
        $this->workPermits = array_values($this->workPermits);
    }

    public function addNationality()
    {
        $this->nationalities[] = '';
    }

    public function removeNationality($index)
    {
        unset($this->nationalities[$index]);
        $this->nationalities = array_values($this->nationalities);
    }

    /*
    |--------------------------------------------------------------------------
    | Private Helper Methods
    |--------------------------------------------------------------------------
    */

    private function resetComponentData()
    {
        $this->reset([
            'title', 'first_name', 'last_name', 'birth_date', 'city', 'country', 'about_me',
            'emails', 'addresses', 'workPermits', 'nationalities', 'image', 'new_image', 'gender_id',
            'identity_documents', 'phones', 'socialMedia',
            'workExperiences', 'educations', 'languages', 'skills', 'drivingLicenses',
            'selectedCv', 'personalData', 'activeSections',
        ]);

        // Re-initialize dynamic fields to have at least one empty entry for forms
        $this->identity_documents = [['identity_id' => '', 'number' => '']];
        $this->phones = [['phone_id' => '', 'number' => '']];
        $this->socialMedia = [['social_media_id' => '', 'user_name' => '', 'url' => '']];

        $this->resetErrorBag();
        $this->resetValidation();
    }

    private function savePersonalData(CV $cv)
    {
        $imagePath = $this->image ? basename($this->image->store('images', 'public')) : null;

        PersonalData::create([
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
    }

    private function updatePersonalData(CV $cv)
    {
        $oldImageName = $cv->personalData->image;
        $imageNameToStore = $oldImageName;

        if ($this->new_image) {
            $fullNewImagePath = $this->new_image->store('images', 'public');
            $imageNameToStore = basename($fullNewImagePath);

            if ($oldImageName && Storage::disk('public')->exists('images/' . $oldImageName)) {
                Storage::disk('public')->delete('images/' . $oldImageName);
            }
        }

        $cv->personalData->update([
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
            'image' => $imageNameToStore,
            'gender_id' => $this->gender_id,
        ]);
    }

    private function saveRelations(CV $cv)
    {
        $personalData = $cv->personalData;

        $this->attachPivotData($personalData->identities(), $this->identity_documents, ['identity_id' => 'id', 'number' => 'number']);
        $this->attachPivotData($personalData->phones(), $this->phones, ['phone_id' => 'id', 'number' => 'number']);
        $this->attachPivotData($personalData->socialMedia(), $this->socialMedia, ['social_media_id' => 'id', 'user_name' => 'user_name', 'url' => 'url']);

        $this->createHasManyData($cv->workExperiences(), $this->workExperiences, WorkExperience::class, [
            'company_name' => 'company', 'position' => 'position', 'start_date' => 'start', 'end_date' => 'end', 'description' => 'description'
        ]);
        $this->createHasManyData($cv->education(), $this->educations, Education::class, [
            'institution' => 'school', 'title' => 'degree', 'start_date' => 'start', 'end_date' => 'end', 'city' => 'city', 'country' => 'country'
        ]);

        $this->attachPivotData($cv->languages(), $this->languages, ['language_id' => 'id', 'level' => 'level']);
        $this->attachPivotData($cv->digitalSkills(), $this->skills, ['digital_skill_id' => 'id', 'level' => 'level']);
        $this->attachPivotData($cv->drivingLicenses(), $this->drivingLicenses, ['driving_license_id' => 'id']);
    }

    private function syncRelations(CV $cv)
    {
        $personalData = $cv->personalData;

        $this->syncPivotData($personalData->identities(), $this->identity_documents, ['identity_id' => 'id', 'number' => 'number']);
        $this->syncPivotData($personalData->phones(), $this->phones, ['phone_id' => 'id', 'number' => 'number']);
        $this->syncPivotData($personalData->socialMedia(), $this->socialMedia, ['social_media_id' => 'id', 'user_name' => 'user_name', 'url' => 'url']);

        $this->syncHasManyData($cv->workExperiences(), $this->workExperiences, [
            'company_name' => 'company', 'position' => 'position', 'start_date' => 'start', 'end_date' => 'end', 'description' => 'description'
        ]);
        $this->syncHasManyData($cv->education(), $this->educations, [
            'institution' => 'school', 'title' => 'degree', 'start_date' => 'start', 'end_date' => 'end', 'city' => 'city', 'country' => 'country'
        ]);

        $this->syncPivotData($cv->languages(), $this->languages, ['language_id' => 'id', 'level' => 'level']);
        $this->syncPivotData($cv->digitalSkills(), $this->skills, ['digital_skill_id' => 'id', 'level' => 'level']);
        $this->syncPivotData($cv->drivingLicenses(), $this->drivingLicenses, ['driving_license_id' => 'id']);
    }

    private function attachPivotData($relation, array $data, array $fieldMap)
    {
        foreach ($data as $item) {
            list($idValue, $pivotData) = $this->extractPivotData($item, $fieldMap);

            if (empty($idValue)) {
                continue;
            }

            if (empty($pivotData)) {
                $relation->attach($idValue);
            } else {
                $relation->attach($idValue, $pivotData);
            }
        }
    }

    private function syncPivotData($relation, array $data, array $fieldMap)
    {
        $syncData = [];
        foreach ($data as $item) {
            list($idValue, $pivotData) = $this->extractPivotData($item, $fieldMap);

            if (empty($idValue)) {
                continue;
            }
            $syncData[$idValue] = $pivotData;
        }
        $relation->sync($syncData);
    }

    private function extractPivotData(array $item, array $fieldMap): array
    {
        $idField = array_key_first($fieldMap); // e.g., 'identity_id'
        $idValue = $item[$idField] ?? null;

        $pivotData = [];
        foreach ($fieldMap as $dbField => $propField) {
            if ($dbField !== $idField) { // Don't include the ID field in pivot data
                $pivotData[$dbField] = $item[$propField] ?? null;
            }
        }
        return [$idValue, $pivotData];
    }

    private function createHasManyData($relation, array $data, string $modelClass, array $fieldMap)
    {
        foreach ($data as $item) {
            $modelData = [];
            foreach ($fieldMap as $dbField => $propField) {
                $modelData[$dbField] = $item[$propField] ?? null;
            }
            $relation->create($modelData);
        }
    }

    private function syncHasManyData($relation, array $data, array $fieldMap)
    {
        $relation->delete(); // Delete existing records
        foreach ($data as $item) {
            $modelData = [];
            foreach ($fieldMap as $dbField => $propField) {
                $modelData[$dbField] = $item[$propField] ?? null;
            }
            $relation->create($modelData); // Recreate them
        }
    }

    private function fillCvData(CV $cv)
    {
        $this->title = $cv->title;
        $personalData = $cv->personalData;

        $this->image = $personalData->image ?? null;
        $this->first_name = $personalData->first_name;
        $this->last_name = $personalData->last_name;
        $this->birth_date = $personalData->birth_date;
        $this->city = $personalData->city;
        $this->country = $personalData->country;
        $this->about_me = $personalData->about_me;
        $this->emails = $personalData->email ?? [''];
        $this->addresses = $personalData->address ?? [''];
        $this->workPermits = $personalData->workPermits ?? [''];
        $this->nationalities = $personalData->nationality ?? [''];
        $this->gender_id = $personalData->gender_id;

        $this->identity_documents = $personalData->identities->map(fn($identity) => [
            'identity_id' => $identity->id,
            'number' => $identity->pivot->number,
        ])->toArray();
        if (empty($this->identity_documents)) $this->identity_documents = [['identity_id' => '', 'number' => '']];

        $this->phones = $personalData->phones->map(fn($phone) => [
            'phone_id' => $phone->id,
            'number' => $phone->pivot->number,
        ])->toArray();
        if (empty($this->phones)) $this->phones = [['phone_id' => '', 'number' => '']];

        $this->socialMedia = $personalData->socialMedia->map(fn($socialmedia) => [
            'social_media_id' => $socialmedia->id,
            'user_name' => $socialmedia->pivot->user_name,
            'url' => $socialmedia->pivot->url,
        ])->toArray();
        if (empty($this->socialMedia)) $this->socialMedia = [['social_media_id' => '', 'user_name' => '', 'url' => '']];

        $this->workExperiences = $cv->workExperiences->map(fn($experience) => [
            'company' => $experience->company_name,
            'position' => $experience->position,
            'start' => $experience->start_date,
            'end' => $experience->end_date,
            'description' => $experience->description,
        ])->toArray();
        $this->addSectionIfDataExists('work_experience', $this->workExperiences);


        $this->educations = $cv->education->map(fn($education) => [
            'school' => $education->institution,
            'city' => $education->city,
            'country' => $education->country,
            'degree' => $education->title,
            'start' => $education->start_date,
            'end' => $education->end_date,
        ])->toArray();
        $this->addSectionIfDataExists('education', $this->educations);

        $this->languages = $cv->languages->map(fn($language) => [
            'language_id' => $language->id,
            'level' => $language->pivot->level,
        ])->toArray();
        $this->addSectionIfDataExists('languages', $this->languages);

        $this->skills = $cv->digitalSkills->map(fn($skill) => [
            'digital_skill_id' => $skill->id,
            'level' => $skill->pivot->level,
        ])->toArray();
        $this->addSectionIfDataExists('skills', $this->skills);

        $this->drivingLicenses = $cv->drivingLicenses->map(fn($drivingLicense) => [
            'driving_license_id' => $drivingLicense->id,
        ])->toArray();
        $this->addSectionIfDataExists('driving_licenses', $this->drivingLicenses);
    }

    private function addSectionIfDataExists(string $sectionName, array $data)
    {
        if (count($data) > 0 && !in_array($sectionName, $this->activeSections)) {
            $this->activeSections[] = $sectionName;
        }
    }

    private function clearSectionData(string $section)
    {
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

    private function deleteAssociatedFiles(CV $cv)
    {
        if ($cv->file_path) {
            Storage::disk('public')->delete('cv/' . $cv->file_path);
        }

        if ($cv->personalData && $cv->personalData->image) {
            Storage::disk('public')->delete('images/' . $cv->personalData->image);
        }
    }
}
