<div class="flex justify-center">
    <button wire:click="create"
            class="bg-gray-300 hover:bg-gray-400 p-3 rounded-md mb-10 flex flex-col items-center">
        <img src="{{ asset('archivo.png') }}" alt="{{ __('Create CV') }}" class="w-10 h-10">
        <p class="text-sm font-semibold text-gray-700 mt-2">{{ __('Create CV') }}</p>
    </button>
</div>

<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
    @forelse ($cvs as $cv)
        <div class="p-6 text-gray-900 dark:text-gray-100 md:flex md:justify-between md:items-center">
            <div class="leading-10">
                <button wire:click="show({{ $cv->id }})" class="text-xl font-bold">
                    {{ $cv->title }}
                </button>
            </div>
            <div class="flex flex-col md:flex-row items-stretch gap-3 mt-5 md:mt-0">
                <button wire:click="show({{ $cv->id }})"
                        class="bg-slate-800 py-2 px-4 rounded-lg text-white text-xs font-bold uppercase text-center">{{ __('See') }}
                </button>
                <button wire:click="edit({{ $cv->id }})"
                        class="bg-blue-800 py-2 px-4 rounded-lg text-white text-xs font-bold uppercase text-center">{{ __('Edit') }}</button>
                <button wire:click="delete({{ $cv->id }})"
                        class="bg-red-600 py-2 px-4 rounded-lg text-white text-xs font-bold uppercase text-center">
                    {{ __('Delete') }}
                </button>
            </div>
        </div>
    @empty
        <p class="p-3 text-center text-sm text-gray-600">{{ __('There are no cvs yet') }}</p>
    @endforelse

</div>
