<x-app-layout>
    <div class="max-w-6xl mx-auto p-6 lg:p-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">
                    {{ __('Admin Dashboard') }}
                </h1>
                <p class="text-lg text-gray-600">
                    {{ __('Welcome') }}, {{ $user->name }} ({{ $user->getRoleNames()->join(', ') }})
                </p>
            </div>

            <form method="POST" action="{{ route('admin.backup') }}" class="mt-4 md:mt-0">
                @csrf
                <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white font-semibold rounded-lg shadow hover:bg-indigo-700 transition">
                    {{ __('Generate Backup') }}
                </button>
            </form>
        </div>

        @if (session('success'))
            <div class="mb-4 p-4 bg-green-100 border border-green-300 text-green-800 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <a href="{{ route('genders.manager') }}"
               class="block p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-50 transition">
                <h2 class="text-xl font-semibold text-indigo-600">{{ __('Manage Genders') }}</h2>
                <p class="text-gray-500 mt-2">{{ __('Create, edit, and delete genders.') }}</p>
            </a>

            <a href="{{ route('digital-skills.manager') }}"
               class="block p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-50 transition">
                <h2 class="text-xl font-semibold text-indigo-600">{{ __('Manage Digital Skills') }}</h2>
                <p class="text-gray-500 mt-2">{{ __('Create, edit, and delete digital skills.') }}</p>
            </a>

            <a href="{{ route('driving-licenses.manager') }}"
               class="block p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-50 transition">
                <h2 class="text-xl font-semibold text-indigo-600">{{ __('Manage Driving Licenses') }}</h2>
                <p class="text-gray-500 mt-2">{{ __('Create, edit, and delete driving licenses.') }}</p>
            </a>
        </div>
    </div>
</x-app-layout>
