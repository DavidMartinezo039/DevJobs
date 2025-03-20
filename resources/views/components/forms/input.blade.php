<div>
    <x-input-label for="{{ $id }}" :value="__($label)" />
    <x-text-input id="{{ $id }}" name="{{ $name }}" class="block mt-1 w-full" type="text" wire:model="{{ $wireModel }}" :value="old($name)" placeholder="{{  __($label) }}"/>
    <x-input-error :messages="$errors->get($name)" class="mt-2" />
</div>
