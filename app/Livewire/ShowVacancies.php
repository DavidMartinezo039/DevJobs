<?php

namespace App\Livewire;

use App\Models\Vacancy;
use Livewire\Component;

class ShowVacancies extends Component
{
    public function render()
    {
        $vacancies = Vacancy::where('user_id', auth()->id())->paginate(10);
        return view('livewire.show-vacancies', [
            'vacancies' => $vacancies
        ]);
    }
}
