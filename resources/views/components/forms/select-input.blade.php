<div>
    <x-input-label for="{{ $id }}" :value="__($label)"/>
    <select
        id="{{ $id }}"
        name="{{ $name }}"
        wire:model="{{ $selectedValue }}"
        class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm w-full"
    >
        <option value="">-- {{ __('Select') }} --</option>
        @foreach($options as $option)
            <option value="{{ $option->id }}">{{ $option->$name }}</option>
        @endforeach
    </select>
    <x-input-error :messages="$errors->get($name)" class="mt-2"/>
</div>
