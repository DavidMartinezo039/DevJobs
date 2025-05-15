@component('mail::message')
    # {{ __('Congratulations!') }}

    {{ __('You have been') }} **{{ __('accepted') }}** {{ __('for the position.') }}

    {{ __('Thank you for applying.') }}

    @component('mail::button', ['url' => url('/my-applications')])
        {{ __('View Position') }}
    @endcomponent

    {{ __('Regards,') }}
    {{ config('app.name') }}
@endcomponent
