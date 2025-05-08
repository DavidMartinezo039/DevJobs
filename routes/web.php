<?php

use App\Http\Controllers\CandidateController;
use App\Http\Controllers\CvPdfController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VacancyController;
use App\Livewire\AppliedJobs;
use App\Livewire\CvManager;
use App\Livewire\VacanciesManager;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/vacancies', VacanciesManager::class)->name('vacancies.manager');

    Route::get('/candidates/{vacancy}', [CandidateController::class, 'index'])->name('candidates.index');
    Route::get('/my-applications', AppliedJobs::class)->name('candidates.applied-jobs');

    Route::get('/notifications', NotificationController::class)->middleware('rol.recruiter')->name('notifications.index');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/cvs', CvManager::class)->name('cv.manager');
    Route::get('/cvs/{cv}/download', [CvPdfController::class, 'download'])->name('cv.download');

});

Route::get('/vacancies/{vacancy}', [VacancyController::class, 'show'])->name('vacancies.show');

require __DIR__.'/auth.php';
