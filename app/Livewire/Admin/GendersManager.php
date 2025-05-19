<?php

namespace App\Livewire\Admin;

use App\Models\Gender;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;

class GendersManager extends Component
{
    public $genders, $type, $gender, $createMode;
    public $isEditMode = false;

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

    public function store()
    {
        $this->validate();

        Gender::create([
            'type' => $this->type,
        ]);

        session()->flash('message', 'Gender created successfully.');

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
        $this->validate([
            'type' => 'required|string|unique:genders'
        ]);

            $this->gender->update([
                'type' => $this->type,
            ]);
            session()->flash('message', 'Gender updated successfully.');
            $this->resetInput();
    }

    public function delete(Gender $gender)
    {
        Gate::authorize('delete', $gender);

        $gender->delete();
        session()->flash('message', 'Gender deleted successfully.');
    }

    public function toggleDefault(Gender $gender)
    {
        Gate::authorize('toggleDefault', Gender::class);

        $gender->is_default = !$gender->is_default;
        $gender->save();

        session()->flash('message', 'Default status updated.');
    }

    public function render()
    {
        $this->genders = Gender::orderBy('type')->get();
        return view('livewire.admin.genders-manager')->layout('layouts.app');
    }
}
