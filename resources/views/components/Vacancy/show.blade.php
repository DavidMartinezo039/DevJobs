<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-3">
            <div class="p-10">
                <div class="mb-5">
                    <h3 class="font-bold text-3xl text-gray-800 my-3">
                        {{ $vacancy->title }}
                    </h3>

                    <div class="md:grid md:grid-cols-2 bg-gray-50 p-4 my-10">
                        <p class="font-bold text-sm uppercase text-gray-800 my-3">{{ __('Company') }}:
                            <span class="normal-case font-normal">{{ $vacancy->company }}</span>
                        </p>

                        <p class="font-bold text-sm uppercase text-gray-800 my-3">{{ __('last day to apply') }}:
                            <span
                                class="normal-case font-normal">{{ $vacancy->last_day->toFormattedDateString() }}</span>
                        </p>

                        <p class="font-bold text-sm uppercase text-gray-800 my-3">{{ __('Category') }}:
                            <span class="normal-case font-normal">{{ $vacancy->category->category }}</span>
                        </p>

                        <p class="font-bold text-sm uppercase text-gray-800 my-3">{{ __('Salary') }}:
                            <span class="normal-case font-normal">{{ $vacancy->salary->salary }}</span>
                        </p>

                    </div>
                </div>

                <div class="md:grid md:grid-cols-6 gap-4">
                    <div class="md:col-span-2">
                        <img src="{{ asset('storage/vacancies/' . $vacancy->image) }}"
                             alt="{{'Vacancy Image ' . $vacancy->title}}">
                    </div>

                    <div class="md:col-span-4">
                        <h2 class="text-2xl font-bold mb-5">
                            {{ __('Job Description') }}
                        </h2>
                        <p> {{ $vacancy->description }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
