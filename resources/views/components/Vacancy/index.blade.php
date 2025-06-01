<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

        @if(session()->has('message'))
            <div class="uppercase border border-green-600 bg-green-100 text-green-600 font-bold p-2 my-3 text-sm">
                {{ session('message') }}
            </div>
        @endif
        <div class="flex justify-center">
            <button wire:click="create"
                    class="bg-gray-300 hover:bg-gray-400 p-3 rounded-md mb-10 flex flex-col items-center">
                <img src="{{ asset('archivo.png') }}" alt="{{ __('Create Vacancy') }}" class="w-10 h-10">
                <p class="text-sm font-semibold text-gray-700 mt-2">{{ __('Create Vacancy') }}</p>
            </button>
        </div>

        <div>
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                @forelse($vacancies as $vacancy)
                    <div wire:key="vacancy-{{ $vacancy->id }}" class="p-6 text-gray-900 dark:text-gray-100 md:flex md:justify-between md:items-center">
                        <div class="leading-10">
                            <a wire:click="show({{ $vacancy }})" class="text-xl font-bold cursor-pointer">
                                {{ $vacancy->title }}
                            </a>
                            <p class="text-sm text-gray-600 font-bold">{{ $vacancy->company }}</p>
                            <p class="text-sm text-gray-500">{{ __('Last day') }}: {{ $vacancy->last_day->format('d/m/Y') }}</p>
                        </div>

                        <div class="flex flex-col md:flex-row items-stretch gap-3 mt-5 md:mt-0">
                            <a href="{{ route('candidates.index', $vacancy) }}"
                               class="bg-slate-800 py-2 px-4 rounded-lg text-white text-xs font-bold uppercase text-center">
                                {{ $vacancy->users->count() }} {{ __('Candidates') }}
                            </a>

                            <button wire:click="edit({{ $vacancy }})"
                                    class="bg-blue-800 py-2 px-4 rounded-lg text-white text-xs font-bold uppercase text-center">
                                {{ __('Edit') }}
                            </button>

                            <button wire:click="confirmDelete({{ $vacancy }})"
                                    class="bg-red-600 py-2 px-4 rounded-lg text-white text-xs font-bold uppercase text-center">
                                {{ __('Delete') }}
                            </button>
                        </div>
                    </div>
                @empty
                    <p class="p-3 text-center text-sm text-gray-600">{{ __('There are no vacancies to display') }}</p>
                @endforelse
            </div>
            <div class="mt-10">
                {{ $vacancies->links() }}
            </div>
        </div>
    </div>
</div>

@section('additional-js')

    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        Livewire.on('DeleteAlert', vacancy => {
            Swal.fire({
                title: @json(__('Are you sure you want to delete the vacancy?')),
                text: @json(__('A deleted vacancy cannot be recovered')),
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: @json(__('Yes, delete it!')),
                cancelButtonText: @json(__('Cancel'))
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.dispatch('delete', vacancy)

                    Swal.fire(
                        @json(__('The vacancy was eliminated')),
                        @json(__('Successfully Removed')),
                        'success'
                    );
                }
            })
        })
    </script>

@endsection
