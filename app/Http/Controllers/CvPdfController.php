<?php

namespace App\Http\Controllers;

use App\Jobs\GenerateCVPdf;
use App\Models\CV;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class CvPdfController extends Controller
{
    public function download(CV $cv)
    {
        return GenerateCVPdf::dispatchSync($cv);
    }
}
