<?php

use App\Http\Controllers\PrefectureController;
use App\Http\Controllers\UserController;
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
Route::view('/auth/activate', 'app')->name('auth.activate');
Route::view('/auth/resetpass', 'app')->name('auth.resetpass');

Route::view('/{path?}', 'app')->where('path', '.*');
