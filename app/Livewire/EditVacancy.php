<?php

namespace App\Livewire;

use App\Http\Requests\VacancyUpdateRequest;
use App\Models\Category;
use App\Models\Salary;
use App\Models\Vacancy;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithFileUploads;

class EditVacancy extends Component
{
    public $vacancy;
    public $title;
    public $salary;
    public $category;
    public $company;
    public $last_day;
    public $description;
    public $image;
    public $new_image;

    use WithFileUploads;

    public function mount(Vacancy $vacancy)
    {
        $this->vacancy = $vacancy;
        $this->title = $vacancy->title;
        $this->salary = $vacancy->salary_id;
        $this->category = $vacancy->category_id;
        $this->company = $vacancy->company;
        $this->last_day = Carbon::parse($vacancy->last_day)->format('Y-m-d');
        $this->description = $vacancy->description;
        $this->image = $vacancy->image;
    }

    protected function rules(): array
    {
        return (new VacancyUpdateRequest())->rules();
    }

    public function editVacancy()
    {
        $validateDatta = $this->validate();

        if ($this->new_image) {
            $image = $this->new_image->store('vacancies', 'public');
            $validateDatta['image'] = str_replace('vacancies/', '', $image);
        }

        $this->vacancy->update($validateDatta);

        session()->flash('message', __('Vacancy updated successfully'));

        return redirect()->route('vacancies.index');
    }

    public function render()
    {
        $salaries = Salary::all();
        $categories = Category::all();
        return view('livewire.edit-vacancy', ['salaries' => $salaries, 'categories' => $categories]);
    }
}
