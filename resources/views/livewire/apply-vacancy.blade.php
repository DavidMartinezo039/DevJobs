<div class="bg-gray-100 p-5 mt-10 flex flex-col justify-center items-center">
    <h3 class="text-center text-2xl font-bold my-4">{{ __('Apply for this vacancy') }}</h3>

    @if(session()->has('message'))
        <div class="uppercase border border-green-600 bg-green-100 text-green-600 font-bold p-2 my-5 text-sm rounded-lg">
            {{ session('message') }}
        </div>
    @endif

    <form wire:submit.prevent="applyVacancy" class="w-96 mt-5">
        <div class="mb-4">
            <x-input-label for="cv" :value="__('Curriculum (PDF)')"></x-input-label>
            <x-text-input id="cv" type="file" accept=".pdf" wire:model="cv" class="block mt-1 w-full"></x-text-input>
            <x-input-error :messages="$errors->get('cv')" class="mt-2" />
        </div>

        <x-primary-button class="my-5">
            {{ __('Apply') }}
        </x-primary-button>
    </form>
</div>
