<x-app-layout>
    <div class="max-w-6xl mx-auto p-6 lg:p-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">
            {{ __('Admin Dashboard') }}
        </h1>

        <p class="text-lg text-gray-600 mb-4">
            {{ __('Welcome') }}, {{ $user->name }} ({{ $user->getRoleNames()->join(', ') }})
        </p>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <a href="{{ route('genders.manager') }}"
               class="block p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-50 transition">
                <h2 class="text-xl font-semibold text-indigo-600">{{ __('Manage Genders') }}</h2>
                <p class="text-gray-500 mt-2">{{ __('Create, edit, and delete genres.') }}</p>
            </a>
        </div>
    </div>
</x-app-layout>
