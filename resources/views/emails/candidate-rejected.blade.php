@component('mail::message')
    # {{ __('Thank you for your interest') }}

    {{ __('Unfortunately, after carefully reviewing your application, we have decided not to proceed with the process for this position.') }}

    {{ __('We truly appreciate the time you invested and encourage you to apply for future opportunities.') }}

    @component('mail::button', ['url' => url('/')])
        {{ __('Explore more vacancies') }}
    @endcomponent

    {{ __('Kind regards,') }}
    {{ config('app.name') }}
@endcomponent
