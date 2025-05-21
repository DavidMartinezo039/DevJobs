<?php

namespace App\Livewire\Admin;

use App\Http\Requests\StoreGenderRequest;
use App\Http\Requests\UpdateGenderRequest;
use App\Jobs\NotifyMarketingUsersOfGenderChange;
use App\Jobs\NotifyModeratorsOfDefaultGender;
use App\Models\Gender;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;

class GendersManager extends Component
{
    public $genders, $type, $gender, $createMode;
    public $isEditMode = false;

    public $pendingChanges = [];
    protected $listeners = ['deleteGender'];

    public $genderToDelete = null;


    protected $rules = [
        'type' => 'required|string|unique:genders,type',
    ];

    public function resetInput()
    {
        $this->type = '';
        $this->gender = null;
        $this->isEditMode = false;
        $this->createMode = false;
        $this->resetValidation();
    }

    public function markForToggle($genderId)
    {
        if (isset($this->pendingChanges[$genderId])) {
            unset($this->pendingChanges[$genderId]);
        } else {
            $this->pendingChanges[$genderId] = true;
        }
    }

    public function saveChanges()
    {
        foreach ($this->pendingChanges as $genderId => $value) {
            $gender = Gender::find($genderId);

            if ($gender && auth()->user()->can('toggleDefault', $gender)) {
                $gender->is_default = !$gender->is_default;
                $gender->save();
            }
        }

        NotifyModeratorsOfDefaultGender::dispatch($gender);

        $this->pendingChanges = [];
        session()->flash('message', __('Changes saved successfully'));
    }


    public function store()
    {
        $request = new StoreGenderRequest();
        $this->validate($request->rules());

        $gender = Gender::create([
            'type' => $this->type,
        ]);
        $this->pendingChanges[$gender->id] = true;

        NotifyMarketingUsersOfGenderChange::dispatch($gender, 'created');

        session()->flash('message', __('Gender created successfully'));

        $this->resetInput();
    }

    public function edit(Gender $gender)
    {
        Gate::authorize('update', $gender);

        $this->gender = $gender;
        $this->type = $gender->type;
        $this->isEditMode = true;
    }

    public function update()
    {
        $request = new UpdateGenderRequest();
        $this->validate($request->rules());

        $this->gender->update([
                'type' => $this->type,
            ]);

        NotifyMarketingUsersOfGenderChange::dispatch($this->gender, 'updated');

        session()->flash('message', __('Gender updated successfully'));
            $this->resetInput();
    }

    public function confirmDelete($gender)
    {
        $this->dispatch('DeleteAlert', $gender);
    }

    public function deleteGender(Gender $gender)
    {
        Gate::authorize('delete', $gender);

        if (isset($this->pendingChanges[$gender->id])) {
            unset($this->pendingChanges[$gender->id]);
        }

        NotifyMarketingUsersOfGenderChange::dispatch($gender, 'deleted');

        $gender->delete();

        $this->resetInput();

        session()->flash('message', __('Gender deleted successfully'));
    }

    public function render()
    {
        $this->genders = Gender::orderBy('type')->get();
        return view('livewire.admin.genders-manager')->layout('layouts.app');
    }
}
