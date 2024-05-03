<?php

use Illuminate\Support\Facades\Route;
use App\http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('forgot-password', 'App\Http\Controllers\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('forgot-password', 'App\Http\Controllers\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('reset-password/{token}', 'App\Http\Controllers\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('reset-password', 'App\Http\Controllers\ResetPasswordController@reset')->name('password.update');