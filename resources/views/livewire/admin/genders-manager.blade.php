<div class="container mx-auto mt-10 p-5">
    <div class="flex justify-center h-12">
            <button
                wire:click="saveChanges"
                class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition-all duration-300"
                style="{{ count($pendingChanges) > 0 ? 'opacity: 1; visibility: visible;' : 'opacity: 0; visibility: hidden;' }}"
            >
                {{ __('Save Changes') }}
            </button>
    </div>

    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-5 mt-10">

        @if(session()->has('message'))
            <div
                x-data="{ show: true }"
                x-init="setTimeout(() => show = false, 5000)"
                x-show="show"
                x-transition
                class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-4"
            >
                {{ session('message') }}
            </div>
        @endif

        @if($isEditMode || $createMode)
            <h2 class="text-2xl font-bold mb-4">
                {{ $isEditMode ? __('Edit Gender') : __('Create Gender') }}
            </h2>

            <form wire:submit.prevent="{{ $isEditMode ? 'update' : 'store' }}">
                <input
                    type="text"
                    wire:model.defer="type"
                    placeholder="Gender type"
                    class="border rounded px-3 py-2 w-full @error('type') border-red-500 @enderror"
                >

                @error('type')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror

                <div class="mt-3 flex gap-2">
                    <button
                        type="submit"
                        class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700"
                    >
                        {{ $isEditMode ? __('Update') : __('Create') }}
                    </button>

                    <button
                        type="button"
                        wire:click="resetInput"
                        class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600"
                    >
                        {{ __('Cancel') }}
                    </button>
                </div>
            </form>
        @else
            <div class="flex justify-center">
                <button
                    wire:click="$set('createMode', true)"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-lg font-semibold shadow-md transition duration-200"
                >
                    {{ __('Create New Gender') }}
                </button>
            </div>
        @endif

        <div class="md:flex md:justify-center p-5">
            <ul class="divide-y divide-gray-200 w-full">
                @forelse($genders as $gender)
                    @php
                        $isPending = isset($pendingChanges[$gender->id]);
                        $willBeDefault = $isPending ? !$gender->is_default : $gender->is_default;
                    @endphp
                    <li wire:key="gender-{{ $gender->id }}" class="p-3 flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <p class="text-xl font-medium text-gray-800">{{ $gender->type }}</p>

                        <div class="flex items-center gap-2">
                            @if($gender->is_default)
                                <span class="text-xs bg-gray-300 text-gray-800 px-2 py-1 rounded">{{ __('Default') }}</span>
                            @endif
                            @hasrole('god')
                            <button
                                wire:click="markForToggle({{ $gender->id }})"
                                class="flex items-center gap-2 px-4 py-2 text-sm font-semibold rounded-lg shadow transition-all duration-300
                    {{ $willBeDefault
                        ? 'bg-green-600 hover:bg-green-700 text-white'
                        : 'bg-yellow-500 hover:bg-yellow-600 text-white' }}"
                            >
                                @if($willBeDefault)
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                                         viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    {{ __('Unmark Default') }}
                                @else
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                                         viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    {{ __('Mark as Default') }}
                                @endif
                            </button>
                            @endhasrole

                            @if(!$gender->is_default || !auth()->user()->hasRole('moderator'))
                                <button
                                    wire:click="edit({{ $gender }})"
                                    class="bg-blue-800 py-2 px-4 rounded-lg text-white text-xs font-bold uppercase"
                                >
                                    {{ __('Edit') }}
                                </button>

                                <button
                                    wire:click="confirmDelete({{ $gender }})"
                                    class="bg-red-600 py-2 px-4 rounded-lg text-white text-xs font-bold uppercase"
                                >
                                    {{ __('Delete') }}
                                </button>
                            @endif
                        </div>
                    </li>
                @empty
                    <p class="p-3 text-center text-sm text-gray-600">
                        {{ __('There are no genders yet.') }}
                    </p>
                @endforelse
            </ul>
        </div>
    </div>
    <x-back-to-dashboard-button/>
</div>

@section('additional-js')

    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        Livewire.on('DeleteAlert', gender => {
            Swal.fire({
                title: @json(__('Are you sure you want to delete the gender?')),
                text: @json(__('A deleted gender cannot be recovered')),
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: @json(__('Yes, delete it!')),
                cancelButtonText: @json(__('Cancel'))
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.dispatch('deleteGender', gender)

                    Swal.fire(
                        @json(__('The gender was eliminated')),
                        @json(__('Successfully Removed')),
                        'success'
                    );
                }
            })
        })
    </script>

@endsection
