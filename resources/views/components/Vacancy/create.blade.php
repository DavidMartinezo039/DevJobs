<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <h1 class="text-2xl font-bold text-center my-10">{{ __('Post Vacancy') }}</h1>
                <div class="md:flex md:justify-center p-5">
                    <form class="md:w-1/2 space-y-5" wire:submit.prevent="store">

                        <x-forms.input id="title" name="title" label="Title Vacancy" wireModel="title"/>

                        <x-forms.select-input
                            id="salary"
                            name="salary"
                            label="Monthly Salary"
                            :options="$salaries"
                            selectedValue="salary"
                        />

                        <x-forms.select-input
                            id="category"
                            name="category"
                            label="Category"
                            :options="$categories"
                            selectedValue="category"
                        />

                        <x-forms.input id="company" name="company" label="Company" wireModel="company"/>

                        <x-forms.date-picker id="last_day" name="last_day" label="{{ __('Last Day to Apply') }}"
                                             wireModel="last_day"/>

                        <div>
                            <x-input-label for="description" :value="__('Job Description')"/>
                            <textarea id="description" wire:model="description" placeholder="Job Overview"
                                      class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm w-full h-72"></textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2"/>
                        </div>

                        <div>
                            <x-input-label for="image" :value="__('Image (Max:2048kb)')"/>
                            <x-text-input id="image" class="block mt-1 w-full" type="file" wire:model="image"
                                          accept="image/*"/>
                            <div class="my-5 w-80">
                                @if($image)
                                    {{  __('Image') }}:
                                    <img src="{{ $image->temporaryUrl() }}" alt="Image">
                                @endif
                            </div>
                            <x-input-error :messages="$errors->get('image')" class="mt-2"/>
                        </div>

                        <x-primary-button>
                            {{ __('Create Vacancy') }}
                        </x-primary-button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
