<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Transaction\TransactionController;
use App\Http\Controllers\User\UserController;

Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'showLogin'])->name('login');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/', [AuthController::class, 'login'])->name('form-login');
    Route::post('/register', [AuthController::class, 'register'])->name('form-register');
});

Route::middleware('auth')->group(function () {
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [UserController::class, 'index'])->name('dashboard');
    Route::group(['prefix' => 'deposit', 'as' => 'deposit.'], function () {
        Route::get('/', [TransactionController::class, 'showDeposit'])->name('index');
        Route::post('/value', [TransactionController::class, 'deposit'])->name('form-deposit');
    });
    Route::group(['prefix' => 'transfer', 'as' => 'transfer.'], function () {
        Route::get('/', [TransactionController::class, 'showTransfer'])->name('index');
        Route::post('/value', [TransactionController::class, 'transfer'])->name('form-transfer');
    });
});
