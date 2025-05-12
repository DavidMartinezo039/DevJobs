@component('mail::message')
    # ¡Felicidades!

    Has sido **aceptado** para la vacante.

    Gracias por postularte.

    @component('mail::button', ['url' => url('/my-applications')])
        Ver Vacante
    @endcomponent

    Saludos,<br>
    {{ config('app.name') }}
@endcomponent
