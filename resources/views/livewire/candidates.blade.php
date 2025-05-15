<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <h1 class="text-2xl font-bold text-center my-10">
                    {{ __('Vacancy Candidates') }} : {{ $vacancy->title }}
                </h1>
                @if ($vacancy->users->isNotEmpty())
                    <div class="text-center mt-6">
                        <x-primary-button wire:click="saveStatuses">
                            {{ __('Confirm changes') }}
                        </x-primary-button>
                    </div>
                @endif

                <div class="md:flex md:justify-center p-5">
                    <ul class="divide-y divide-gray-200 w-full">
                        @forelse($vacancy->users as $candidate)
                            <li class="p-3 flex flex-col md:flex-row md:items-center justify-between gap-4">
                                <div class="flex-1">
                                    <p class="text-xl font-medium text-gray-800">{{ $candidate->name }}</p>
                                    <p class="text-sm text-gray-600">{{ $candidate->email }}</p>
                                    <p class="text-sm font-medium text-gray-600">
                                        {{ __('Day he applied') }}:
                                        <span
                                            class="font-normal">{{ $candidate->pivot->created_at->diffForHumans() }}</span>
                                    </p>
                                </div>

                                <div class="mb-4 md:mb-0">
                                    @php
                                        $status = $statuses[$candidate->id] ?? $candidate->pivot->status;
                                        $color = match($status) {
                                            'pending' => 'bg-yellow-200 text-yellow-800',
                                            'accepted' => 'bg-green-200 text-green-800',
                                            'rejected' => 'bg-red-200 text-red-800',
                                            default => 'bg-gray-200 text-gray-800'
                                        };
                                    @endphp

                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold {{ $color }}">
                                        {{ ucfirst($status) }}
                                    </span>
                                </div>

                                <div class="flex items-center gap-2">
                                    @if(($originalStatuses[$candidate->id] ?? '') === 'pending')
                                        <button wire:click="setStatus({{ $candidate->id }}, 'accepted')"
                                                class="px-3 py-1 text-sm bg-green-600 text-white rounded hover:bg-green-700">
                                            {{ __('Accept') }}
                                        </button>

                                        <button wire:click="setStatus({{ $candidate->id }}, 'rejected')"
                                                class="px-3 py-1 text-sm bg-red-600 text-white rounded hover:bg-red-700">
                                            {{ __('Reject') }}
                                        </button>

                                        <button wire:click="setStatus({{ $candidate->id }}, 'pending')"
                                                class="px-3 py-1 text-sm bg-yellow-400 text-black rounded hover:bg-yellow-500">
                                            {{ __('Pending') }}
                                        </button>
                                    @endif

                                    <a href="{{ asset('storage/cv/' . $candidate->pivot->cv) }}"
                                       target="_blank"
                                       class="px-3 py-1 text-sm bg-gray-200 text-gray-800 rounded hover:bg-gray-300">
                                        {{ __('See') }} CV
                                    </a>
                                </div>
                            </li>
                        @empty
                            <p class="p-3 text-center text-sm text-gray-600">{{ __('There are no candidates yet') }}</p>
                        @endforelse
                    </ul>
                </div>

                @if (session()->has('message'))
                    <div class="text-green-600 text-center mt-4">
                        {{ session('message') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
