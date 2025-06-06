<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Http\Controllers\UploadController;

Route::get('/', [UploadController::class, 'index'])->name('uploads.index');
Route::post('/upload', [UploadController::class, 'store'])->name('uploads.store');
Route::get('/status', [UploadController::class, 'status'])->name('uploads.status');
