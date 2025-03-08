<form class="md:w-1/2 space-y-5">
    <div>
        <x-input-label for="title" :value="__('Title Vacancy')" />
        <x-text-input id="title" class="block mt-1 w-full" type="text" wire:model="title" :value="old('title')" placeholder="{{  __('Title Vacancy') }}"/>
        <x-input-error :messages="$errors->get('title')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="salary" :value="__('Monthly Salary')" />
        <select
            id="salary"
            name="salary"
            class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm w-full"
        >
            <option>-- {{ __('Select') }} --</option>
            @foreach($salaries as $salary)
                <option value="{{ $salary->id }}">{{$salary->salary}}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('salary')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="category" :value="__('Category')" />
        <select
            id="category"
            name="category"
            class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm w-full"
        >
            <option>-- {{ __('Select') }} --</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}">{{$category->category}}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('category')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="company" :value="__('Company')" />
        <x-text-input id="company" class="block mt-1 w-full" type="text" name="company" :value="old('company')" placeholder="{{  __('Company: example.') }} Netflix, Uber, Shopify"/>
        <x-input-error :messages="$errors->get('company')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="last_day" :value="__('last Day to Apply')" />
        <x-text-input id="last_day" class="block mt-1 w-full" type="date" name="last_day" :value="old('last_day')"/>
        <x-input-error :messages="$errors->get('last_day')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="description" :value="__('Job Description')" />
        <textarea id="description" name="description" placeholder="Job Overview" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm w-full h-72"></textarea>
        <x-input-error :messages="$errors->get('description')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="image" :value="__('Image')" />
        <x-text-input id="image" class="block mt-1 w-full" type="file" name="image"/>
        <x-input-error :messages="$errors->get('image')" class="mt-2" />
    </div>

    <x-primary-button>
        {{ __('Create Vacancy') }}
    </x-primary-button>
</form>
