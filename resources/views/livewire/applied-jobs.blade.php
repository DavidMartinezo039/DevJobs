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

                                <div class="mb-4 md:mb-0">
                                    @php
                                        $status = $statuses[$vacancy->id] ?? $vacancy->pivot->status;
                                        $color = match($status) {
                                            'pending' => 'bg-yellow-200 text-yellow-800',
                                            'accepted' => 'bg-green-200 text-green-800',
                                            'rejected' => 'bg-red-200 text-red-800',
                                            default => 'bg-gray-200 text-gray-800'
                                        };
                                    @endphp

                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold mr-1 {{ $color }}">
                                        {{ ucfirst($status) }}
                                    </span>
                                </div>

                                <div>
                                    <a
                                        class="px-3 py-1 text-sm bg-gray-200 text-gray-800 rounded hover:bg-gray-300"
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
