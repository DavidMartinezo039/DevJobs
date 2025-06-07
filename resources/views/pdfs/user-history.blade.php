<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ __('User Activity History') }}</title>
    <style>
        @page {
            margin: 100px 50px;
        }

        body {
            font-family: 'Inter', sans-serif;
            font-size: 12px;
            position: relative;
            line-height: 1.6;
            color: #333;
        }

        header {
            position: fixed;
            top: -80px;
            left: 0;
            right: 0;
            height: 50px;
            background-color: #f8f8f8;
            text-align: center;
            line-height: 50px;
            font-size: 16px;
            font-weight: bold;
            color: #555;
            border-bottom: 1px solid #ddd;
            padding: 0 50px;
        }

        footer {
            position: fixed;
            bottom: -60px;
            left: 0;
            right: 0;
            height: 50px;
            font-size: 10px;
            text-align: center;
            line-height: 50px;
            border-top: 1px solid #ddd;
            color: #777;
            padding: 0 50px;
        }

        .pagenum:before {
            content: counter(page);
        }

        .watermark {
            position: fixed;
            bottom: 5%;
            right: 5%;
            transform: rotate(45deg);
            font-size: 48px;
            color: rgba(190, 190, 190, 0.2);
            z-index: -1;
            white-space: nowrap;
            pointer-events: none;
            font-weight: bold;
        }

        h1 {
            text-align: center;
            margin-bottom: 30px;
            font-size: 24px;
            color: #2c3e50;
            border-bottom: 2px solid #3498db;
            padding-bottom: 10px;
        }

        h2 {
            border-bottom: 1px solid #ccc;
            margin-top: 40px;
            margin-bottom: 20px;
            font-size: 18px;
            color: #34495e;
            padding-bottom: 5px;
        }

        h3.activity {
            margin-top: 25px;
            margin-left: 20px;
            margin-bottom: 10px;
            font-size: 16px;
            color: #2980b9;
            font-weight: normal;
        }

        .activity ul {
            list-style: disc;
            margin-left: 40px;
            padding-left: 0;
        }

        .activity li {
            margin-bottom: 8px;
        }

        .activity small {
            color: #888;
            font-size: 0.9em;
        }

        .page-break {
            page-break-before: always;
        }

        .index {
            margin-top: 30px;
            margin-bottom: 50px;
            padding: 20px;
            background-color: #f0f8ff;
            border-radius: 8px;
            border: 1px solid #e0e0e0;
        }

        .index h2 {
            font-size: 20px;
            color: #2c3e50;
            border-bottom: 1px solid #aed6f1;
            padding-bottom: 10px;
            margin-top: 0;
        }

        .index ul {
            list-style: none;
            padding-left: 0;
            margin-top: 15px;
        }

        .index li {
            margin-bottom: 8px;
        }

        .index a {
            text-decoration: none;
            color: #3498db;
            font-weight: bold;
            transition: color 0.3s ease;
        }

        .index a:hover {
            color: #2980b9;
            text-decoration: underline;
        }
    </style>
</head>
<body>

<header>
    {{ __('User Activity History Report') }}
</header>

<footer>
    {{ __('Generated on') }} {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }} — Page <span class="pagenum"></span>
</footer>

<div class="watermark">DevJobs Report</div>

<h1>{{ __('User Activity History') }}</h1>

<p><strong>{{ __('Generated the') }}: </strong> {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</p>

<div class="index">
    <h2>{{ __('Index by Role') }}</h2>
    <ul>
        @foreach ($grouped as $role => $activities)
            <li><a href="#role-{{ Str::slug($role) }}">{{ ucfirst($role) }}</a></li>
        @endforeach
    </ul>
</div>

@foreach ($grouped as $role => $activities)
    <div class="page-break"></div>

    <h2 id="role-{{ Str::slug($role) }}">{{ ucfirst($role) }}</h2>

    @foreach ($activities as $action => $logs)
        <h3 class="activity">{{ ucfirst(str_replace('_', ' ', $action)) }}</h3>
        <ul class="activity">
            @foreach ($logs as $log)
                <li>
                    <strong>{{ $log->user->name }}</strong> – {{ $log->description }} <br>
                    <small>{{ $log->created_at->format('Y-m-d H:i') }}</small>
                </li>
            @endforeach
        </ul>
    @endforeach
@endforeach

</body>
</html>
