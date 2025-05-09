<?php

namespace App\Jobs;

use App\Models\Vacancy;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\View;
class GenerateVacancyPdf
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Vacancy $vacancy;
    public string $filename;

    public function __construct(Vacancy $vacancy)
    {
        $this->vacancy = $vacancy;
        $this->filename = 'Vacancy_' . $vacancy->title . '.pdf';
    }

    public function handle(): \Illuminate\Http\Response
    {
        $pdf = Pdf::loadView('pdfs.VacancyPdf', ['vacancy' => $this->vacancy]);

        return $pdf->download($this->filename);
    }
}
