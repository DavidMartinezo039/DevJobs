@component('mail::message')
    # {{ __('You have withdrawn your application') }}

    {{ __('You have confirmed that you want to withdraw from the selection process for the position') }} **{{ $vacancy->title }}**.

    {{ __('Thank you for your interest.') }}

    {{ __('If this was a mistake, you can reapply if the position is still open.') }}

    {{ __('Regards,') }}
    {{ config('app.name') }}
@endcomponent
