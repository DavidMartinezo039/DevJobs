<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Models\EditRequest;
use App\Models\Vacancy;
use Carbon\Carbon;

class CleanupOldThings extends Command
{
    protected $signature = 'requests:cleanup';
    protected $description = 'Elimina solicitudes no resueltas antiguas y vacantes expiradas con sus CVs';

    public function handle()
    {
        $this->cleanupEditRequests();
        $this->cleanupExpiredVacancies();
    }

    protected function cleanupEditRequests()
    {
        $threshold = Carbon::now()->subDays(30);

        $deleted = EditRequest::whereNull('approved')
            ->where('created_at', '<', $threshold)
            ->delete();

        $this->info("Se eliminaron {$deleted} solicitudes de edición sin resolver antiguas.");
    }

    protected function cleanupExpiredVacancies()
    {
        $threshold = Carbon::now()->subMonth();
        $expiredVacancies = Vacancy::where('last_day', '<', $threshold)->get();

        $vacancyCount = 0;
        $cvCount = 0;

        foreach ($expiredVacancies as $vacancy) {
            $pivotData = $vacancy->users()->withPivot('cv')->get();

            foreach ($pivotData as $user) {
                $cvFile = $user->pivot->cv;

                if ($cvFile && Storage::disk('public')->exists("cv/{$cvFile}")) {
                    Storage::disk('public')->delete("cv/{$cvFile}");
                    $cvCount++;
                }
            }

            $vacancy->users()->detach();

            if ($vacancy->image && $vacancy->image !== 'default.png') {
                if (Storage::disk('public')->exists("vacancies/{$vacancy->image}")) {
                    Storage::disk('public')->delete("vacancies/{$vacancy->image}");
                }
            }

            $vacancy->delete();
            $vacancyCount++;
        }

        $this->info("Se eliminaron {$vacancyCount} vacantes expiradas y {$cvCount} CVs del storage.");
    }
}
