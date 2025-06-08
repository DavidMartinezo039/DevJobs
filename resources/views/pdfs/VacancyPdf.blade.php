<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Vacancy PDF</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            color: #333;
            line-height: 1.5;
            margin: 80px 40px;
        }

        h1 {
            color: #2c3e50;
            font-size: 24px;
            border-bottom: 2px solid #ccc;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .label {
            font-weight: bold;
            display: inline-block;
            width: 120px;
            color: #2c3e50;
        }

        p {
            margin: 5px 0 10px 0;
        }

        .section {
            margin-bottom: 20px;
        }

        img {
            max-width: 250px;
            border: 1px solid #ccc;
            padding: 5px;
        }

        header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 60px;
            text-align: center;
            line-height: 35px;
            font-size: 10px;
            color: #555;
            border-bottom: 1px solid #eee;
            padding: 0 40px;
            background-color: #f9f9f9;
            box-sizing: border-box;
        }

        footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: 60px;
            text-align: center;
            line-height: 35px;
            font-size: 9px;
            color: #555;
            border-top: 1px solid #eee;
            padding: 0 40px;
            background-color: #f9f9f9;
            box-sizing: border-box;
        }

        .pagenum:before {
            content: counter(page);
        }
    </style>
</head>
<body>

<header>
    <h3>{{ __('Job vacancy') }} - {{ $vacancy->title }}</h3>
</header>

<footer>
    {{ __('Generated on') }} {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }} — {{ __('Page') }} <span class="pagenum"></span>
</footer>

<h1>{{ $vacancy->title }}</h1>

<div style="display: table; width: 100%;">
    <div style="display: table-cell; width: 60%; vertical-align: top;">
        <div class="section">
            <p><span class="label">{{__('Company')}}:</span> {{ $vacancy->company }}</p>
            <p><span class="label">{{__('Category')}}:</span> {{ $vacancy->category->category }}</p>
            <p><span class="label">{{__('Monthly Salary')}}:</span> {{ $vacancy->salary->salary }}</p>
            <p><span class="label">{{__('Last Day to Apply')}}:</span> {{ $vacancy->last_day->format('d-m-Y') }}</p>
        </div>
        <h2 style="font-size: 18px; color: #2c3e50; margin-bottom: 10px;">{{ __('Job Description') }}</h2>
        <p style="text-align: justify; line-height: 1.5; margin-right: 5%">
            {{ $vacancy->description }}
        </p>
    </div>

    <div style="display: table-cell; width: 35%; vertical-align: top; padding-right: 20px;">
        @php
            $imagePath = storage_path('app/public/vacancies/' . $vacancy->image);
        @endphp

        @if($vacancy->image && file_exists($imagePath))
            <img
                src="data:image/png;base64,{{ base64_encode(file_get_contents($imagePath)) }}"
                alt="Vacancy Image"
                style="max-width: 100%; border: 1px solid #ccc; padding: 5px;"
            >
        @endif
    </div>
</div>

</body>
</html>
