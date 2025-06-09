<?php

use App\Http\Controllers\Admin\UserHistoryController;
use App\Http\Controllers\CvPdfController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VacancyController;
use App\Http\Controllers\VacancyPdfController;
use App\Http\Middleware\LocaleCookieMiddleware;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\DigitalSkillManager;
use App\Livewire\Admin\DrivingLicenseManager;
use App\Livewire\Admin\DrivingLicenseRequestsManager;
use App\Livewire\Admin\GendersManager;
use App\Livewire\AppliedJobs;
use App\Livewire\Candidates;
use App\Livewire\ConfirmWithdraw;
use App\Livewire\CvManager;
use App\Livewire\UserPreference;
use App\Livewire\VacanciesManager;
use Illuminate\Support\Facades\Route;

/*
Route::prefix('/{locale}')->middleware(LocaleMiddleware::class)->group(function () {

});
*/
Route::get('locale/{locale}', function ($locale) {
    return redirect()->back()->withCookie(cookie('locale', $locale));
});

Route::middleware(LocaleCookieMiddleware::class)->group(function () {

    Route::get('/', HomeController::class)->name('home');
    Route::get('/vacancies/{vacancy}/download', [VacancyPdfController::class, 'download'])->name('vacancy.download');
    Route::get('/vacancies/{vacancy}', [VacancyController::class, 'show'])->name('vacancies.show');

    Route::middleware(['auth', 'verified'])->group(function () {
        Route::get('/vacancies', VacanciesManager::class)->name('vacancies.manager');

        Route::get('/vacancies/{vacancy}/confirm-withdraw', ConfirmWithdraw::class)
            ->middleware('signed')
            ->name('vacancy.confirmWithdraw');

        Route::get('/candidates/{vacancy}', Candidates::class)->name('candidates.index');
        Route::get('/my-applications', AppliedJobs::class)->name('candidates.applied-jobs');

        Route::get('/notifications', NotificationController::class)->middleware('rol.recruiter')->name('notifications.index');

        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
        Route::get('profile/token', [ProfileController::class, 'showTokenFromCommand'])->name('profile.token');


        Route::get('/cvs', CvManager::class)->name('cv.manager');
        Route::get('/cvs/{cv}/download', [CvPdfController::class, 'download'])->name('cv.download');

        Route::middleware('rol.admin')->prefix('dashboard')->group(function () {
            Route::get('/', Dashboard::class)->name('dashboard');
            Route::get('/genders', GendersManager::class)->name('genders.manager');
            Route::get('/digital-skills', DigitalSkillManager::class)->name('digital-skills.manager');
            Route::get('/driving-licenses', DrivingLicenseManager::class)->name('driving-licenses.manager');
            Route::get('/driving-licenses-requests', DrivingLicenseRequestsManager::class)->name('god.driving-license-requests');
            Route::post('/history/generate', [UserHistoryController::class, 'generate'])->name('user.history.generate');
            Route::get('/history/download', [UserHistoryController::class, 'download'])->name('download.user.history');
        });


        Route::get('/preferences', UserPreference::class)->name('preferences');
    });

    require __DIR__.'/auth.php';
});

