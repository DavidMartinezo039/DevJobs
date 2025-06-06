<?php

namespace App\Jobs;

use App\Models\ActivityLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class GenerateUserHistoryPdf implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $requestedByUserId;

    public function __construct($requestedByUserId)
    {
        $this->requestedByUserId = $requestedByUserId;
    }

    public function handle()
    {
        $logs = ActivityLog::with('user')->latest()->get();

        $grouped = $logs->groupBy(function ($log) {
            return $log->user->getRoleNames()->first() ?? 'unknown';
        })->map(function ($items) {
            return $items->groupBy('action');
        });

        $timestamp = Carbon::now()->format('Y-m-d_H-i-s');
        $filename = "user-history-{$timestamp}.pdf";
        $path = "reports/{$filename}";

        $pdf = Pdf::loadView('pdfs.user-history', compact('grouped'));

        Storage::disk('public')->put($path, $pdf->output());
    }
}
