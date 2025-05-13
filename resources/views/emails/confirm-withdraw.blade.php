@component('mail::message')
    # {{ __('Confirm CV deletion') }}

    {{ __('You have requested to delete your CV from the position') }} **{{ $vacancy->title }}**.

    {{ __('Click the button below to confirm the deletion:') }}

    @component('mail::button', ['url' => $url])
        {{ __('Confirm deletion') }}
    @endcomponent

    {{ __('If you did not request this action, you can ignore this message.') }}

    {{ __('Thank you,') }}
    {{ config('app.name') }}
@endcomponent
