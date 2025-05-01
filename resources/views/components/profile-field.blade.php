<div>
    @if($value)
        <p>
            @if($label !== '')
                <span class="text-sm text-gray-500 font-bold uppercase mb-2">{{ __($label) }}:</span>
            @endif
            @if($useFormattedDate)
                {{ \Carbon\Carbon::parse($value)->format('d/m/Y') }}
            @else
                {{ $value }}
            @endif
        </p>
    @endif
</div>
