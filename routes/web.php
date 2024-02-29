<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdvertisementController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CompanyController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ResultController;
use App\Http\Controllers\Admin\SurveyController;
use App\Http\Controllers\Admin\SurveyUserController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\VideoController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::prefix('public')->group(static function () {
    Route::get('/{company}/surveys', [\App\Http\Controllers\Public\SurveyController::class, 'index'])->name('public.company.statistics');
});

Route::get('', static function () {
    return redirect()->route('admin.admins.list');
});

// Auth Routes
Route::prefix('auth')->group(function () {
    Route::get('', static function () {
        return redirect()->route('admin.login.get');
    });

    Route::view('login', 'admin.auth.login')->name('admin.login.get');
    Route::post('login', [AuthController::class, 'login'])->name('admin.login.post');

    Route::view('forgot-password', 'admin.auth.forgot-password')->name('admin.forgot-password.get');
    Route::post('forgot-password', [AuthController::class, 'forgotPassword'])->name('admin.forgot-password.post');

    Route::view('reset-password', 'admin.auth.reset-password')->name('admin.reset-password.get');
    Route::post('reset-password', [AuthController::class, 'resetPassword'])->name('admin.reset-password.post');
});

Route::middleware('auth:web')->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::any('logout', [AuthController::class, 'logout'])->name('admin.logout');

    Route::prefix('surveys')->group(function () {
        Route::get('', [SurveyController::class, 'index'])->name('admin.surveys.list');
        Route::get('create', [SurveyController::class, 'create'])->name('admin.surveys.create');
        Route::post('save', [SurveyController::class, 'save'])->name('admin.surveys.save');
        Route::get('{id}/edit', [SurveyController::class, 'edit'])->name('admin.surveys.edit');
        Route::get('{id}/view', [SurveyController::class, 'view'])->name('admin.surveys.view');
        Route::get('{id}/delete', [SurveyController::class, 'delete'])->name('admin.surveys.delete');
        Route::get('{id}/status/update', [SurveyController::class, 'updateStatus'])->name('admin.surveys.status');
        Route::get('{id}/export-survey-pdf', [SurveyController::class, 'exportChartPdf'])->name('export-chart-pdf');
        Route::get('{id}/export-survey-csv', [SurveyController::class, 'exportChartCsv'])->name('export-chart-csv');
    });

    Route::prefix('companies')->group(function () {
        Route::get('', [CompanyController::class, 'index'])->name('admin.companies.list');
        Route::get('create', [CompanyController::class, 'create'])->name('admin.companies.create');
        Route::post('save', [CompanyController::class, 'save'])->name('admin.companies.save');
        Route::get('{id}/edit', [CompanyController::class, 'edit'])->name('admin.companies.edit');
        Route::get('{id}/delete', [CompanyController::class, 'delete'])->name('admin.companies.delete');
    });

    Route::prefix('admins')->group(function () {
        Route::get('', [AdminController::class, 'index'])->name('admin.admins.list');
        Route::get('create', [AdminController::class, 'create'])->name('admin.admins.create');
        Route::post('save', [AdminController::class, 'save'])->name('admin.admins.save');
        Route::get('{id}/edit', [AdminController::class, 'edit'])->name('admin.admins.edit');
        Route::get('{id}/delete', [AdminController::class, 'delete'])->name('admin.admins.delete');
    });

    Route::prefix('advertisements')->group(function () {
        Route::get('', [AdvertisementController::class, 'index'])->name('admin.advertisement.list');
        Route::get('create', [AdvertisementController::class, 'create'])->name('admin.advertisement.create');
        Route::post('store', [AdvertisementController::class, 'store'])->name('admin.advertisement.store');
        Route::get('{advertisement}/edit', [AdvertisementController::class, 'edit'])->name('admin.advertisement.edit');
        Route::post('{advertisement}/update', [AdvertisementController::class, 'update'])->name('admin.advertisement.update');
        Route::get('{advertisement}/delete', [AdvertisementController::class, 'destroy'])->name('admin.advertisement.delete');
    });

    Route::prefix('video')->group(function () {
        Route::get('', [VideoController::class, 'index'])->name('admin.video.list');
    });

    Route::get('/export-users', [UserController::class, 'exportUsers'])->name('export-users');
    Route::get('/export-users-pdf', [UserController::class, 'exportUsersPdf'])->name('export-users-pdf');


    Route::get('survey_users', [SurveyUserController::class, 'index'])->name('admin.survey_users.list');

    Route::prefix('results')->group(function () {
        Route::get('', [ResultController::class, 'index'])->name('admin.results.list');
    });
});
