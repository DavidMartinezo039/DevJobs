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
            margin: 40px;
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

        .image {
            margin-top: 20px;
        }

        .image img {
            max-width: 250px;
            border: 1px solid #ccc;
            padding: 5px;
        }
    </style>
</head>
<body>
<h1>{{ $vacancy->title }}</h1>

<div style="display: table; width: 100%;">
    <div style="display: table-cell; width: 60%; vertical-align: top;">
        <div class="section">
            <p><span class="label">{{__('Company')}}:</span> {{ $vacancy->company }}</p>
            <p><span class="label">{{__('Category')}}:</span> {{ $vacancy->category->category }}</p>
            <p><span class="label">{{__('Monthly Salary')}}:</span> {{ $vacancy->salary->salary }}</p>
            <p><span class="label">{{__('Last Day to Apply')}}:</span> {{ $vacancy->last_day->format('d-m-Y') }}</p>
        </div>
        <h2 style="font-size: 18px; color: #2c3e50; margin-bottom: 10px;">Job Description</h2>
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
