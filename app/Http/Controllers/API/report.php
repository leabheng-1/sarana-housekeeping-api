<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Guest;
use App\Models\Rooms;
use Illuminate\Http\Request;
use App\Http\Controllers\API\FunctionValidatorAndInsert;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class report extends BaseController
{

    public function weekly(Request $request) {
    // Calculate the start and end of the current week using Carbon.
    $startOfWeek = Carbon::now()->startOfWeek(); // Monday
    $endOfWeek = Carbon::now()->endOfWeek(); // Sunday

    $roomType = $request->input('room_type');
    $roomNumber = $request->input('room_number');
    $roomStatus = $request->input('room_status');

    // Initialize an array to store the daily check-in, check-out, payment, and total payment counts.
    $weeklyReport = [];

    // Loop through each day of the week (Monday to Sunday) and calculate check-ins, check-outs, payments, and total payments.
    $currentDate = $startOfWeek;
    while ($currentDate <= $endOfWeek) {
        $checkInCount = DB::table('bookings')
            ->join('rooms', 'bookings.room_id', '=', 'rooms.id')
            ->whereDate('bookings.checkin_date', $currentDate)
            ->when($roomType, function ($query) use ($roomType) {
                return $query->where('rooms.room_type', $roomType);
            })
            ->when($roomNumber, function ($query) use ($roomNumber) {
                return $query->where('rooms.room_number', $roomNumber);
            })
            ->when($roomStatus, function ($query) use ($roomStatus) {
                return $query->where('rooms.room_status', $roomStatus);
            })
            ->count();

        $checkOutCount = DB::table('bookings')
            ->join('rooms', 'bookings.room_id', '=', 'rooms.id')
            ->whereDate('bookings.checkout_date', $currentDate)
            ->when($roomType, function ($query) use ($roomType) {
                return $query->where('rooms.room_type', $roomType);
            })
            ->when($roomNumber, function ($query) use ($roomNumber) {
                return $query->where('rooms.room_number', $roomNumber);
            })
            ->when($roomStatus, function ($query) use ($roomStatus) {
                return $query->where('rooms.room_status', $roomStatus);
            })
            ->count();

        // Calculate payments and total payments based on your application's logic.

        $payment = DB::table('payments')
            ->join('bookings', 'payments.id', '=', 'bookings.payment_id')
            ->whereDate('bookings.checkout_date', $currentDate)
            ->when($roomType, function ($query) use ($roomType) {
                return $query->join('rooms', 'bookings.room_id', '=', 'rooms.id')
                             ->where('rooms.room_type', $roomType);
            })
            ->when($roomNumber, function ($query) use ($roomNumber) {
                return $query->join('rooms', 'bookings.room_id', '=', 'rooms.id')
                             ->where('rooms.room_number', $roomNumber);
            })
            ->when($roomStatus, function ($query) use ($roomStatus) {
                return $query->join('rooms', 'bookings.room_id', '=', 'rooms.id')
                             ->where('rooms.room_status', $roomStatus);
            })
            ->sum('payments.payment');

        $weeklyReport[date('l', strtotime($currentDate))] = [
            'date' => $currentDate->format('Y-m-d'),
            'check_in' => $checkInCount,
            'check_out' => $checkOutCount,
            'payment' => $payment,
        ];
        $totalPayment = $payment + $payment ;
        // Move to the next day.
        $currentDate->addDay();
        
    }

    return $this->sendResponse($weeklyReport, 'Weekly report retrieved successfully');
}

public function monthly(Request $request) {
    // Calculate the start and end of the current month using Carbon.
    $startOfMonth = Carbon::now()->startOfMonth(); // First day of the month
    $endOfMonth = Carbon::now()->endOfMonth();     // Last day of the month

    $roomType = $request->input('room_type');
    $roomNumber = $request->input('room_number');
    $roomStatus = $request->input('room_status');

    // Initialize an array to store the weekly check-in, check-out, payment, and total payment counts.
    $monthlyReport = [];

    // Loop through each day of the month and calculate check-ins, check-outs, payments, and total payments.
    $currentDate = $startOfMonth;
    $currentWeek = 1; // Initialize the week of the month to 1.

    while ($currentDate <= $endOfMonth) {
        // Calculate the week of the month.
        $weekOfMonth = ceil($currentDate->day / 7);

        if ($weekOfMonth > $currentWeek) {
            // We've moved to a new week of the month. Initialize counts for the new week.
            $currentWeek = $weekOfMonth;
            $checkInCount = 0;
            $checkOutCount = 0;
        }

        $checkInCount = DB::table('bookings')
            ->join('rooms', 'bookings.room_id', '=', 'rooms.id')
            ->whereYear('bookings.checkin_date', $currentDate->year)
            ->whereMonth('bookings.checkin_date', $currentDate->month)
            ->when($roomType, function ($query) use ($roomType) {
                return $query->where('rooms.room_type', $roomType);
            })
            ->when($roomNumber, function ($query) use ($roomNumber) {
                return $query->where('rooms.room_number', $roomNumber);
            })
            ->when($roomStatus, function ($query) use ($roomStatus) {
                return $query->where('rooms.room_status', $roomStatus);
            })
            ->count();

        $checkOutCount = DB::table('bookings')
            ->join('rooms', 'bookings.room_id', '=', 'rooms.id')
            ->whereYear('bookings.checkout_date', $currentDate->year)
            ->whereMonth('bookings.checkout_date', $currentDate->month)
            ->when($roomType, function ($query) use ($roomType) {
                return $query->where('rooms.room_type', $roomType);
            })
            ->when($roomNumber, function ($query) use ($roomNumber) {
                return $query->where('rooms.room_number', $roomNumber);
            })
            ->when($roomStatus, function ($query) use ($roomStatus) {
                return $query->where('rooms.room_status', $roomStatus);
            })
            ->count();

        // Calculate payments and total payments based on your application's logic.

        $payment = DB::table('payments')
            ->join('bookings', 'payments.id', '=', 'bookings.payment_id')
            ->whereYear('bookings.checkout_date', $currentDate->year)
            ->whereMonth('bookings.checkout_date', $currentDate->month)
            ->when($roomType, function ($query) use ($roomType) {
                return $query->join('rooms', 'bookings.room_id', '=', 'rooms.id')
                             ->where('rooms.room_type', $roomType);
            })
            ->when($roomNumber, function ($query) use ($roomNumber) {
                return $query->join('rooms', 'bookings.room_id', '=', 'rooms.id')
                             ->where('rooms.room_number', $roomNumber);
            })
            ->when($roomStatus, function ($query) use ($roomStatus) {
                return $query->join('rooms', 'bookings.room_id', '=', 'rooms.id')
                             ->where('rooms.room_status', $roomStatus);
            })
            ->sum('payments.payment');

        // Store the counts in the weekly report under the current week.
        $monthlyReport[$currentWeek] = [
            'start_date' => $currentDate->format('Y-m-d'),
            'end_date' => $currentDate->format('Y-m-d'),
            'check_in' => $checkInCount,
            'check_out' => $checkOutCount,
            'payment' => $payment,
        ];

        // Move to the next day.
        $currentDate->addDay();
    }

    return $this->sendResponse($monthlyReport, 'Monthly report retrieved successfully');
}
 

    
 public function daily(Request $request){
    $query = DB::table('bookings')
        ->join('guests', 'bookings.guest_id', '=', 'guests.id')
        ->join('payments', 'bookings.payment_id', '=', 'payments.id')
        ->join('rooms', 'bookings.room_id', '=', 'rooms.id');

    if ($request->has('room_status')) {
        $roomStatus = $request->input('room_status');
        $query->where('rooms.room_status', $roomStatus);
    }

    if ($request->has('start_date') && $request->has('end_date')) {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $query->whereBetween('bookings.checkin_date', [$startDate, $endDate]);
    }

    if ($request->has('room_type')) {
        $roomType = $request->input('room_type');
        $query->where('rooms.roomtype', $roomType);
    }

    if ($request->has('room_number')) {
        $roomNumber = $request->input('room_number');
        $query->where('rooms.room_number', $roomNumber);
    }

    $daily = $query->select('bookings.*')->get();

    return $this->sendResponse($daily, 'Booking(s) retrieved successfully');
}
   
 
}