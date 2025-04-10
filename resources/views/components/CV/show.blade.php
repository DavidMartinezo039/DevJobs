<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-3">
            <div class="p-10">
                <div class="mb-5">
                    <h3 class="font-bold text-3xl text-gray-800 my-3">
                        {{ $selectedCv->title }}
                    </h3>

                    <div class="md:grid md:grid-cols-2 bg-gray-50 p-4 my-10">
                        <p class="font-bold text-sm uppercase text-gray-800 my-3">{{ __('Description') }}:
                            <span class="normal-case font-normal">{{ $selectedCv->description }}</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
