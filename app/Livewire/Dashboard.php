<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Dashboard extends Component
{
    public $user;

    public function mount()
    {
        $this->user = Auth::user();
    }

    public function backup()
    {
        Artisan::call('backup:database');
        session()->flash('success', __('Database backup generated'));
    }

    public function render()
    {
        return view('livewire.dashboard')->layout('layouts.app');
    }
}
