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
use App\Http\Controllers\API\DashboardController;
use App\Http\Controllers\API\HousekeepingController;
use App\Http\Controllers\API\StatusController;
use App\Http\Controllers\API\report; 

Route::post('/guests/insert', [GuestsController::class, 'insert']);
Route::post('register', [RegisterController::class, 'register']);
Route::post('login', [RegisterController::class, 'login']);
Route::get('guests/select_all', [GuestsController::class, 'selectAllGuests']);
Route::delete('/guests/delete/{id}', [GuestsController::class, 'deleteGuest']);
Route::put('/guests/update/{id}', [GuestsController::class, 'updateGuest']);
Route::get('guests/find/{id}',  [GuestsController::class, 'find']);

// 
Route::put('booking/updatestatus/{action}/{id}', [BookingController::class, 'updateBookingStatus']);
Route::put('booking/update/{id}', [BookingController::class, 'update']);
Route::put('booking/cancel/{id}', [BookingController::class, 'cancel']);
Route::put('booking/void/{id}', [BookingController::class, 'void']);
Route::put('booking/move/{id}', [BookingController::class, 'moveStay']);
Route::post('booking/insert', [BookingController::class, 'insert']);
Route::get('booking/all', [BookingController::class, 'selectAllBooking']);
Route::get('booking/BookingDailog', [BookingController::class, 'BookingDailog']);
Route::get('booking/roomVariable', [BookingController::class, 'roomVariable']);
Route::get('booking/allTime', [BookingController::class, 'selectBooking']);
Route::put('booking/checkin/{bookingId}', [BookingController::class, 'checkIn']);
Route::put('booking/checkout/{bookingId}', [BookingController::class, 'checkOut']);
Route::get('booking/room_date', [BookingController::class, 'room_date']);

// 
Route::get('room/all', [RoomsController::class, 'selectAllRooms']);
Route::post('room/insert', [RoomsController::class, 'insert']);
Route::put('room/update/{id}', [RoomsController::class, 'update']);
Route::delete('room/delete/{id}', [RoomsController::class, 'delete']);
// 
Route::get('payment/all/{id}', [PaymentController::class, 'selectPaymentById']);
Route::put('booking/cancel/{id}', [BookingController::class, 'cancel']);
Route::post('payment/insert', [PaymentController::class, 'insert']);
Route::put('payment/update/{id}', [PaymentController::class, 'update']);
Route::delete('payment/delete/{id}', [PaymentController::class, 'delete']);
// 
Route::get('dashboard/today_booking', [DashboardController::class, 'todayBooking']);
Route::get('dashboard/todayStatus', [DashboardController::class, 'todayStatus']);
Route::get('dashboard/guest_today', [DashboardController::class, 'bookingsCountByDayOfWeek']);
// 
Route::post('/housekeeping/insert', [HousekeepingController::class, 'insert']);
Route::get('/housekeeping/all', [HousekeepingController::class, 'selectAllHousekeeping']);
Route::delete('/housekeeping/{id}', [HousekeepingController::class, 'delete']);
Route::put('/housekeeping/update/{id}', [HousekeepingController::class, 'update']);
Route::get('/housekeeping/find/{keyword}', [HousekeepingController::class, 'find']);
// 
Route::get('/report/monthly', [report::class, 'monthly']);
Route::get('/report/monthlyReport', [report::class, 'monthlyReport']);
Route::get('/report/weekly', [report::class, 'weekly']);
Route::get('/report/yearly', [report::class, 'yearly']);
Route::post('/backup-database', [BackupController::class, 'backupDatabase']);
Route::get('/report/daily', [report::class, 'daily']);
Route::get('/report/monthlyCharge', [report::class, 'monthlyCharge']);
Route::get('/report/monthlyGuestCount', [report::class, 'monthlyGuestCountByRoomType']);
// 
Route::get('/status/bookings', [StatusController::class, 'getBookingsByStatusToday']);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
