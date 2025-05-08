<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <h1 class="text-2xl font-bold text-center my-10 uppercase">{{ __('the vacancies I am applying for') }}</h1>
                <div class="md:flex md:justify-center p-5">
                    <ul class="divide-y divide-gray-200 w-full">
                        @forelse($user->vacancies as $vacancy)
                            <li class="p-3 flex items-center">
                                <div class="flex-1">
                                    <a href="{{ route('vacancies.show', $vacancy) }}" class="text-xl font-medium text-gray-800">{{ $vacancy->title }}</a>
                                </div>

                                <div>
                                    <a
                                        class="inline-flex items-center shadow-sm px-2.5 py-0.5 border border-gray-300 text-sm leading-5 font-medium rounded-full
                                            text-gray-700 bg-white hover:bg-gray-50"
                                        href="{{ asset('storage/cv/' . $vacancy->pivot->cv) }}"
                                        target="_blank"
                                        rel="noreferrer noopen">
                                        {{ __('See') }} CV
                                    </a>
                                </div>
                            </li>
                        @empty
                            <p class="p-3 text-center text-sm text-gray-600">{{ __('You have not applied for any vacancies yet.') }}</p>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
