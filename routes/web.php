<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Action\Deposit\DepositController;
use App\Http\Controllers\User\UserController;

Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'showLogin'])->name('login');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/', [AuthController::class, 'login'])->name('form-login');
    Route::post('/register', [AuthController::class, 'register'])->name('form-register');
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [UserController::class, 'index'])->name('dashboard');
    Route::get('/deposit', [DepositController::class, 'showDeposit'])->name('deposit');
});
