<?php

namespace App\Livewire;

use App\Events\CandidateWithdrew;
use App\Models\Vacancy;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class ConfirmWithdraw extends Component
{
    public Vacancy $vacancy;

    public function mount(Vacancy $vacancy)
    {
        $this->vacancy = $vacancy;
    }

    public function withdraw()
    {
        $user = Auth::user();
        $cv = $user->vacancies()->where('vacancy_id', $this->vacancy->id)->value('cv');

        if ($cv) {
            Storage::disk('public')->delete('cv/' . $cv);
            $this->vacancy->users()->detach($user->id);

            event(new CandidateWithdrew($this->vacancy, $user));


            session()->flash('message', __('Has cancelado tu participación en esta vacante'));
        }

        return redirect()->route('home');
    }

    public function render()
    {
        return view('livewire.confirm-withdraw')->layout('layouts.app');
    }
}
