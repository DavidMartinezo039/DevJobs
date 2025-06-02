@component('mail::message')
    # {{ __('Hello :name!', ['name' => $user->name]) }}

    {{ __('We found a new vacancy that matches your preferences:') }}

    **{{ __('Title:') }}** {{ $vacancy->title }}
    **{{ __('Company:') }}** {{ $vacancy->company }}
    **{{ __('Category:') }}** {{ $vacancy->category->name ?? '-' }}
    **{{ __('Salary:') }}** {{ $vacancy->salary->salary ?? '-' }}

    @component('mail::button', ['url' => route('vacancies.show', $vacancy)])
        {{ __('View Vacancy') }}
    @endcomponent

    {{ __('Thanks for using our platform.') }}

@endcomponent
