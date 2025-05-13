<div class="max-w-2xl mx-auto mt-10 bg-white shadow-md rounded-lg p-6">
    <h2 class="text-2xl font-bold text-red-600 mb-4">
        {{ __('Are you sure you want to withdraw your application?') }}
    </h2>

    <p class="mb-6 text-gray-700">
        {{ __('You are about to delete your CV and cancel your participation in the vacancy:') }}
        <strong class="text-gray-900">{{ $vacancy->title }}</strong>.
        {{ __('This action cannot be undone.') }}
    </p>

    <form wire:submit.prevent="withdraw">
        <button type="submit"
                class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-semibold rounded">
            {{ __('Yes, I want to cancel my participation') }}
        </button>
    </form>
</div>
