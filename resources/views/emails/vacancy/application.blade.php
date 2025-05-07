@component('mail::message')
    # ¡Tu postulación ha sido enviada!

    Hola {{ auth()->user()->name }},

    Te confirmamos que te has postulado exitosamente a la vacante "{{ $vacancy->title }}".

    @component('mail::button', ['url' => url('/vacancies/' . $vacancy->id)])
        Ver Vacante
    @endcomponent

    Gracias por confiar en nuestra plataforma.

    Saludos,<br>
    {{ config('app.name') }}
@endcomponent
