<div>
    @if($values  && count($values) > 0)
        <p>
            <span class="text-sm text-gray-500 font-bold uppercase mb-2">{{ __($label) }}:</span></p>
        @foreach($values as $value)
            <div class="flex space-x-2 mt-2 border-b border-gray-300 dark:border-gray-600 pb-2 mb-5">
                @if(!empty($items) || !empty($dataPivot))
                    {{-- Es un modelo con tabla pivote --}}
                    <div class="flex flex-wrap gap-3">
                        @foreach($items ?? [] as $item)
                            <div
                                class="w-full sm:w-auto bg-gray-100 dark:bg-gray-700 px-4 py-2 rounded-md shadow-sm border border-gray-300 dark:border-gray-600">
                                <x-profile-field :label="$item" :value="$value[$item]"/>
                            </div>
                        @endforeach
                    </div>

                    @foreach($dataPivot ?? [] as $item)
                        <div
                            class="w-full sm:w-auto bg-gray-100 dark:bg-gray-700 px-4 py-2 rounded-md shadow-sm border border-gray-300 dark:border-gray-600">
                            <x-profile-field :label="$item" :value="$value['pivot'][$item]"/>
                        </div>
                    @endforeach
                @else
                    {{-- Es un valor simple como string --}}
                    <x-profile-field :value="$value"/>
                @endif
            </div>
        @endforeach
    @endif
</div>
