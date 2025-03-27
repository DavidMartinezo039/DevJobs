<div class="container mx-auto p-4 mt-10">
    @if ($view !== 'index')
        <button wire:click="index"
                class="bg-gray-600 hover:bg-gray-700 text-white font-semibold px-6 py-2 rounded-lg shadow-md transition-all duration-300 relative group w-24 hover:w-32 flex items-center justify-end overflow-hidden">
            <span class="transition-all duration-300">Volver</span>
            <svg xmlns="http://www.w3.org/2000/svg"
                 class="w-5 h-5 absolute left-3 opacity-0 group-hover:opacity-100 transition-opacity duration-300"
                 fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5m7 7l-7-7 7-7"></path>
            </svg>
        </button>

    @endif

    @if ($view === 'index')
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

    @elseif ($view === 'create')
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h1 class="text-2xl font-bold text-center my-10">{{ __('Create CV') }}</h1>
                        <div class="md:flex md:justify-center p-5">
                            <div class="md:w-1/2 space-y-5">
                                <x-forms.input id="title" name="title" label="Title" wireModel="title"/>
                                <div>
                                    <x-input-label for="description" :value="__('Description')"/>
                                    <textarea wire:model="description" placeholder="Description"
                                              class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm w-full h-72"></textarea>
                                    <x-input-error :messages="$errors->get('description')" class="mt-2"/>
                                </div>
                                <x-primary-button wire:click="store">{{ __('Create CV') }}</x-primary-button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @elseif ($view === 'edit')
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h1 class="text-2xl font-bold text-center my-10">{{ __('Update CV') }} :
                            {{$selectedCv->title}}</h1>
                        <div class="md:flex md:justify-center p-5">
                            <div class="md:w-1/2 space-y-5">
                                <x-forms.input id="title" name="title" label="Title" wireModel="title"/>
                                <div>
                                    <x-input-label for="description" :value="__('Description')"/>
                                    <textarea wire:model="description" placeholder="Description"
                                              class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm w-full h-72"></textarea>
                                    <x-input-error :messages="$errors->get('description')" class="mt-2"/>
                                </div>
                                <x-primary-button wire:click="update">{{ __('Update CV') }}</x-primary-button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @elseif ($view === 'show')
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-3">
                    <div class="p-10">
                        <div class="mb-5">
                            <h3 class="font-bold text-3xl text-gray-800 my-3">
                                {{ $selectedCv->title }}
                            </h3>

                            <div class="md:grid md:grid-cols-2 bg-gray-50 p-4 my-10">
                                <p class="font-bold text-sm uppercase text-gray-800 my-3">{{ __('Description') }}:
                                    <span class="normal-case font-normal">{{ $selectedCv->description }}</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
