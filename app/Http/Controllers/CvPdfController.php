<?php

namespace App\Http\Controllers;

use App\Jobs\GenerateCVPdf;
use App\Models\CV;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CvPdfController extends Controller
{
    public function download(CV $cv)
    {
        if ($cv->file_path) {
            $filePath = 'cv/' . $cv->file_path;

            if (Storage::disk('public')->exists($filePath)) {
                return Storage::disk('public')->download($filePath);
            }
        }

        GenerateCVPdf::dispatch($cv);
        return back()->with('success', 'El CV se está generando en PDF. Por favor, inténtalo más tarde.');
    }
}
