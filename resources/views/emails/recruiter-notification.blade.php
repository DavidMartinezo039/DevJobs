@component('mail::message')
    # {{ __('Candidate withdrawn') }}

    {{ __('The candidate') }} **{{ $candidate->name }}** {{ __('has withdrawn their application from the position') }} **{{ $vacancy->title }}**.

    {{ __('They will no longer be part of the selection process.') }}

    {{ __('Check the vacancy if you need to make adjustments.') }}

    {{ __('Regards,') }}
    {{ config('app.name') }}
@endcomponent
