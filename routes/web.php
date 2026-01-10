<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\MemberController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'home')->name('home');

Route::get('login', [AuthController::class, 'showLogin'])->name('login');
Route::post('login', [AuthController::class, 'login'])->name('login.submit');
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::resource('books', BookController::class)->only(['index']);
    Route::resource('loans', LoanController::class)->only(['index']);
});

Route::middleware(['auth', 'role:petugas'])->group(function () {
    Route::resource('books', BookController::class)->only(['create', 'store', 'edit', 'update', 'destroy']);
    Route::resource('members', MemberController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
    Route::resource('loans', LoanController::class)->only(['create', 'store', 'update']);
    Route::get('loans-report', [LoanController::class, 'report'])->name('loans.report');
});
