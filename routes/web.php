<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\SurveyController;
use App\Http\Middleware\AdminAuthenticated;
use Illuminate\Support\Facades\Route;

Route::get('/', [SurveyController::class, 'show'])->name('survey');
Route::post('/', [SurveyController::class, 'store'])->name('survey.store');
Route::get('/thank-you', [SurveyController::class, 'thankYou'])->name('thank-you');
Route::get('/flyer', fn() => view('flyer'))->name('flyer');
Route::get('/flyer/2up', fn() => view('flyer-2up'))->name('flyer.2up');
Route::get('/flyer/4up', fn() => view('flyer-4up'))->name('flyer.4up');

Route::get('/admin/login', [AdminController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AdminController::class, 'login'])->name('admin.login.post');
Route::post('/admin/logout', [AdminController::class, 'logout'])->name('admin.logout');

Route::middleware(AdminAuthenticated::class)->group(function () {
    Route::get('/admin', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/export', [AdminController::class, 'export'])->name('admin.export');
});
