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
                {{ $isEditMode ? 'Edit Gender' : 'Create Gender' }}
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
                    <li class="p-3 flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <p class="text-xl font-medium text-gray-800">{{ $gender->type }}</p>

                        <div class="flex items-center gap-2">
                            <button
                                wire:click="edit({{ $gender->id }})"
                                class="bg-blue-800 py-2 px-4 rounded-lg text-white text-xs font-bold uppercase"
                            >
                                {{ __('Edit') }}
                            </button>

                            <button
                                wire:click="delete({{ $gender->id }})"
                                class="bg-red-600 py-2 px-4 rounded-lg text-white text-xs font-bold uppercase"
                            >
                                {{ __('Delete') }}
                            </button>
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
