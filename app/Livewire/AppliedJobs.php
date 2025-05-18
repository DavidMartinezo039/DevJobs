<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Vacancy;
use Livewire\Component;
use Illuminate\Support\Facades\Gate;

class AppliedJobs extends Component
{
    public function render()
    {
        Gate::authorize('viewAnyPivot', Vacancy::class);

        $applications = auth()->user()->getAccessibleApplications();

        return view('livewire.applied-jobs' , [
        'applications' => $applications,
    ])->layout('layouts.app');
    }
}
