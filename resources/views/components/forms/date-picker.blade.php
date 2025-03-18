<div>
    <x-input-label for="{{ $id }}" :value="$label" />
    <x-text-input id="{{ $id }}" name="{{ $name }}" class="block mt-1 w-full" type="date" wire:model="{{ $wireModel }}" :value="old($id)" />
    <x-input-error :messages="$errors->get($name)" class="mt-2" />
</div>
