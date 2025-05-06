<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>CV de {{ $cv->personalData->first_name }} {{ $cv->personalData->last_name }}</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
        }

        .container {
            padding: 30px;
        }

        .header {
            border-bottom: 2px solid #444;
            padding-bottom: 20px;
        }

        .header::after {
            content: "";
            display: table;
            clear: both;
        }

        .a {
            width: 45%;
            height: 100px;
            float: left;
            box-sizing: border-box;
            padding: 10px;
        }

        .photo {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #444;
        }

        .info {
            text-align: right;
        }

        .info h1 {
            margin: 0;
            font-size: 22px;
        }

        .info p {
            margin: 3px 0;
        }

        .content {
            display: flex;
            margin-top: 20px;
        }

        .left {
            width: 30%;
            float: left;
            padding-right: 10px;
            border-right: 1px solid #ccc;
        }

        .right {
            width: 70%;
            float: left;
            padding-left: 10px;
        }

        .section {
            margin-bottom: 20px;
        }

        .section h3 {
            background-color: #444;
            color: white;
            padding: 5px;
            font-size: 14px;
        }

        ul {
            padding-left: 15px;
            margin: 0;
        }

        li {
            margin-bottom: 6px;
        }

        .small-text {
            font-size: 11px;
            color: #555;
        }

        .label {
            font-weight: bold;
        }

    </style>
</head>
<body>
<div class="container">

    {{-- Encabezado con foto e info --}}
    <div class="header">
        <div class="a">
            @if($cv->personalData->image)
                <img
                    src="data:image/png;base64,{{ base64_encode(file_get_contents(storage_path('app/public/images/' . $cv->personalData->image))) }}"
                    class="photo" alt="photo">
            @endif
        </div>
        <div class="info a">
            <h1>{{ $cv->personalData->first_name }} {{ $cv->personalData->last_name }}</h1>
            <p>{{ is_array($cv->personalData->email) ? implode(', ', $cv->personalData->email) : $cv->personalData->email }}</p>
            <p>{{ $cv->personalData->city }} - {{ $cv->personalData->country }}</p>
            <p>{{ is_array($cv->personalData->nationality) ? implode(', ', $cv->personalData->nationality) : $cv->personalData->nationality ?? '-' }}</p>
        </div>
    </div>

    {{-- Contenido principal dividido en dos columnas --}}
    <div class="content">

        {{-- Columna izquierda --}}
        <div class="left">

            {{-- Habilidades --}}
            <div class="section">
                <h3>{{ __('Digital Skills') }}</h3>
                <ul>
                    @foreach($cv->digitalSkills as $skill)
                        <li>{{ $skill->name }} - {{ $skill->pivot->level }}</li>
                    @endforeach
                </ul>
            </div>

            {{-- Idiomas --}}
            <div class="section">
                <h3>{{ __('Languages') }}</h3>
                <ul>
                    @foreach($cv->languages as $lang)
                        <li>{{ $lang->name }} - {{ $lang->pivot->level }}</li>
                    @endforeach
                </ul>
            </div>

            <div class="section">
                <h3>{{ __('Driving Licenses') }}</h3>
                <ul>
                    @foreach($cv->drivingLicenses as $licence)
                        <li>{{ $licence->category }} - {{ $licence->vehicle_type }}</li>
                    @endforeach
                </ul>
            </div>

            {{-- Redes sociales --}}
            @if($cv->personalData->socialMedia->count())
                <div class="section">
                    <h3>{{ __('Social Media') }}</h3>
                    <ul>
                        @foreach($cv->personalData->socialMedia as $sm)
                            <li>{{ $sm->type }}: {{ $sm->pivot->user_name }}</li>
                            <p>{{ $sm->pivot->url }}</p>
                        @endforeach
                    </ul>
                </div>
            @endif

        </div>

        {{-- Columna derecha --}}
        <div class="right">

            {{-- Perfil personal --}}

            <div class="section">
                <h3>{{ __('Personal Data') }}</h3>
                <ul>
                    <span class="label">{{ __('Permits') }}</span>
                    @foreach($cv->personalData->workPermits as $permit)
                        <li>
                            <span>{{ $permit }}</span>
                        </li>
                    @endforeach
                </ul>
                <br>

                <span class="label">{{ __('Birth Date') }}</span>
                <p> {{ $cv->personalData->birth_date }}</p><br>

                <ul>
                    <span class="label">{{ __('Addresses') }}</span>
                    @foreach($cv->personalData->address as $address)
                        <li>
                            <span>{{ $address }}</span>
                        </li>
                    @endforeach
                </ul>
                <br>

                <span class="label">{{ __('About Me') }}</span>
                <p>{{ $cv->personalData->about_me }}</p>
            </div>

            {{-- Experiencia laboral --}}
            @if($cv->workExperiences->count())
                <div class="section">
                    <h3>{{ __('Work Experiences') }}</h3>
                    <ul>
                        @foreach($cv->workExperiences as $exp)
                            <li>
                                <span class="label">{{ $exp->position }}</span> en {{ $exp->company_name }}<br>
                                <span
                                    class="small-text">{{ $exp->start_date }} - {{ $exp->end_date ?? 'Actualidad' }}</span><br>
                                <em>{{ $exp->description }}</em>
                            </li><br>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Educación --}}
            @if($cv->education->count())
                <div class="section">
                    <h3>{{ __('Education') }}</h3>
                    <ul>
                        @foreach($cv->education as $edu)
                            <li>
                                <span class="label">{{ $edu->title }}</span> - {{ $edu->institution }}<br>
                                <span
                                    class="small-text">{{ $edu->start_date }} - {{ $edu->end_date ?? __('Actuality') }}</span><br>
                                <span>{{ $edu->city }} - {{ $edu->country }}</span>
                            </li><br>
                        @endforeach
                    </ul>
                </div>
            @endif

        </div>

    </div>
</div>
</body>
</html>
