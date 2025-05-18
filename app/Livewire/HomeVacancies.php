<?php

namespace App\Livewire;

use App\Models\Vacancy;
use Livewire\Component;

class HomeVacancies extends Component
{
    public $term;
    public $category;
    public $salary;

    protected $listeners = ['search' => 'search'];

    public function search($term, $category, $salary)
    {
        $this->term = $term;
        $this->category = $category;
        $this->salary = $salary;
    }

    public function render()
    {
        $vacancies = Vacancy::HomeVacancies([
            'term' => $this->term,
            'category' => $this->category,
            'salary' => $this->salary,
        ])->paginate(10);

        return view('livewire.home-vacancies', ['vacancies' => $vacancies]);
    }
}
