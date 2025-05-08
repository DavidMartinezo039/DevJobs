<?php

namespace App\Livewire;

use App\Http\Requests\VacancyRequest;
use App\Http\Requests\VacancyUpdateRequest;
use App\Models\Category;
use App\Models\Salary;
use App\Models\Vacancy;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Gate;

class VacanciesManager extends Component
{
    use WithFileUploads;

    public $view = 'index';
    protected $listeners = ['deleteVacancy'];

    public $salaries, $categories;

    public $vacancy;
    public $title;
    public $salary;
    public $category;
    public $company;
    public $last_day;
    public $description;
    public $image;
    public $new_image;

    public function mount()
    {
        Gate::authorize('viewAny', Vacancy::class);

        $this->salaries = Salary::all();
        $this->categories = Category::all();

        $this->reset([
            'vacancy',
            'title',
            'salary',
            'category',
            'company',
            'last_day',
            'description',
            'image',
            'new_image',
        ]);
    }
    public function index()
    {
        $this->mount();
        $this->view = 'index';
    }

    public function create()
    {
        Gate::authorize('create', Vacancy::class);
        $this->view = 'create';
    }

    public function edit(Vacancy $vacancy)
    {
        Gate::authorize('update', $vacancy);
        $this->vacancy = $vacancy;
        $this->title = $vacancy->title;
        $this->salary = $vacancy->salary_id;
        $this->category = $vacancy->category_id;
        $this->company = $vacancy->company;
        $this->last_day = Carbon::parse($vacancy->last_day)->format('Y-m-d');
        $this->description = $vacancy->description;
        $this->image = $vacancy->image;

        $this->view = 'edit';
    }

    public function show(Vacancy $vacancy)
    {
        Gate::authorize('view', $vacancy);
        $this->vacancy = $vacancy;
        $this->view = 'show';
    }

    public function confirmDelete($vacancy)
    {
        $this->dispatch('DeleteAlert', $vacancy);
    }

    public function deleteVacancy(Vacancy $vacancy)
    {
        Gate::authorize('delete', $vacancy);
        $vacancy->delete();
    }

    protected function rulesCreate(): array
    {
        return (new VacancyRequest())->rules();
    }

    protected function rulesEdit(): array
    {
        return (new VacancyUpdateRequest())->rules();
    }


    public function store()
    {
        $validateDatta = $this->validate($this->rulesCreate());

        $image = $this->image->store('vacancies', 'public');
        $image_name = str_replace('vacancies/', '', $image);

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

        $this->index();
    }

    public function update()
    {
        $validateDatta = $this->validate($this->rulesEdit());

        if ($this->new_image) {
            $image = $this->new_image->store('vacancies', 'public');
            $validateDatta['image'] = str_replace('vacancies/', '', $image);
        }

        $this->vacancy->update($validateDatta);

        session()->flash('message', __('Vacancy updated successfully'));

        $this->index();
    }

    public function render()
    {
        $vacancies = Vacancy::VacanciesByRol()->paginate(10);
        return view('livewire.vacancies-manager', [
            'vacancies' => $vacancies
        ])->layout('layouts.app');
    }
}
