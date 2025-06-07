<?php

use App\Http\Controllers\CvPdfController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserHistoryController;
use App\Http\Controllers\VacancyController;
use App\Http\Controllers\VacancyPdfController;
use App\Http\Middleware\LocaleCookieMiddleware;
use App\Http\Middleware\LocaleMiddleware;
use App\Livewire\Admin\DigitalSkillManager;
use App\Livewire\Admin\DrivingLicenseManager;
use App\Livewire\Admin\DrivingLicenseRequestsManager;
use App\Livewire\Admin\GendersManager;
use App\Livewire\AppliedJobs;
use App\Livewire\Candidates;
use App\Livewire\ConfirmWithdraw;
use App\Livewire\CvManager;
use App\Livewire\Dashboard;
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

        Route::get('/cvs', CvManager::class)->name('cv.manager');
        Route::get('/cvs/{cv}/download', [CvPdfController::class, 'download'])->name('cv.download');

        Route::get('/dashboard', Dashboard::class)->middleware('rol.admin')->name('dashboard');
        Route::get('/dashboard/genders', GendersManager::class)->middleware('rol.admin')->name('genders.manager');
        Route::get('/dashboard/digital-skills', DigitalSkillManager::class)->middleware('rol.admin')->name('digital-skills.manager');
        Route::get('/dashboard/driving-licenses', DrivingLicenseManager::class)->middleware('rol.admin')->name('driving-licenses.manager');
        Route::get('/dashboard/driving-licenses-requests', DrivingLicenseRequestsManager::class)->name('god.driving-license-requests');
        Route::post('/dashboard/history/generate', [UserHistoryController::class, 'generate'])->middleware('rol.admin')->name('user.history.generate');
        Route::get('/dashboard/history/download', [UserHistoryController::class, 'download'])->middleware('rol.admin')->name('download.user.history');


        Route::get('/preferences', UserPreference::class)->name('preferences');

        Route::get('/admin/run-cleanup', function () {
            Artisan::call('requests:cleanup');
            return redirect()->back()->with('success', __('Cleaning executed successfully'));
        })->middleware('rol.admin')->name('run.cleanup');
    });

    require __DIR__.'/auth.php';
});

