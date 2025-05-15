@component('mail::message')
    # {{ __('Your application has been submitted!') }}

    {{ __('Hello') }} {{ auth()->user()->name }},

    {{ __('We confirm that you have successfully applied to the position') }} "{{ $vacancy->title }}".

    @component('mail::button', ['url' => url('/vacancies/' . $vacancy->id)])
        {{ __('View Position') }}
    @endcomponent

    {{ __('Thank you for trusting our platform.') }}

    {{ __('Regards,') }}
    {{ config('app.name') }}
@endcomponent
