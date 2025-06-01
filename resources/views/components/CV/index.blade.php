<div class="flex justify-center">
    <button wire:click="create"
            class="bg-gray-300 hover:bg-gray-400 p-3 rounded-md mb-10 flex flex-col items-center">
        <img src="{{ asset('archivo.png') }}" alt="{{ __('Create CV') }}" class="w-10 h-10">
        <p class="text-sm font-semibold text-gray-700 mt-2">{{ __('Create CV') }}</p>
    </button>
</div>

<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
    @forelse ($cvs as $cv)
        <div wire:key="cv-{{ $cv->id }}" class="p-6 text-gray-900 dark:text-gray-100 md:flex md:justify-between md:items-center">
            <div class="leading-10">
                <button wire:click="show({{ $cv->id }})" class="text-xl font-bold">
                    {{ $cv->title }}
                </button>
            </div>
            <div class="flex flex-col md:flex-row items-stretch gap-3 mt-5 md:mt-0">
                <a href="{{ route('cv.download', $cv) }}"
                   class="bg-green-600 py-2 px-4 rounded-lg text-white text-xs font-bold uppercase text-center">
                    {{ __('Download CV') }}
                </a>

                <button wire:click="show({{ $cv->id }})"
                        class="bg-slate-800 py-2 px-4 rounded-lg text-white text-xs font-bold uppercase text-center">
                    {{ __('See') }}
                </button>
                <button wire:click="edit({{ $cv->id }})"
                        class="bg-blue-800 py-2 px-4 rounded-lg text-white text-xs font-bold uppercase text-center">
                    {{ __('Edit') }}
                </button>
                <button wire:click="confirmDelete({{ $cv }})"
                        class="bg-red-600 py-2 px-4 rounded-lg text-white text-xs font-bold uppercase text-center">
                    {{ __('Delete') }}
                </button>
            </div>
        </div>
    @empty
        <p class="p-3 text-center text-sm text-gray-600">{{ __('There are no cvs yet') }}</p>
    @endforelse
</div>

@section('additional-js')

    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        Livewire.on('DeleteAlert', cv => {
            Swal.fire({
                title: @json(__('Are you sure you want to delete the CV?')),
                text: @json(__('A deleted CV cannot be recovered')),
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: @json(__('Yes, delete it!')),
                cancelButtonText: @json(__('Cancel'))
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.dispatch('delete', cv)

                    Swal.fire(
                        @json(__('The CV was eliminated')),
                        @json(__('Successfully Removed')),
                        'success'
                    );
                }
            })
        })
    </script>

@endsection
