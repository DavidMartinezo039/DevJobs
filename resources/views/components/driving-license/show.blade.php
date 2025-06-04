@props([
    'category',
    'vehicle_type',
    'max_speed',
    'max_power',
    'power_to_weight',
    'max_weight',
    'max_passengers',
    'min_age'
])

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-3">
            <div class="p-10">
                <div class="mb-5">
                    <h3 class="font-bold text-3xl text-gray-800 my-3">
                        {{ __('Driving License') }} - {{ $category }}
                    </h3>

                    <div class="md:grid md:grid-cols-2 bg-gray-50 p-4 my-10 gap-4">
                        <p class="font-bold text-sm uppercase text-gray-800 my-3">{{ __('Vehicle Type') }}:
                            <span class="normal-case font-normal">{{ $vehicle_type }}</span>
                        </p>

                        <p class="font-bold text-sm uppercase text-gray-800 my-3">{{ __('Max Speed') }}:
                            <span class="normal-case font-normal">{{ $max_speed }} km/h</span>
                        </p>

                        <p class="font-bold text-sm uppercase text-gray-800 my-3">{{ __('Max Power') }}:
                            <span class="normal-case font-normal">{{ $max_power }} HP</span>
                        </p>

                        <p class="font-bold text-sm uppercase text-gray-800 my-3">{{ __('Power to Weight') }}:
                            <span class="normal-case font-normal">{{ $power_to_weight }}</span>
                        </p>

                        <p class="font-bold text-sm uppercase text-gray-800 my-3">{{ __('Max Weight') }}:
                            <span class="normal-case font-normal">{{ $max_weight }} kg</span>
                        </p>

                        <p class="font-bold text-sm uppercase text-gray-800 my-3">{{ __('Max Passengers') }}
                            :
                            <span class="normal-case font-normal">{{ $max_passengers }}</span>
                        </p>

                        <p class="font-bold text-sm uppercase text-gray-800 my-3">{{ __('Minimum Age') }}:
                            <span class="normal-case font-normal">{{ $min_age }} años</span>
                        </p>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button wire:click="resetInput"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        {{ __('Close') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
