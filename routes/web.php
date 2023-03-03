<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Route;



Route::get('/', [Controller::class, 'rooda']);

Route::get('/auth/login', [AuthController::class, 'loginPage'])->name('auth.login-page');
Route::post('/auth/login', [AuthController::class, 'login'])->name('auth.login');
Route::middleware(['auth:web'])->group(function (){
    Route::get('/auth/logout', [AuthController::class, 'logout'])->name('auth.logout');

    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');

    Route::post('/projects', [ProjectController::class, 'store'])->name('project.store');
    Route::get('/projects/{uuid}', [ProjectController::class, 'show'])->name('project.show');

});
