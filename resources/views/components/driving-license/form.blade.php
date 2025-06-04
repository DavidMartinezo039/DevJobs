@props([
    'isEditMode'
])

<div>
    <h2 class="text-2xl font-bold mb-4">
        {{ $isEditMode ? __('Edit Driving License') : __('Create Driving License') }}
    </h2>

    <form wire:submit.prevent="{{ $isEditMode ? 'update' : 'store' }}">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <x-forms.input id="category" name="category" label="{{ __('Category') }}" wireModel="category"/>
            <x-forms.input id="vehicle_type" name="vehicle_type" label="{{ __('Vehicle Type') }}" wireModel="vehicle_type"/>
            <x-forms.input id="max_speed" name="max_speed" label="{{ __('Max Speed (km/h)') }}" type="number" wireModel="max_speed"/>
            <x-forms.input id="max_power" name="max_power" label="{{ __('Max Power (kW)') }}" type="number" wireModel="max_power"/>
            <x-forms.input id="power_to_weight" name="power_to_weight" label="{{ __('Power to Weight Ratio') }}" type="number" step="0.01" wireModel="power_to_weight"/>
            <x-forms.input id="max_weight" name="max_weight" label="{{ __('Max Weight (kg)') }}" type="number" wireModel="max_weight"/>
            <x-forms.input id="max_passengers" name="max_passengers" label="{{ __('Max Passengers') }}" type="number" wireModel="max_passengers"/>
            <x-forms.input id="min_age" name="min_age" label="{{ __('Minimum Age') }}" type="number" wireModel="min_age"/>
        </div>

        <div class="mt-4 flex gap-2">
            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                {{ $isEditMode ? __('Update') : __('Create') }}
            </button>

            <button type="button" wire:click="resetInput"
                    class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                {{ __('Cancel') }}
            </button>
        </div>
    </form>
</div>
