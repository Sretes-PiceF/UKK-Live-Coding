<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [AuthController::class, 'ViewLogin'])->name('login');
Route::get('/register', [AuthController::class, 'ViewRegister'])->name('register');
