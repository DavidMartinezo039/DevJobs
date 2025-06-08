<?php

namespace App\Livewire\Admin;

use App\Events\DrivingLicenseEditRequested;
use App\Http\Requests\DrivingLicenseRequest;
use App\Models\DrivingLicense;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use Livewire\WithPagination;

class DrivingLicenseManager extends Component
{
    use WithPagination;

    public $createMode = false;
    public $isEditMode = false;
    public $isShowMode = false;

    public $drivingLicense;

    public $category;
    public $vehicle_type;
    public $max_speed;
    public $max_power;
    public $power_to_weight;
    public $max_weight;
    public $max_passengers;
    public $min_age;

    protected $listeners = ['delete'];

    public function resetInput()
    {
        $this->reset([
            'category', 'vehicle_type', 'max_speed', 'max_power', 'power_to_weight',
            'max_weight', 'max_passengers', 'min_age', 'isEditMode', 'createMode', 'drivingLicense',
        ]);
        $this->isEditMode = false;
        $this->isShowMode = false;
        $this->createMode = false;
        $this->resetPage();
        $this->resetValidation();
    }

    public function rules()
    {
        return (new DrivingLicenseRequest())->rules();
    }

    public function store()
    {
        $validated = $this->validate();

        $validated['only_god'] = auth()->user()->hasRole('god');


        DrivingLicense::create($validated);

        session()->flash('message', __('Driving license created successfully'));
        $this->resetInput();
    }

    public function edit(DrivingLicense $drivingLicense)
    {
        $this->drivingLicense = $drivingLicense;

        if (!Gate::allows('update', $this->drivingLicense)) {
            DrivingLicenseEditRequested::dispatch(auth()->user(), $this->drivingLicense);

            session()->flash('message', __('Edit request sent. Waiting for god approval.'));
            return;
        }

        $this->fill($drivingLicense->only([
            'category', 'vehicle_type', 'max_speed', 'max_power',
            'power_to_weight', 'max_weight', 'max_passengers', 'min_age'
        ]));

        $this->isEditMode = true;
    }

    public function show(DrivingLicense $drivingLicense)
    {
        $this->drivingLicense = $drivingLicense;

        $this->fill($drivingLicense->only([
            'category', 'vehicle_type', 'max_speed', 'max_power',
            'power_to_weight', 'max_weight', 'max_passengers', 'min_age'
        ]));

        $this->isShowMode = true;
        $this->isEditMode = false;
        $this->createMode = false;
    }

    public function update()
    {
        $validated = $this->validate();

        $validated['only_god'] = auth()->user()->hasRole('god');

        $this->drivingLicense->update($validated);

        session()->flash('message', __('Driving license updated successfully'));
        $this->resetInput();
    }

    public function confirmDelete($id)
    {
        $this->dispatch('DeleteAlert', $id);
    }

    public function delete(DrivingLicense $drivingLicense)
    {
        if (!Gate::allows('delete', $drivingLicense)) {
            DrivingLicenseEditRequested::dispatch(auth()->user(), $drivingLicense);

            session()->flash('message', __('Delete request sent. Waiting for god approval.'));
            return;
        }        $drivingLicense->delete();

        session()->flash('message', __('Driving license deleted successfully.'));
        $this->resetInput();
    }

    public function render()
    {
        return view('livewire.admin.driving-license-manager', [
            'drivingLicenses' => DrivingLicense::orderedByCategory()->paginate(10),
        ])->layout('layouts.app');
    }
}
