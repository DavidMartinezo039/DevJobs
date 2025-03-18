<div class="bg-gray-100 mt-10 p-5 dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
    <h3 class="text-center text-2xl font-bold my-4">{{ __('Apply for this vacancy') }}</h3>

    @if(session()->has('message'))
        <div
            class="uppercase border border-green-600 bg-green-100 text-green-600 font-bold p-2 my-5 text-sm rounded-lg">
            {{ session('message') }}
        </div>
    @endif

    @if($userCv)
        <div class="p-6 text-gray-900 dark:text-gray-100 flex justify-between items-center">
            <div class="leading-10">
                <p class="text-xl font-medium">{{ __('You have already applied to this vacancy') }}</p>
            </div>

            <div class="flex flex-col md:flex-row items-center gap-3 mt-5 md:mt-0">
                <a href="{{ asset('storage/cv/' . $userCv) }}" target="_blank" rel="noreferrer noopener"
                   class="bg-white border border-gray-300 py-2 px-4 rounded-lg text-gray-700 text-sm font-bold uppercase text-center hover:bg-gray-100">
                    {{ __('See') }} CV
                </a>

                <button wire:click="removeCv"
                        class="bg-red-500 py-2 px-4 rounded-lg text-white text-sm font-bold uppercase text-center hover:bg-red-600">
                    {{ __('Remove') }} CV
                </button>
            </div>
        </div>
    @else
        <div class="flex flex-col justify-center items-center">
            <form wire:submit.prevent="applyVacancy" class="w-96 mt-5">
                <div class="mb-4">
                    <x-input-label for="cv" :value="__('Curriculum (PDF)')"></x-input-label>
                    <x-text-input id="cv" type="file" accept=".pdf" wire:model="cv"
                                  class="block mt-1 w-full"></x-text-input>
                    <x-input-error :messages="$errors->get('cv')" class="mt-2"/>
                </div>

                <x-primary-button class="my-5">
                    {{ __('Apply') }}
                </x-primary-button>
            </form>
        </div>
    @endif
</div>
