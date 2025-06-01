<div class="container mx-auto mt-10 p-5">
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-5">

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
                {{ $isEditMode ? __('Edit Digital Skill') : __('Create Digital Skill') }}
            </h2>

            <form wire:submit.prevent="{{ $isEditMode ? 'update' : 'store' }}">
                <input
                    type="text"
                    wire:model.defer="name"
                    placeholder="Digital Skill Name"
                    class="border rounded px-3 py-2 w-full @error('name') border-red-500 @enderror"
                >

                @error('name')
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
                    {{ __('Create New Digital Skill') }}
                </button>
            </div>
        @endif

        <div class="md:flex md:justify-center p-5">
            <ul class="divide-y divide-gray-200 w-full">
                @forelse($digitalSkills as $skill)
                    <li wire:key="skill-{{ $skill->id }}" class="p-3 flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <p class="text-xl font-medium text-gray-800">{{ $skill->name }}</p>

                        <div class="flex items-center gap-2">
                            <button
                                wire:click="edit({{ $skill }})"
                                class="bg-blue-800 py-2 px-4 rounded-lg text-white text-xs font-bold uppercase"
                            >
                                {{ __('Edit') }}
                            </button>

                            <button
                                wire:click="confirmDelete({{ $skill }})"
                                class="bg-red-600 py-2 px-4 rounded-lg text-white text-xs font-bold uppercase"
                            >
                                {{ __('Delete') }}
                            </button>
                        </div>
                    </li>
                @empty
                    <li class="text-sm text-center text-gray-500 p-4">{{ __('No digital skills yet.') }}</li>
                @endforelse
            </ul>
        </div>
    </div>
    <div class="mt-4">
        {{ $digitalSkills->links() }}
    </div>
    <x-back-to-dashboard-button/>
</div>

@section('additional-js')

    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        Livewire.on('DeleteAlert', digitalskill => {
            Swal.fire({
                title: @json(__('Are you sure you want to delete the digital skill?')),
                text: @json(__('A deleted digital skill cannot be recovered')),
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: @json(__('Yes, delete it!')),
                cancelButtonText: @json(__('Cancel'))
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.dispatch('delete', digitalskill)

                    Swal.fire(
                        @json(__('The digital skill was eliminated')),
                        @json(__('Successfully Removed')),
                        'success'
                    );
                }
            })
        })
    </script>

@endsection
