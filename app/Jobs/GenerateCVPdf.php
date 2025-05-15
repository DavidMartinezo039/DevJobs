<?php

namespace App\Jobs;

use App\Models\CV;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;

class GenerateCVPdf  implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public CV $cv;
    public string $filename;

    public function __construct(CV $cv)
    {
        $this->cv = $cv;
        $this->filename = 'CV_' . $cv->title . '.pdf';
    }

    public function handle(): void
    {
        $this->cv->load([
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

        $pdf = Pdf::loadView('pdfs.CvPdf', ['cv' => $this->cv]);


        Storage::disk('public')->put('cv/' . $this->filename, $pdf->output());

        $this->cv->file_path = $this->filename;
        $this->cv->save();
    }
}
