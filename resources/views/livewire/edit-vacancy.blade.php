<form class="md:w-1/2 space-y-5" wire:submit.prevent="editVacancy">
    <div>
        <x-input-label for="title" :value="__('Title Vacancy')" />
        <x-text-input id="title" class="block mt-1 w-full" type="text" wire:model="title" :value="old('title')" placeholder="{{  __('Title Vacancy') }}"/>
        <x-input-error :messages="$errors->get('title')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="salary" :value="__('Monthly Salary')" />
        <x-forms.select-input
            id="salary"
            name="salary"
            :options="$salaries"
            selectedValue="salary"
        />
        <x-input-error :messages="$errors->get('salary')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="category" :value="__('Category')" />
        <x-forms.select-input
            id="category"
            name="category"
            :options="$categories"
            selectedValue="category"
        />
        <x-input-error :messages="$errors->get('category')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="company" :value="__('Company')" />
        <x-text-input id="company" class="block mt-1 w-full" type="text" wire:model="company" :value="old('company')" placeholder="{{  __('Company: example.') }} Netflix, Uber, Shopify"/>
        <x-input-error :messages="$errors->get('company')" class="mt-2" />
    </div>

    <x-forms.date-picker id="last_day" name="last_day" label="{{ __('Last Day to Apply') }}" wireModel="last_day" />

    <div>
        <x-input-label for="description" :value="__('Job Description')" />
        <textarea id="description" wire:model="description" placeholder="Job Overview" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm w-full h-72"></textarea>
        <x-input-error :messages="$errors->get('description')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="image" :value="__('Image')" />
        <x-text-input id="image" class="block mt-1 w-full" type="file" wire:model="new_image" accept="image/*"/>

        <div class="my-5 w-80">
            <x-input-label :value="__('Current Image')"/>
        <img src="{{ asset('storage/vacancies/' . $image) }}" alt="{{ __('Vacancy Image') . $title }}">
        </div>
        <div class="my-5 w-80">
            @if($new_image) w
                <x-input-label :value="__('New Image')"/>
                <img src="{{ $new_image->temporaryUrl() }}" alt="Image">
            @endif
        </div>
        <x-input-error :messages="$errors->get('new_image')" class="mt-2" />
    </div>

    {{--

    <div>
        <x-input-label for="image" :value="__('Image')" />
        <x-text-input id="image" class="block mt-1 w-full" type="file" wire:model="image" accept="image/*"/>

        <x-input-error :messages="$errors->get('image')" class="mt-2" />
    </div>
    --}}

    <x-primary-button>
        {{ __('Save Changes') }}
    </x-primary-button>
</form>
