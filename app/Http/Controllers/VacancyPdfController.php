<?php

namespace App\Http\Controllers;

use App\Jobs\GenerateVacancyPdf;
use App\Models\Vacancy;
use App\Traits\LogsActivity;
use Illuminate\Http\Request;

class VacancyPdfController extends Controller
{
    use LogsActivity;

    public function download(Vacancy $vacancy)
    {
        $this->logActivity(
            action: 'download_vacancy',
            targetType: 'App\Models\Vacancy',
            targetId: $vacancy->id,
            description: 'Download a vacancy'
        );

        return GenerateVacancyPdf::dispatchSync($vacancy);
    }
}
