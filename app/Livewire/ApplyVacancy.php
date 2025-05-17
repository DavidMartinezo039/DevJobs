<?php

namespace App\Livewire;

use App\Events\VacancyApplied;
use App\Mail\ConfirmWithdrawMail;
use App\Mail\VacancyApplicationMail;
use App\Models\Vacancy;
use App\Notifications\NewCandidate;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\URL;

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
        Gate::authorize('createPivot', Vacancy::class);

        $data = $this->validate();

        $cv = $this->cv->store('cv', 'public');
        $data['cv'] = str_replace('cv/', '', $cv);

        $this->vacancy->users()->attach(auth()->id(), ['cv' => $data['cv']]);

        event(new VacancyApplied($this->vacancy, auth()->user()));

        session()->flash('message', __('Vacancy applied successfully'));

        return redirect()->back();
    }

    public function removeCv()
    {
        $user = auth()->user();

        $this->authorize('deletePivot', $this->vacancy);

        $url = URL::temporarySignedRoute(
            'vacancy.confirmWithdraw',
            now()->addMinutes(30),
            ['vacancy' => $this->vacancy->id]
        );

        Mail::to($user->email)->queue(new ConfirmWithdrawMail($this->vacancy, $url));

        session()->flash('message', __('We have sent an email with a link to confirm the deletion of your CV'));
    }


    public function render()
    {
        $userCv = auth()->user()->vacancies()->where('vacancy_id', $this->vacancy->id)->value('cv');

        return view('livewire.apply-vacancy', compact('userCv'));
    }
}
