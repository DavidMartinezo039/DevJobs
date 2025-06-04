<div class="container mx-auto mt-10 p-5">
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-5">

        @if(session()->has('message'))
            <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)"
                 x-show="show" x-transition
                 class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-4">
                {{ session('message') }}
            </div>
        @endif

        @if($isEditMode || $createMode)
            <x-driving-license.form :isEditMode="$isEditMode"/>
        @elseif($isShowMode)
                <x-driving-license.show
                    :category="$category"
                    :vehicle_type="$vehicle_type"
                    :max_speed="$max_speed"
                    :max_power="$max_power"
                    :power_to_weight="$power_to_weight"
                    :max_weight="$max_weight"
                    :max_passengers="$max_passengers"
                    :min_age="$min_age"
                />
            @else
            <div class="flex justify-center mb-5">
                <button wire:click="$set('createMode', true)"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-lg font-semibold shadow-md transition duration-200">
                    {{ __('Create New Driving License') }}
                </button>
                <a href="{{ route('god.driving-license-requests') }}"
                   class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 ml-5 rounded-lg font-semibold shadow-md transition duration-200">
                    {{ __('See requests for edit or delete a driving license') }}
                </a>
            </div>
        @endif

        <div class="md:flex md:justify-center p-5">
            <ul class="divide-y divide-gray-200 w-full">
                @forelse($drivingLicenses as $license)
                    <li wire:key="license-{{ $license->id }}"
                        class="p-3 flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div wire:click="show({{ $license }})" class="cursor-pointer">
                            <p class="text-xl font-medium text-gray-800">{{ $license->category }}</p>
                            <p class="text-sm text-gray-500">{{ $license->vehicle_type }}</p>
                        </div>

                        <div class="flex items-center gap-2">
                            <button wire:click="edit({{ $license }})"
                                    class="bg-blue-800 py-2 px-4 rounded-lg text-white text-xs font-bold uppercase">
                                {{ __('Edit') }}
                            </button>

                            <button wire:click="confirmDelete({{ $license->id }})"
                                    class="bg-red-600 py-2 px-4 rounded-lg text-white text-xs font-bold uppercase">
                                {{ __('Delete') }}
                            </button>
                        </div>
                    </li>
                @empty
                    <li class="text-sm text-center text-gray-500 p-4">{{ __('No driving licenses yet.') }}</li>
                @endforelse
            </ul>
        </div>
    </div>

    <div class="mt-4">
        {{ $drivingLicenses->links() }}
    </div>

    <x-back-to-dashboard-button/>
</div>

@section('additional-js')
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        Livewire.on('DeleteAlert', id => {
            Swal.fire({
                title: @json(__('Are you sure you want to delete the driving license?')),
                text: @json(__('A deleted driving license cannot be recovered')),
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: @json(__('Yes, delete it!')),
                cancelButtonText: @json(__('Cancel'))
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.dispatch('delete', {id})

                    Swal.fire(
                        @json(__('Deleted!')),
                        @json(__('Driving license deleted successfully.')),
                        'success'
                    );
                }
            });
        });
    </script>
@endsection
