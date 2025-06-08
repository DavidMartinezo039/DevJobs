<?php

namespace App\Livewire\Admin;

use App\Models\DigitalSkill;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use Livewire\WithPagination;

class DigitalSkillManager extends Component
{
    use WithPagination;

    public  $createMode;
    public $name;
    public $digitalSkill;
    public $isEditMode = false;
    protected $listeners = ['delete'];

    protected $rules = [
        'name' => 'required|string|unique:digital_skills,name',
    ];

    public function resetInput()
    {
        $this->name = '';
        $this->isEditMode = false;
        $this->createMode = false;
        $this->resetPage();
        $this->resetValidation();
    }

    public function store()
    {
        $this->validate();

        $skill = DigitalSkill::create([
            'name' => $this->name,
        ]);

        session()->flash('message', __('Digital skill created successfully'));
        $this->resetInput();
    }

    public function edit(DigitalSkill $digitalSkill)
    {
        Gate::authorize('update', $digitalSkill);

        $this->digitalSkill = $digitalSkill;
        $this->name = $digitalSkill->name;
        $this->isEditMode = true;
    }

    public function update()
    {
        $this->validate([
            'name' => 'required|string|unique:digital_skills,name,' . $this->digitalSkill->id,
        ]);

        $this->digitalSkill->update([
            'name' => $this->name,
        ]);

        session()->flash('message', __('Digital skill updated successfully'));
        $this->resetInput();
    }

    public function confirmDelete($digitalSkill)
    {
        $this->dispatch('DeleteAlert', $digitalSkill);
    }

    public function delete(DigitalSkill $digitalSkill)
    {
        Gate::authorize('delete', $digitalSkill);

        $digitalSkill->delete();

        session()->flash('message', __('Digital skill deleted successfully'));
        $this->resetInput();
    }

    public function render()
    {

        return view('livewire.admin.digital-skill-manager', [
            'digitalSkills' => DigitalSkill::orderedByName()->paginate(10)
        ])->layout('layouts.app');
    }
}
