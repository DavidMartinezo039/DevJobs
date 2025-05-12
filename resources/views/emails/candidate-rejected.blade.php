@component('mail::message')
    # Gracias por tu interés

    Lamentablemente, tras revisar cuidadosamente tu candidatura, hemos decidido no continuar con el proceso para esta vacante.

    Apreciamos mucho el tiempo que dedicaste y te animamos a postularte a futuras oportunidades.

    @component('mail::button', ['url' => url('/')])
        Explorar más vacantes
    @endcomponent

    Saludos cordiales,<br>
    {{ config('app.name') }}
@endcomponent
