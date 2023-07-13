<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
use App\Http\Controllers\API\RegisterController;
use App\Http\Controllers\API\GuestsController; 
use App\Http\Controllers\API\BookingController; 
use App\Http\Controllers\API\RoomsController; 
use App\Http\Controllers\API\PaymentController;
 
Route::post('/guests/insert', [GuestsController::class, 'insert']);
Route::post('register', [RegisterController::class, 'register']);
Route::post('login', [RegisterController::class, 'login']);
Route::get('guests/select_all', [GuestsController::class, 'selectAllGuests']);
Route::delete('/guests/delete/{id}', [GuestsController::class, 'deleteGuest']);
Route::put('/guests/update/{id}', [GuestsController::class, 'updateGuest']);
Route::get('guests/find/{id}',  [GuestsController::class, 'find']);

// 

Route::put('booking/update/{id}', [BookingController::class, 'update']);
Route::post('booking/insert', [BookingController::class, 'insert']);
Route::get('booking/select', [BookingController::class, 'selectAllBooking']);

// 
Route::get('room/select', [RoomsController::class, 'selectAllRooms']);
Route::post('room/insert', [RoomsController::class, 'insert']);
Route::put('room/update/{id}', [RoomsController::class, 'update']);
Route::delete('room/delete/{id}', [RoomsController::class, 'delete']);
// 
Route::get('payment/select', [PaymentController::class, 'selectAllPayment']);
Route::post('payment/insert', [PaymentController::class, 'insert']);
Route::put('payment/update/{id}', [PaymentController::class, 'update']);
Route::delete('payment/delete/{id}', [PaymentController::class, 'delete']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
