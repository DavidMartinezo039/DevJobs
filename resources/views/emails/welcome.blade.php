@component('mail::message')
    # {{ __('Hello :name!', ['name' => $user->name]) }}

    {{ __('Thank you for registering on our platform. We are thrilled to have you with us.') }}

    @component('mail::button', ['url' => url('/')])
        {{ __('Go to the homepage') }}
    @endcomponent

    {{ __('We hope you enjoy the experience!') }}
    {{ __('The :app team', ['app' => config('app.name')]) }}
@endcomponent
