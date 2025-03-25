<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\CV;

class CvManager extends Component
{
    use WithFileUploads;

    public $cvs, $title, $description, $selectedCv;
    public $view = 'index';

    public function mount()
    {
        $this->cvs = CV::all();
    }

    public function index()
    {
        $this->mount();
        $this->view = 'index';
    }


    public function create()
    {
        $this->resetErrorBag();
        $this->resetValidation();
        $this->reset(['title', 'description']);
        $this->view = 'create';
    }

    public function store()
    {
        $this->validate([
            'title' => 'required',
            'description' => 'required',
        ]);

        CV::create(['title' => $this->title, 'description' => $this->description]);

        $this->reset(['title', 'description']);
        $this->mount();
        $this->view = 'index';
    }

    public function edit(CV $cv)
    {
        $this->selectedCv = $cv;
        $this->title = $cv->title;
        $this->description = $cv->description;
        $this->view = 'edit';
    }

    public function update()
    {
        $this->validate([
            'title' => 'required',
            'description' => 'required',
        ]);

        $this->selectedCv->update(['title' => $this->title, 'description' => $this->description]);

        $this->mount();
        $this->view = 'index';
    }

    public function delete(CV $cv)
    {
        $cv->delete();
        $this->mount();
    }

    public function show(CV $cv)
    {
        $this->selectedCv = $cv;
        $this->view = 'show';
    }

    public function render()
    {
        return view('livewire.cv-manager')->layout('layouts.app');
    }
}
