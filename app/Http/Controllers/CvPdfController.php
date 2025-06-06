<?php

namespace App\Http\Controllers;

use App\Jobs\GenerateCVPdf;
use App\Models\CV;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Traits\LogsActivity;

class CvPdfController extends Controller
{
    use LogsActivity;

    public function download(CV $cv)
    {
        if ($cv->file_path) {
            $filePath = 'cv/' . $cv->file_path;

            if (Storage::disk('public')->exists($filePath)) {
                $this->logActivity(
                    action: 'download_cv',
                    targetType: 'App\Models\CV',
                    targetId: $cv->id,
                    description: 'Descargo su currículum'
                );
                return Storage::disk('public')->download($filePath, 'CV_' . $cv->title . '.pdf');
            }
        }

        GenerateCVPdf::dispatch($cv);
        return back()->with('success', __('Your CV is being generated as a PDF. Please try again later.'));
    }
}
