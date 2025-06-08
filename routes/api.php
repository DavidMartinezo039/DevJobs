<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DigitalSkillController;
use App\Http\Controllers\Api\DrivingLicenseController;
use App\Http\Controllers\Api\GenderController;
use App\Http\Controllers\Api\VacancyController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::post('/vacancies', [VacancyController::class, 'store']);
    Route::put('/vacancies/{vacancy}', [VacancyController::class, 'update']);
    Route::delete('/vacancies/{vacancy}', [VacancyController::class, 'destroy']);
    Route::get('/my-vacancies', [VacancyController::class, 'myVacancies']);

    Route::apiResource('genders', GenderController::class)->middleware('rol.admin');
    Route::post('genders/{gender}/toggle-default', [GenderController::class, 'toggleDefault'])->middleware('rol.admin');

    Route::apiResource('digital-skills', DigitalSkillController::class)->middleware('rol.admin');
    Route::apiResource('driving-licenses', DrivingLicenseController::class)->middleware('rol.admin');
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/vacancies', [VacancyController::class, 'index']);
Route::get('/vacancies/{vacancy}', [VacancyController::class, 'show']);
