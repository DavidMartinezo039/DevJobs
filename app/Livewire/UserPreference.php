<?php

namespace App\Livewire;

use App\Http\Requests\UpdateUserPreferenceRequest;
use App\Models\Category;
use App\Models\Salary;
use Livewire\Component;

class UserPreference extends Component
{
    public $category;
    public $salary;
    public $company;
    public $keyword;
    public $categories;
    public $salaries;

    public function mount()
    {
        $pref = auth()->user()->preference;
        $this->categories = Category::all();
        $this->salaries = Salary::all();

        if ($pref) {
            $this->category = $pref->category_id;
            $this->salary = $pref->salary_id;
            $this->company = $pref->company;
            $this->keyword = $pref->keyword;
        }
    }

    public function save()
    {
        $rules = (new UpdateUserPreferenceRequest())->rules();

        $validatedData = $this->validate($rules);

        $mappedData = [
            'salary_id' => $validatedData['salary'] !== '' ? $validatedData['salary'] : null,
            'category_id' => $validatedData['category'] !== '' ? $validatedData['category'] : null,
            'company' => $validatedData['company'],
            'keyword' => $validatedData['keyword'],
        ];

        auth()->user()->preference()->updateOrCreate([], $mappedData);

        session()->flash('success', __('Preferences updated successfully'));
    }

    public function render()
    {
        return view('livewire.user-preference')->layout('layouts.app');
    }
}
