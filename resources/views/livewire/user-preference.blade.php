<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <h1 class="text-2xl font-bold text-center my-10">{{ __('User Preferences') }}</h1>
                @if(session()->has('success'))
                    <div
                        x-data="{ show: true }"
                        x-init="setTimeout(() => show = false, 5000)"
                        x-show="show"
                        x-transition
                        class="max-w-md mx-auto bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-6 text-center"
                    >
                        {{ session('success') }}
                    </div>
                @endif
                <div class="md:flex md:justify-center p-5">

                    <form wire:submit.prevent="save" class="md:w-1/2 space-y-5">

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

                        <div>
                            <x-input-label for="keyword" :value="__('Words in the Description or Title')"/>
                            <textarea id="keyword" wire:model="keyword"
                                      class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm w-full h-72"></textarea>
                            <x-input-error :messages="$errors->get('keyword')" class="mt-2"/>
                        </div>

                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">{{ __('Save Preferences') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
