<?php

namespace App\Livewire;

use App\Mail\VacancyApplicationMail;
use App\Models\Vacancy;
use App\Notifications\NewCandidate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
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

        Mail::to(auth()->user()->email)->send(new VacancyApplicationMail($this->vacancy));

        session()->flash('message', __('Vacancy applied successfully'));

        return redirect()->back();
    }

    public function removeCv()
    {
        $user = auth()->user();
        $userCv = $user->vacancies()->where('vacancy_id', $this->vacancy->id)->value('cv');

        if ($userCv) {
            Storage::disk('public')->delete('cv/' . $userCv);

            $this->vacancy->users()->detach($user->id);

            session()->flash('message', __('Your CV has been removed successfully.'));
        } else {
            session()->flash('error', __('No CV found to remove.'));
        }
    }

    public function render()
    {
        $userCv = auth()->user()->vacancies()->where('vacancy_id', $this->vacancy->id)->value('cv');

        return view('livewire.apply-vacancy', compact('userCv'));
    }
}
