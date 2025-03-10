<?php

namespace App\Livewire;

use App\Http\Requests\VacancyRequest;
use App\Models\Category;
use App\Models\Salary;
use App\Models\Vacancy;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreateVacancy extends Component
{
    public $title;
    public $salary;
    public $category;
    public $company;
    public $last_day;
    public $description;
    public $image;

    use WithFileUploads;

    protected function rules(): array
    {
        return (new VacancyRequest())->rules();
    }

    public function createVacancy()
    {
        $validateDatta = $this->validate();

        $image = $this->image->store('public/vacancies');
        $image_name = str_replace('public/vacancies/', '', $image);

        Vacancy::create([
            'title' => $validateDatta['title'],
            'salary_id' => $validateDatta['salary'],
            'category_id' => $validateDatta['category'],
            'user_id' => auth()->id(),
            'company' => $validateDatta['company'],
            'last_day' => $validateDatta['last_day'],
            'description' => $validateDatta['description'],
            'image' => $image_name,
        ]);

        session()->flash('message', __('Vacancy added successfully'));

        return redirect()->route('vacancies.index');
    }

    public function render()
    {
        $salaries = Salary::all();
        $categories = Category::all();
        return view('livewire.create-vacancy', ['salaries' => $salaries, 'categories' => $categories]);
    }
}
