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
use App\Http\Controllers\CreatorController;
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
    Route::post('/become-creator', [CreatorController::class, 'becomeCreator']);
});

Route::middleware(['auth:sanctum', 'role:creator'])->group(function () {
    Route::post('/turf', [TurfsController::class, 'createTurf']);
    Route::post('/turf/{id}', [TurfsController::class, 'updateTurf']);
});


//PUBLIC APIs
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('forgot-password');
Route::get('/reset-password', [ResetPasswordController::class, 'showResetForm'])->name('reset_password');


Route::post("/booking", [BookingsController::class, 'createBooking']);
Route::get("/booking", [BookingsController::class, 'readAllBookings']);
Route::get("/booking/{id}", [BookingsController::class, 'readBooking']);
Route::post("/booking/{id}", [BookingsController::class, 'updateBooking']);
Route::delete("/booking/{id}", [BookingsController::class, 'deleteBooking']);


Route::post("/payment", [PaymentsController::class, 'createPayment']);
Route::get("/payment", [PaymentsController::class, 'readAllPayments']);
Route::get("/payment/{id}", [PaymentsController::class, 'readPayment']);
Route::post("/payment/{id}", [PaymentsController::class, 'updatePayment']);
Route::delete("/payment/{id}", [PaymentsController::class, 'deletePayment']);


Route::post("/review", [ReviewsController::class, 'createReview']);
Route::get("/review", [ReviewsController::class, 'readAllReviews']);
Route::get("/review/{id}", [ReviewsController::class, 'readReview']);
Route::post("/review/{id}", [ReviewsController::class, 'updateReview']);
Route::delete("/review/{id}", [ReviewsController::class, 'deleteReview']);


Route::post("/refund", [RefundController::class, 'createRefund']);
Route::get("/refund", [RefundController::class, 'readAllRefunds']);
Route::get("/refund/{id}", [RefundController::class, 'readRefund']);
Route::post("/refund/{id}", [RefundController::class, 'updateRefund']);
Route::delete("/refund/{id}", [RefundController::class, 'deleteRefund']);

Route::get('/turfs/search', [TurfsController::class, 'search']);
