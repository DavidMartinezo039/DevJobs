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
                <span class="normal-case font-normal">{{ $vacancy->last_day->toFormattedDateString() }}</span>
            </p>

            <p class="font-bold text-sm uppercase text-gray-800 my-3">{{ __('Category') }}:
                <span class="normal-case font-normal">{{ $vacancy->category->category }}</span>
            </p>

            <p class="font-bold text-sm uppercase text-gray-800 my-3">{{ __('Monthly Salary') }}:
                <span class="normal-case font-normal">{{ $vacancy->salary->salary }}</span>
            </p>

        </div>
    </div>

    <div class="md:grid md:grid-cols-6 gap-4">
        <div class="md:col-span-2">
            <img src="{{ asset('storage/vacancies/' . $vacancy->image) }}" alt="{{'Vacancy Image ' . $vacancy->title}}">
        </div>

        <div class="md:col-span-4">
            <h2 class="text-2xl font-bold mb-5">
                {{ __('Job Description') }}
            </h2>
            <p> {{ $vacancy->description }}</p>
        </div>
    </div>

    @guest
        <div class="mt-5 bg-gray-50 border border-dashed p-5 text-center">
            <p>
                {{ __('Do you want to apply for this vacancy?') }} <a class="font-bold text-indigo-600"
                                                                      href="{{ route('register') }}">{{ __('Get an account and apply for this and other vacancies') }}</a>
            </p>
        </div>
    @endguest

    @auth
        @cannot('create', \App\Models\Vacancy::class)
            <livewire:apply-vacancy :vacancy="$vacancy"/>
        @endcannot
    @endauth

    <div class="text-center mt-6">
        <a href="{{ route('vacancy.download', $vacancy) }}"
           class="bg-green-600 py-2 px-4 rounded-lg text-white text-xs font-bold uppercase inline-block">
            {{ __('Download Vacancy') }}
        </a>
    </div>

</div>
