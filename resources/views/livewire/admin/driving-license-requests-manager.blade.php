<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="flex justify-center">
            <h1 class="text-xl font-bold mb-4">{{ __('Pending Driving License Requests') }}</h1>
        </div>

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            @foreach ($requests as $request)
                <div wire:key="vacancy-{{ $request->id }}"
                     class="p-6 text-gray-900 dark:text-gray-100 md:flex md:justify-between md:items-center">
                    <div class="leading-10">
                        <p>
                            <strong>{{ $request->user->name }}</strong>
                            {{ __('requested to driving license') }}
                            <strong>{{ $request->drivingLicense->category }}</strong>.
                        </p>
                    </div>
                    <div class="flex flex-col md:flex-row items-stretch gap-3 mt-5 md:mt-0">

                        <button wire:click="approve({{ $request }})"
                                class="bg-green-500 text-white px-3 py-1 rounded">
                            {{ __('Approve') }}
                        </button>
                        <button wire:click="reject({{ $request }})"
                                class="bg-red-500 text-white px-3 py-1 rounded">
                            {{ __('Reject') }}
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <a href="{{ route('driving-licenses.manager') }}"
            class="fixed bottom-4 left-4 z-50 bg-gray-600 hover:bg-gray-700 text-white font-semibold px-6 py-2 rounded-lg shadow-md transition-all duration-300 group w-24 hover:w-32 flex items-center justify-end overflow-hidden">
        <span class="transition-all duration-300">{{ __('Back') }}</span>
        <svg xmlns="http://www.w3.org/2000/svg"
             class="w-5 h-5 absolute left-3 opacity-0 group-hover:opacity-100 transition-opacity duration-300"
             fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5m7 7l-7-7 7-7"></path>
        </svg>
    </a>
</div>
