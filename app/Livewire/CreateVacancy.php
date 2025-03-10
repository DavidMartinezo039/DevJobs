<?php

namespace App\Livewire;

use App\Http\Requests\VacancyRequest;
use App\Models\Category;
use App\Models\Salary;
use App\Models\Vacancy;
use Livewire\Component;

class CreateVacancy extends Component
{
    public $title;
    public $salary;
    public $category;
    public $company;
    public $last_day;
    public $description;
    public $image;

    protected function rules(): array
    {
        return (new VacancyRequest())->rules();
    }

    public function createVacancy()
    {
        $this->validate();
    }

    public function render()
    {
        $salaries = Salary::all();
        $categories = Category::all();
        return view('livewire.create-vacancy', ['salaries' => $salaries, 'categories' => $categories]);
    }
}
