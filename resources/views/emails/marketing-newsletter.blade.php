@component('mail::message')
    # {{ __('Hello') }} {{ $username }}!

    {{ __('Thanks for subscribing to our marketing newsletter.') }}

    @component('mail::button', ['url' => config('app.url')])
        {{ __('Visit Our Site') }}
    @endcomponent

    {{ __('See you soon!') }}<br>
    {{ config('app.name') }}
@endcomponent
