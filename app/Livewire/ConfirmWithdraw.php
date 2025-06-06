<?php

namespace App\Livewire;

use App\Events\CandidateWithdrew;
use App\Models\Vacancy;
use App\Traits\LogsActivity;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class ConfirmWithdraw extends Component
{
    use LogsActivity;
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

            $this->logActivity(
                action: 'withdraw_application',
                targetType: 'App\Models\Vacancy',
                targetId: $this->vacancy->id,
                description: "User {$user->name} withdrew from vacancy '{$this->vacancy->title}'"
            );

            event(new CandidateWithdrew($this->vacancy, $user));


            session()->flash('message', __('You have canceled your participation in this vacancy'));
        }

        return redirect()->route('home');
    }

    public function render()
    {
        return view('livewire.confirm-withdraw')->layout('layouts.app');
    }
}
