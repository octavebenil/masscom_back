<?php

use App\Http\Controllers\Api\ApiAdvertisementController;
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
Route::get('answers', [SurveyController::class, 'allDoneSurvey']);
Route::get('videos', [SurveyController::class, 'videos']);

Route::post('submit', [SurveyController::class, 'submit']);

Route::post('check_email', [SurveyController::class, 'checkEmail']);
Route::post('bulk-submit', [SurveyController::class, 'bulkSubmit']);

Route::prefix('advertisement')->group(function () {
    Route::get('latest', [ApiAdvertisementController::class, 'latest']);
    Route::post('sync', [ApiAdvertisementController::class, 'syncAdvertisementFromLocal']);
});
