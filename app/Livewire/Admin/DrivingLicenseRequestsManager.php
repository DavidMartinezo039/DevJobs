<?php

namespace App\Livewire\Admin;

use App\Models\EditRequest;
use Livewire\Component;

class DrivingLicenseRequestsManager extends Component
{
    public function approve($request)
    {
        $request->approved = true;
        $request->save();

        session()->flash('message', __('Request approved. The moderator can now proceed.'));
    }

    public function reject($request)
    {
        $request->approved = false;
        $request->save();

        session()->flash('message', __('Request rejected.'));
    }

    public function render()
    {
        return view('livewire.admin.driving-license-requests-manager', [
            'requests' => EditRequest::with(['user', 'drivingLicense'])
                ->where('approved', null)
                ->get(),
        ])->layout('layouts.app');
    }
}
