<div>
    <x-input-label for="{{ $id }}" :value="$label" />
    <x-text-input id="{{ $id }}" class="block mt-1 w-full" type="date" wire:model="{{ $wireModel }}" :value="old($id)" />
    <x-input-error :messages="$errors->get($id)" class="mt-2" />
</div>
