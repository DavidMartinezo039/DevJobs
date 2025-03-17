<?php

namespace App\Livewire;

use App\Models\Vacancy;
use App\Notifications\NewCandidate;
use Livewire\Component;
use Livewire\WithFileUploads;

class ApplyVacancy extends Component
{
    use WithFileUploads;
    public $cv;
    public $vacancy;

    protected $rules = [
        'cv' => 'required|mimes:pdf',
    ];

    public function mount(Vacancy $vacancy)
    {
        $this->vacancy = $vacancy;
    }

    public function applyVacancy()
    {
        $data = $this->validate();

        $cv = $this->cv->store('cv', 'public');
        $data['cv'] = str_replace('cv/', '', $cv);

        $this->vacancy->users()->attach(auth()->id(), ['cv' => $data['cv']]);

        $this->vacancy->recruiter->notify(new NewCandidate($this->vacancy->id, $this->vacancy->title, auth()->id()));

        session()->flash('message', __('Vacancy applied successfully'));

        return redirect()->back();
    }

    public function render()
    {
        return view('livewire.apply-vacancy');
    }
}
