<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\MemberController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'home')->name('home');

Route::resource('books', BookController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
Route::resource('members', MemberController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
Route::resource('loans', LoanController::class)->only(['index', 'create', 'store', 'update']);
Route::get('loans-report', [LoanController::class, 'report'])->name('loans.report');
