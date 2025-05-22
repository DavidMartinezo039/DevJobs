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

                        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

                        <div
                            x-data="{ isDragging: false }"
                            @dragover.prevent="isDragging = true"
                            @dragleave.prevent="isDragging = false"
                            @drop.prevent="
        isDragging = false;
        $wire.upload('image', $event.dataTransfer.files[0]);
    "
                            class="border-2 border-dashed rounded-md p-6 text-center cursor-pointer transition-all duration-200"
                            :class="isDragging ? 'bg-blue-100 border-blue-400' : 'border-gray-300'"
                            @click="$refs.fileInput.click()"
                        >
                            <x-input-label for="image" :value="__('Image (Max:2048kb)')" />

                            <input
                                x-ref="fileInput"
                                id="image"
                                type="file"
                                class="hidden"
                                wire:model="image"
                                accept="image/*"
                            />

                            <p class="text-gray-500">
                                {{ __('Drag and drop an image or click to select') }}
                            </p>

                            @error('image')
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                            @enderror

                            @if($image)
                                <div class="mt-4">
                                    <p class="font-semibold">{{ __('Image') }}:</p>
                                    <img src="{{ $image->temporaryUrl() }}" class="mt-2 w-80 mx-auto rounded shadow" alt="{{ __('Image') }}">
                                </div>
                            @endif
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
