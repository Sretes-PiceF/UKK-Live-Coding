<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\PengaturanController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\PelangganMiddleware;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login-pelanggan', [AuthController::class, 'ViewLoginPelanggan'])->name('login.pelanggan');
Route::get('/login-admin', [AuthController::class, 'ViewLoginAdmin'])->name('login.admin');
Route::get('/register-pelanggan', [AuthController::class, 'ViewRegisterPelanggan'])->name('register');
Route::get('/register-admin', [AuthController::class, 'ViewRegisterAdmin'])->name('register.admin');
Route::post('/action-register-pelanggan', [AuthController::class, 'ActionRegisterPelanggan'])->name('action.register-pelanggan');
Route::post('/action-register-admin', [AuthController::class, 'ActionRegisterAdmin'])->name('action.register-admin');
Route::post('/action-login', [AuthController::class, 'ActionLogin'])->name('action.login');
Route::get('/action-logout', [AuthController::class, 'logout'])->name('action.logout');

Route::middleware(PelangganMiddleware::class)->group(function () {
    // Protected routes for Pelanggan
    //Pelanggan Routes

    Route::get('/pelanggan/tagihan', [PelangganController::class, 'Tagihan'])->name('pelanggan.tagihan');
    Route::get('/pelanggan/total', [PelangganController::class, 'Total'])->name('pelanggan.total');
});

Route::middleware(AdminMiddleware::class)->group(function () {
    // Protected routes for Admin
    //Admin Routes
    Route::get('/admin/tagihan', [AdminController::class, 'TagihanAdmin'])->name('admin.tagihan');
    Route::get('/admin/total', [AdminController::class, 'TotalAdmin'])->name('admin.total');
    Route::get('/admin/pelanggan', [AdminController::class, 'PelangganAdmin'])->name('admin.pelanggan');

    //Route Pelanggan 
    Route::get('/admin/pelanggan/edit/{id}', [AdminController::class, 'editPelanggan'])->name('admin.pelanggan.edit');
    Route::patch('/admin/pelanggan/update/{id}', [AdminController::class, 'updatePelanggan'])->name('admin.pelanggan.update');
    Route::delete('/admin/pelanggan/delete/{id}', [AdminController::class, 'deletePelanggan'])->name('admin.pelanggan.delete');
    Route::get('/admin/pelanggan/search', [AdminController::class, 'searchPelanggan'])->name('admin.pelanggan.search');

    //Route Tagihan
    Route::get('/admin/tagihan/create', [AdminController::class, 'createTagihan'])->name('admin.tagihan.create');
    Route::post('/admin/tagihan/store', [AdminController::class, 'storeTagihan'])->name('admin.tagihan.store');
    Route::get('/admin/tagihan/edit/{id}', [AdminController::class, 'editTagihan'])->name('admin.tagihan.edit');
    Route::patch('/admin/tagihan/update/{id}', [AdminController::class, 'updateTagihan'])->name('admin.tagihan.update');
    Route::delete('/admin/tagihan/delete/{id}', [AdminController::class, 'deleteTagihan'])->name('admin.tagihan.delete');

    //Route Pengaturan
    Route::get('/admin/pengaturan', [PengaturanController::class, 'edit'])->name('admin.pengaturan.edit');
    Route::post('/admin/pengaturan/update', [PengaturanController::class, 'update'])->name('admin.pengaturan.update');
});
