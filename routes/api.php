<?php

use App\Http\Controllers\Api\ApiAdvertisementController;
use App\Http\Controllers\Api\ApiParentController;
use App\Http\Controllers\Api\ApiVideoController;
use App\Http\Controllers\Api\SurveyController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('questions', [SurveyController::class, 'index']);
Route::get('gagnants', [SurveyController::class, 'gagnants']);
Route::get('answers', [SurveyController::class, 'allDoneSurvey']);
Route::get('videos', [SurveyController::class, 'videos']);

Route::post('submit', [SurveyController::class, 'submit']);

Route::post('check_email', [SurveyController::class, 'checkEmail']);
Route::post('next_survey', [SurveyController::class, 'next_survey']);
Route::post('bulk-submit', [SurveyController::class, 'bulkSubmit']);

Route::prefix('advertisement')->group(function () {
    Route::get('latest', [ApiAdvertisementController::class, 'latest']);
    Route::get('count', [ApiAdvertisementController::class, 'countAdvertisement']);
    Route::post('sync', [ApiAdvertisementController::class, 'syncAdvertisementFromLocal']);
});

Route::prefix('videos')->group(function () {
    Route::post('sync', [ApiVideoController::class, 'syncVideosFromLocal']);
});

Route::prefix("parrain")->group(function () {
   Route::get("next-code", [ApiParentController::class, "nextCode"])->name("next-code");
   Route::get("objectif", [ApiParentController::class, "objectif"])->name("objectif");

    Route::post('submit', [ApiParentController::class, 'submit']);

    Route::post('bulk-submit', [ApiParentController::class, 'bulkSubmit']);
});

