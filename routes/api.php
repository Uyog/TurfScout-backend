<?php

use App\Http\Controllers\UserController;
use App\http\Controllers\TurfsController;
use App\http\Controllers\BookingsController;
use App\http\Controllers\PaymentsController;
use App\http\Controllers\AuthController;
use App\Http\Controllers\RefundController;
use App\Http\Controllers\ReviewsController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\ResetPasswordController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\UnauthorizedException;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [UserController::class, 'currentUser']);
    Route::post('/user/{id}', [UserController::class, 'updateName']);
    Route::delete('/user', [UserController::class, 'deleteAccount']);
    Route::get('/turfs/search', [TurfsController::class, 'search']);
    Route::get('/turfs/{id}', [TurfsController::class, 'readTurf']);
    Route::get('/turfs/images', [TurfsController::class, 'getTurfImageUrls']);
    Route::post("/booking", [BookingsController::class, 'createBooking']);
    Route::post('/booking/{id}/rating', [BookingsController::class, 'submitRating']);
});

Route::middleware(['auth:sanctum', 'role:creator'])->group(function () {
    Route::post('/turf', [TurfsController::class, 'createTurf']);
    Route::get('/turf', [TurfsController::class, 'readAllTurfs']);
    Route::put('/turf/{id}', [TurfsController::class, 'updateTurf']);
    Route::delete('/turf/{id}', [TurfsController::class, 'deleteTurf']);
});


//PUBLIC APIs
Route::post('/register', [AuthController::class, 'register']);
Route::post('/register-app', [AuthController::class, 'registerFromOtherApp']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('forgot-password');
Route::get('/reset-password', [ResetPasswordController::class, 'showResetForm'])->name('reset_password');



Route::post("/payment", [PaymentsController::class, 'createPayment']);
Route::get("/payment", [PaymentsController::class, 'readAllPayments']);
Route::get("/payment/{id}", [PaymentsController::class, 'readPayment']);
Route::post("/payment/{id}", [PaymentsController::class, 'updatePayment']);
Route::delete("/payment/{id}", [PaymentsController::class, 'deletePayment']);



Route::post("/refund", [RefundController::class, 'createRefund']);
Route::get("/refund", [RefundController::class, 'readAllRefunds']);
Route::get("/refund/{id}", [RefundController::class, 'readRefund']);
Route::post("/refund/{id}", [RefundController::class, 'updateRefund']);
Route::delete("/refund/{id}", [RefundController::class, 'deleteRefund']);


