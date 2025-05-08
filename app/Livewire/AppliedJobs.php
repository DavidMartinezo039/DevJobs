<?php

namespace App\Livewire;

use Livewire\Component;

class AppliedJobs extends Component
{
    public function render()
    {
        $user = auth()->user();
        return view('livewire.applied-jobs' , [
        'user' => $user,
    ])->layout('layouts.app');
    }
}
