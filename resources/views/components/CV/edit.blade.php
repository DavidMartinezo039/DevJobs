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
