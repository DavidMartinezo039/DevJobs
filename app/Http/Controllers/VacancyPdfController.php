<?php

namespace App\Http\Controllers;

use App\Jobs\GenerateVacancyPdf;
use App\Models\Vacancy;
use Illuminate\Http\Request;

class VacancyPdfController extends Controller
{
    public function download(Vacancy $vacancy)
    {
        return GenerateVacancyPdf::dispatchSync($vacancy);
    }
}
