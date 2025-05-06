<?php

namespace App\Http\Controllers;

use App\Models\CV;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class CvPdfController extends Controller
{
    public function download(CV $cv)
    {
        $cv->load([
            'personalData.gender',
            'personalData.identities',
            'personalData.phones',
            'personalData.socialMedia',
            'workExperiences',
            'languages',
            'digitalSkills',
            'education',
            'drivingLicenses'
        ]);

        $filename = 'CV_' . $cv->title . '.pdf';

        $pdf = PDF::loadView('cv.pdf', compact('cv'));

        return $pdf->download($filename);
    }

}
