<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Salary;
use Livewire\Component;

class CreateVacancy extends Component
{
    public $title;

    protected $rules = [
        'title' => 'required|string',
    ];
    
    public function render()
    {
        $salaries = Salary::all();
        $categories = Category::all();
        return view('livewire.create-vacancy', ['salaries' => $salaries, 'categories' => $categories]);
    }
}
