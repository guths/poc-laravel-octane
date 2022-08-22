<?php

use App\Http\Controllers\LogController;
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

Route::get('/', [LogController::class, 'index']);
Route::post('/sendLog', [LogController::class, 'store'])->name('sendLog');
Route::post('/search', [LogController::class, 'search'])->name('search');
Route::get('/search', [LogController::class, 'indexSearch']);
