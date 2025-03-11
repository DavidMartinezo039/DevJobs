<div>
<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
    @forelse($vacancies as $vacancy)
        <div class="p-6 text-gray-900 dark:text-gray-100 md:flex md:justify-between md:items-center">
            <div class="leading-10">
                <a href="{{ route('vacancies.show', $vacancy) }}" class="text-xl font-bold">
                    {{ $vacancy->title }}
                </a>
                <p class="text-sm text-gray-600 font-bold">{{ $vacancy->company }}</p>
                <p class="text-sm text-gray-500">{{ __('Last day') }}: {{ $vacancy->last_day->format('d/m/Y') }}</p>
            </div>

            <div class="flex flex-col md:flex-row items-stretch gap-3 mt-5 md:mt-0">
                <a href="#" class="bg-slate-800 py-2 px-4 rounded-lg text-white text-xs font-bold uppercase text-center">
                    {{ __('Candidates') }}
                </a>

                <a href="{{ route('vacancies.edit', $vacancy) }}" class="bg-blue-800 py-2 px-4 rounded-lg text-white text-xs font-bold uppercase text-center">
                    {{ __('Edit') }}
                </a>

                <button wire:click="confirmDelete({{ $vacancy }})" class="bg-red-600 py-2 px-4 rounded-lg text-white text-xs font-bold uppercase text-center">
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

@section('additional-js')

    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        Livewire.on('DeleteAlert', vacancy => {
            Swal.fire({
                title: 'Are you sure you want to delete the vacancy?',
                text: "A deleted vacancy cannot be recovered",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.dispatch('deleteVacancy', vacancy)

                    Swal.fire(
                        'The vacancy was eliminated',
                        'Successfully Removed',
                        'success'
                    )
                }
            })
        })
    </script>

@endsection
